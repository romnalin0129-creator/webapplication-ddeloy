document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.navigation');
    toggle?.addEventListener('click', () => {
        const isOpen = navigation.classList.toggle('open');
        toggle.setAttribute('aria-expanded', String(isOpen));
    });
    navigation?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navigation.classList.remove('open');
            toggle?.setAttribute('aria-expanded', 'false');
        });
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && navigation?.classList.contains('open')) {
            navigation.classList.remove('open');
            toggle?.setAttribute('aria-expanded', 'false');
            toggle?.focus();
        }
    });

    const revealElements = document.querySelectorAll(
        '.hero-copy, .intro, .availability-section, .section-title, .service-card, .ritual-image, .ritual-copy, .booking-steps, .faq-section, .booking-banner > div:last-child'
    );
    if ('IntersectionObserver' in window && revealElements.length) {
        document.body.classList.add('reveal-ready');
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -24px 0px' });
        revealElements.forEach(element => {
            element.classList.add('scroll-reveal');
            revealObserver.observe(element);
        });
    }

    const homeFinder = document.querySelector('#home-availability');
    if (homeFinder) {
        const service = homeFinder.querySelector('[name="service_id"]');
        const date = homeFinder.querySelector('[name="date"]');
        const message = document.querySelector('#home-availability-message');
        const results = document.querySelector('#home-availability-results');
        let availabilityRequest;

        homeFinder.addEventListener('submit', async event => {
            event.preventDefault();
            if (!service.value || !date.value) {
                message.textContent = 'กรุณาเลือกบริการและวันที่ต้องการ';
                message.classList.add('error');
                return;
            }

            availabilityRequest?.abort();
            availabilityRequest = new AbortController();
            message.classList.remove('error');
            message.textContent = 'กำลังตรวจสอบตารางคิวจริง...';
            results.replaceChildren();

            try {
                const url = new URL(homeFinder.dataset.availabilityUrl, window.location.origin);
                url.searchParams.set('date', date.value);
                url.searchParams.set('service_id', service.value);
                const response = await fetch(url, {
                    headers: { Accept: 'application/json' },
                    signal: availabilityRequest.signal,
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'ตรวจสอบรอบว่างไม่สำเร็จ');

                const availableTimes = Object.entries(result.times)
                    .filter(([, available]) => available)
                    .map(([time]) => time);
                if (!availableTimes.length) {
                    message.textContent = 'วันที่เลือกไม่มีรอบว่าง ลองเลือกวันอื่นได้เลย';
                    return;
                }

                message.textContent = `พบ ${availableTimes.length} รอบว่าง — แตะเวลาที่สะดวกเพื่อจอง`;
                availableTimes.forEach(time => {
                    const link = document.createElement('a');
                    const bookingUrl = new URL(homeFinder.action, window.location.origin);
                    bookingUrl.searchParams.set('service', service.value);
                    bookingUrl.searchParams.set('date', date.value);
                    bookingUrl.searchParams.set('time', time);
                    link.href = bookingUrl.toString();
                    link.className = 'available-time';
                    link.textContent = `${time} น.`;
                    results.append(link);
                });
            } catch (error) {
                if (error.name === 'AbortError') return;
                message.textContent = error.message;
                message.classList.add('error');
            }
        });
    }

    const form = document.querySelector('#appointment-form');
    if (!form) return;

    const service = form.querySelector('[name="service_id"]');
    const date = form.querySelector('[name="booking_date"]');
    const timeSlots = form.querySelector('#time-slots');
    const message = form.querySelector('#availability-message');
    const selectedTime = () => form.querySelector('[name="booking_time"]:checked')?.value;
    let request;

    const renderSummary = () => {
        const selected = service.options[service.selectedIndex];
        const name = selected?.value ? selected.textContent.split(' · ')[0] : 'ยังไม่ได้เลือกบริการ';
        const price = selected?.dataset.price || '0';
        const duration = selected?.dataset.duration || '—';
        const dateText = date.value
            ? new Date(`${date.value}T00:00:00`).toLocaleDateString('th-TH', { day: 'numeric', month: 'long', year: 'numeric' })
            : 'ยังไม่ได้เลือก';
        document.querySelector('#summary-name').textContent = name;
        document.querySelector('#summary-duration').textContent = `ระยะเวลา ${duration} นาที`;
        document.querySelector('#summary-date').textContent = dateText;
        document.querySelector('#summary-time').textContent = selectedTime() ? `${selectedTime()} น.` : 'ยังไม่ได้เลือก';
        document.querySelector('#summary-price').textContent = `฿${Number(price).toLocaleString('th-TH')}`;
        document.querySelector('#mobile-summary').textContent = selected?.value ? `${name} · ฿${Number(price).toLocaleString('th-TH')}` : 'เลือกบริการ';
    };

    const checkTimes = async () => {
        renderSummary();
        if (!date.value || !service.value) {
            message.textContent = 'เลือกบริการและวันที่เพื่อดูรอบเวลาว่าง';
            return;
        }
        request?.abort();
        request = new AbortController();
        message.classList.remove('error');
        message.textContent = 'กำลังตรวจสอบรอบว่าง...';
        try {
            const url = new URL(form.dataset.availabilityUrl, window.location.origin);
            url.searchParams.set('date', date.value);
            url.searchParams.set('service_id', service.value);
            if (form.dataset.bookingId) url.searchParams.set('booking_id', form.dataset.bookingId);
            const response = await fetch(url, { headers: { Accept: 'application/json' }, signal: request.signal });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'ตรวจสอบรอบว่างไม่สำเร็จ');
            let availableCount = 0;
            timeSlots.querySelectorAll('input[type="radio"]').forEach(input => {
                input.disabled = !result.times[input.value];
                if (!input.disabled) availableCount++;
                if (input.disabled && input.checked) input.checked = false;
            });
            message.textContent = availableCount ? `มี ${availableCount} รอบว่างในวันที่เลือก` : 'ขออภัย วันนี้ไม่มีรอบว่าง กรุณาเลือกวันอื่น';
            if (!availableCount) message.classList.add('error');
            renderSummary();
        } catch (error) {
            if (error.name === 'AbortError') return;
            message.textContent = error.message;
            message.classList.add('error');
        }
    };

    service.addEventListener('change', checkTimes);
    date.addEventListener('change', checkTimes);
    timeSlots.addEventListener('change', renderSummary);
    checkTimes();
});
