<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Relax Massage & Spa นวดผ่อนคลาย จองคิวออนไลน์ได้ง่าย ๆ">
    <title>Relax Massage &amp; Spa | ให้ทุกช่วงเวลาเป็นการพักผ่อน</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/relax.css') }}">
</head>
<body>
<header class="topbar">
    <a class="logo" href="{{ route('home') }}"><span class="logo-symbol">R</span><span>RELAX<small>MASSAGE &amp; SPA</small></span></a>
    <button class="menu-toggle" type="button" aria-label="เปิดเมนู">☰</button>
    <nav class="navigation"><a href="{{ route('home') }}">หน้าแรก</a><a href="#services">บริการ</a><a href="#about">เกี่ยวกับเรา</a><a href="#contact">ติดต่อเรา</a>
        @auth
            <a href="{{ route('bookings.mine') }}">ประวัติการจอง</a>
            <form class="mobile-logout" method="POST" action="{{ route('logout') }}">@csrf<button type="submit">ออกจากระบบ</button></form>
        @else
            <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
            <a href="{{ route('register') }}">สมัครสมาชิก</a>
            <a href="{{ route('password.request') }}">ลืมรหัสผ่าน?</a>
        @endauth
        @if(auth()->check() && auth()->user()->is_admin)<a href="{{ route('dashboard') }}" class="admin-nav">จัดการร้าน</a>@endif
    </nav>
    <div class="nav-cta">@auth<form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="nav-login">ออกจากระบบ</button></form>@endauth<a class="button button-gold" href="{{ route('booking.create') }}">จองคิวเลย <span>↗</span></a></div>
</header>
<main>
    @if(session('success'))<div class="notice notice-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="notice notice-error">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
    <section class="hero">
        <img class="hero-photo" src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=2200&q=90" alt="บรรยากาศสปาแสนสงบ">
        <div class="hero-overlay"></div><div class="hero-copy"><span class="eyebrow light">A MOMENT JUST FOR YOU</span><h1>ผ่อนคลายทุกสัมผัส<br>เติมเต็มช่วงเวลา<br><em>แห่งการพักผ่อน</em></h1><p>ปล่อยวางความเหนื่อยล้า แล้วกลับมาใส่ใจตัวเองอีกครั้ง<br>ด้วยศาสตร์การนวดและการดูแลที่เราตั้งใจคัดสรร</p><a class="button button-gold" href="{{ route('booking.create') }}">จองคิวเลย <span>↗</span></a><div class="hero-caption"><span></span> YOUR SANCTUARY IN THE CITY</div></div>
        <div class="hero-side-note">SLOW DOWN · BREATHE · REST</div>
    </section>
    <section class="availability-section section-wrap" id="available-times">
        <div class="availability-intro">
            <span class="eyebrow">LIVE APPOINTMENT FINDER</span>
            <h2>ค้นหารอบว่างของคุณ</h2>
            <p>เลือกรายการและวันที่ ระบบจะตรวจสอบคิวจริงให้ทันที</p>
        </div>
        <form id="home-availability" class="availability-search" method="GET" action="{{ route('booking.create') }}" data-availability-url="{{ route('booking.availability') }}">
            <label>
                <span>บริการที่สนใจ</span>
                <select name="service_id" required>
                    <option value="">เลือกบริการ</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} · {{ $service->duration_minutes }} นาที · ฿{{ number_format($service->price) }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                <span>วันที่ต้องการ</span>
                <input type="date" name="date" min="{{ today()->toDateString() }}" value="{{ today()->toDateString() }}" required>
            </label>
            <button class="button button-gold" type="submit">ตรวจสอบรอบว่าง <span>↗</span></button>
        </form>
        <p id="home-availability-message" class="availability-message" aria-live="polite">เลือกรายการและวันที่ แล้วกดตรวจสอบรอบว่าง</p>
        <div id="home-availability-results" class="home-time-results" aria-live="polite"></div>
    </section>
    <section class="intro section-wrap" id="about"><div class="intro-mark">R<span>.</span></div><div><span class="eyebrow">WELCOME TO RELAX</span><h2>ที่พักใจในวันที่โลกหมุนเร็ว</h2></div><p>เราสร้างพื้นที่สงบ อบอุ่น และเป็นส่วนตัว เพื่อให้ทุกการมาเยือนเป็นช่วงเวลาที่คุณได้หยุดพักอย่างแท้จริง ดูแลโดยนักบำบัดผู้เชี่ยวชาญ พร้อมผลิตภัณฑ์คุณภาพที่คัดสรรเพื่อผิวและความรู้สึกของคุณ</p></section>
    <section class="services-section" id="services"><div class="section-wrap"><div class="section-title"><div><span class="eyebrow">OUR TREATMENTS</span><h2>ศาสตร์แห่งการผ่อนคลาย</h2><p>เลือกการดูแลที่เหมาะกับช่วงเวลาของคุณ</p></div><a class="subtle-link" href="{{ route('booking.create') }}">ดูรอบว่าง <span>↗</span></a></div>
        <div class="service-grid">
        @forelse($services as $service)
            <article class="service-card"><a href="{{ route('booking.create', ['service' => $service->id]) }}" class="service-image">@if($service->image_path)<img src="{{ str_starts_with($service->image_path, 'http') ? $service->image_path : \Illuminate\Support\Facades\Storage::url($service->image_path) }}" alt="{{ $service->name }}">@else<div class="image-placeholder">RELAX</div>@endif<span class="duration-tag">{{ $service->duration_minutes }} นาที</span></a><div class="service-info"><span class="service-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} &nbsp; TREATMENT</span><h3>{{ $service->name }}</h3><p>{{ $service->description }}</p><div class="service-footer"><strong>฿{{ number_format($service->price) }} <small>/ ท่าน</small></strong><a class="text-book" href="{{ route('booking.create', ['service' => $service->id]) }}">จองบริการ <span>↗</span></a></div></div></article>
        @empty<div class="empty-services">กำลังเตรียมบริการพิเศษสำหรับคุณ กรุณากลับมาเยี่ยมชมอีกครั้ง</div>@endforelse
        </div>
    </div></section>
    <section class="ritual-section"><div class="ritual-image"><img src="https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=1200&q=85" alt="การดูแลอย่างพิถีพิถัน"></div><div class="ritual-copy"><span class="eyebrow">THE RELAX RITUAL</span><h2>เพราะการพักผ่อน<br>ควรเป็นเรื่องพิเศษ</h2><p>ตั้งแต่กลิ่นหอมอ่อน ๆ แสงอบอุ่น ไปจนถึงสัมผัสที่พอดี ทุกองค์ประกอบถูกออกแบบมาเพื่อให้คุณรู้สึกผ่อนคลายอย่างเป็นธรรมชาติ</p><div class="feature-list"><div><span>✦</span><p><strong>ใส่ใจทุกรายละเอียด</strong><small>ดูแลโดยนักบำบัดที่ผ่านการฝึกฝน</small></p></div><div><span>✦</span><p><strong>พื้นที่สงบเป็นส่วนตัว</strong><small>สะอาด ปลอดภัย และได้มาตรฐาน</small></p></div><div><span>✦</span><p><strong>จองคิวง่าย ไม่ต้องรอนาน</strong><small>เลือกรอบว่างและจัดการการจองออนไลน์</small></p></div></div><a class="button button-outline" href="#available-times">ค้นหาช่วงเวลาของคุณ <span>↗</span></a></div></section>
    <section class="booking-steps section-wrap">
        <div class="section-title"><div><span class="eyebrow">A SIMPLE RITUAL</span><h2>จองง่ายใน 3 ขั้นตอน</h2><p>เตรียมช่วงเวลาพักผ่อนของคุณได้ในไม่กี่คลิก</p></div></div>
        <div class="step-grid">
            <article><span class="step-number">01</span><h3>เลือกบริการ</h3><p>เลือกทรีตเมนต์ที่เหมาะกับความต้องการ พร้อมดูราคาและระยะเวลาได้ทันที</p></article>
            <article><span class="step-number">02</span><h3>เลือกรอบที่ว่าง</h3><p>ตรวจสอบตารางคิวจริงตามวันและเวลาที่คุณสะดวก</p></article>
            <article><span class="step-number">03</span><h3>ยืนยันการจอง</h3><p>เข้าสู่ระบบเพื่อส่งคำขอ แล้วติดตามหรือแก้ไขคิวได้จากประวัติการจอง</p></article>
        </div>
    </section>
    <section class="faq-section">
        <div class="section-wrap faq-layout">
            <div><span class="eyebrow">GOOD TO KNOW</span><h2>คำถามที่พบบ่อย</h2><p>ข้อมูลสั้น ๆ เพื่อให้คุณเตรียมตัวก่อนมาพักผ่อน</p><a class="subtle-link" href="#contact">สอบถามเพิ่มเติม ↗</a></div>
            <div class="faq-list">
                <details><summary>ต้องเข้าสู่ระบบก่อนจองหรือไม่?</summary><p>สามารถดูบริการและตรวจสอบรอบว่างได้โดยไม่ต้องเข้าสู่ระบบ แต่ต้องเข้าสู่ระบบก่อนยืนยันการจอง</p></details>
                <details><summary>จะทราบได้อย่างไรว่าจองสำเร็จ?</summary><p>ระบบจะแสดงเลขที่การจอง และรายการจะแสดงในหน้า “ประวัติการจอง” พร้อมสถานะรอยืนยัน</p></details>
                <details><summary>ถ้าต้องการเปลี่ยนวันหรือเวลา ทำอย่างไร?</summary><p>แก้ไขหรือยกเลิกรายการได้จากประวัติการจอง ตราบใดที่รายการยังรอยืนยันจากทางร้าน</p></details>
            </div>
        </div>
    </section>
    <section class="booking-banner"><img src="https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1900&q=80" alt="" aria-hidden="true"><div class="booking-banner-overlay"></div><div><span class="eyebrow light">YOUR TIME, YOUR WAY</span><h2>ให้วันนี้เป็นวันของคุณ</h2><p>เลือกบริการและเวลาที่สะดวก แล้วเราจะเตรียมช่วงเวลาพักผ่อนที่ดีที่สุดไว้ให้</p><a class="button button-gold" href="{{ route('booking.create') }}">จองคิวเลย <span>↗</span></a></div></section>
</main>
<footer id="contact"><div class="footer-main section-wrap"><div class="footer-brand"><a class="logo" href="{{ route('home') }}"><span class="logo-symbol">R</span><span>RELAX<small>MASSAGE &amp; SPA</small></span></a><p>คืนสมดุลให้กายและใจ<br>ด้วยช่วงเวลาที่เป็นของคุณ</p></div><div><span class="footer-label">สำรวจ</span><a href="{{ route('home') }}">หน้าแรก</a><a href="#services">บริการของเรา</a><a href="#about">เกี่ยวกับเรา</a></div><div><span class="footer-label">สำหรับคุณ</span><a href="{{ route('booking.create') }}">จองคิว</a>@auth<a href="{{ route('bookings.mine') }}">ประวัติการจอง</a>@else<a href="{{ route('login') }}">เข้าสู่ระบบ</a>@endauth</div><div><span class="footer-label">ติดต่อเรา</span><p>โทร. 02-555-0100</p><p>Line: @relaxmassage</p><p>เปิดทุกวัน 09:00–18:00 น.</p></div></div><div class="footer-bottom section-wrap"><span>© {{ date('Y') }} Relax Massage &amp; Spa</span><span>THOUGHTFULLY MADE FOR YOUR WELLBEING</span></div></footer>
<script src="{{ asset('js/relax.js') }}"></script>
</body></html>
