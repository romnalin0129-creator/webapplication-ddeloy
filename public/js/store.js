(() => {
    if (document.querySelector('[data-order-confirmed]')) {
        localStorage.removeItem('kawaii-corner-cart');
        return;
    }

    const cards = [...document.querySelectorAll('[data-product-card]')];
    if (!cards.length) return;

    const catalog = new Map(cards.map((card) => [card.dataset.id, JSON.parse(card.dataset.product)]));
    const storageKey = 'kawaii-corner-cart';
    const dialog = document.getElementById('cart-dialog');
    const cartItems = document.querySelector('[data-cart-items]');
    const cartEmpty = document.querySelector('[data-cart-empty]');
    const cartTotal = document.querySelector('[data-cart-total]');
    const cartCount = document.querySelector('[data-cart-count]');
    const inputContainer = document.querySelector('[data-cart-inputs]');
    const search = document.getElementById('product-search');
    const sort = document.getElementById('product-sort');
    const emptyProducts = document.getElementById('empty-products');
    let activeFilter = 'all';
    let cart = {};

    try {
        const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
        if (saved && typeof saved === 'object' && !Array.isArray(saved)) {
            cart = Object.fromEntries(Object.entries(saved).filter(([id, quantity]) =>
                catalog.has(id) && Number.isInteger(quantity) && quantity > 0 && quantity <= 10
            ));
        }
    } catch (error) {
        console.error('Could not read the saved shopping cart.', error);
        localStorage.removeItem(storageKey);
    }

    const formatPrice = (price) => `฿${Number(price).toLocaleString('th-TH')}`;

    function persistCart() {
        localStorage.setItem(storageKey, JSON.stringify(cart));
    }

    function renderCart() {
        cartItems.replaceChildren();
        inputContainer?.replaceChildren();
        let count = 0;
        let total = 0;

        Object.entries(cart).forEach(([id, quantity]) => {
            const product = catalog.get(id);
            if (!product) return;
            count += quantity;
            total += product.price * quantity;

            const row = document.createElement('div');
            row.className = 'cart-row';
            const art = document.createElement('span');
            art.className = `cart-row-art art-${product.color}`;
            art.textContent = product.icon;
            const detail = document.createElement('div');
            const name = document.createElement('p');
            name.className = 'cart-row-name';
            name.textContent = product.name;
            const price = document.createElement('span');
            price.className = 'cart-row-price';
            price.textContent = formatPrice(product.price * quantity);
            detail.append(name, price);

            const controls = document.createElement('div');
            controls.className = 'cart-row-controls';
            const decrease = document.createElement('button');
            decrease.type = 'button';
            decrease.setAttribute('aria-label', `ลดจำนวน ${product.name}`);
            decrease.dataset.change = '-1';
            decrease.dataset.id = id;
            decrease.textContent = '−';
            const amount = document.createElement('span');
            amount.textContent = quantity;
            const increase = document.createElement('button');
            increase.type = 'button';
            increase.setAttribute('aria-label', `เพิ่มจำนวน ${product.name}`);
            increase.dataset.change = '1';
            increase.dataset.id = id;
            increase.textContent = '+';
            controls.append(decrease, amount, increase);
            row.append(art, detail, controls);
            cartItems.append(row);

            if (inputContainer) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `items[${id}]`;
                input.value = quantity;
                inputContainer.append(input);
            }
        });

        cartCount.textContent = count;
        cartTotal.textContent = formatPrice(total);
        cartEmpty.hidden = count > 0;
        cartItems.hidden = count === 0;
        document.querySelector('.cart-summary').hidden = count === 0;
        const checkout = document.querySelector('.checkout-button');
        if (checkout) checkout.hidden = count === 0;
    }

    function addToCart(id) {
        cart[id] = Math.min((cart[id] || 0) + 1, 10);
        persistCart();
        renderCart();
        const trigger = document.querySelector('[data-cart-open]');
        trigger.classList.add('cart-bump');
        window.setTimeout(() => trigger.classList.remove('cart-bump'), 350);
    }

    function applyFilters() {
        const query = search.value.trim().toLocaleLowerCase('th');
        const visible = cards.filter((card) => {
            const matchesCategory = activeFilter === 'all'
                || card.dataset.category === activeFilter
                || (activeFilter === 'bestseller' && card.dataset.bestseller === '1');
            const matchesSearch = card.dataset.name.toLocaleLowerCase('th').includes(query);
            card.hidden = !(matchesCategory && matchesSearch);
            return !card.hidden;
        });
        emptyProducts.hidden = visible.length > 0;
    }

    document.querySelectorAll('[data-add]').forEach((button) => {
        button.addEventListener('click', () => addToCart(button.dataset.add));
    });

    document.querySelectorAll('[data-cart-open]').forEach((button) => {
        button.addEventListener('click', () => dialog.showModal());
    });
    document.querySelectorAll('[data-cart-close]').forEach((button) => {
        button.addEventListener('click', () => dialog.close());
    });
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) dialog.close();
    });

    cartItems.addEventListener('click', (event) => {
        const button = event.target.closest('[data-change]');
        if (!button) return;
        const next = (cart[button.dataset.id] || 0) + Number(button.dataset.change);
        if (next <= 0) delete cart[button.dataset.id];
        else cart[button.dataset.id] = Math.min(next, 10);
        persistCart();
        renderCart();
    });

    document.querySelectorAll('[data-filter]').forEach((button) => {
        button.addEventListener('click', () => {
            activeFilter = button.dataset.filter;
            document.querySelectorAll('[data-filter]').forEach((tab) => tab.classList.toggle('is-active', tab === button));
            applyFilters();
        });
    });
    document.querySelectorAll('[data-category-link]').forEach((link) => {
        link.addEventListener('click', () => {
            activeFilter = link.dataset.categoryLink;
            document.querySelectorAll('[data-filter]').forEach((tab) => tab.classList.toggle('is-active', tab.dataset.filter === activeFilter));
            applyFilters();
        });
    });
    document.querySelector('[data-filter-link]')?.addEventListener('click', () => {
        activeFilter = 'bestseller';
        document.querySelectorAll('[data-filter]').forEach((tab) => tab.classList.toggle('is-active', tab.dataset.filter === activeFilter));
        applyFilters();
        document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
    });
    search.addEventListener('input', applyFilters);
    search.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            search.value = '';
            applyFilters();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            event.preventDefault();
            search.focus();
        }
    });
    sort.addEventListener('change', () => {
        const sorted = [...cards].sort((a, b) => {
            if (sort.value === 'price-asc') return Number(a.dataset.price) - Number(b.dataset.price);
            if (sort.value === 'price-desc') return Number(b.dataset.price) - Number(a.dataset.price);
            if (sort.value === 'name') return a.dataset.name.localeCompare(b.dataset.name, 'th');
            return Number(b.dataset.bestseller) - Number(a.dataset.bestseller);
        });
        sorted.forEach((card) => document.getElementById('product-grid').append(card));
        applyFilters();
    });

    const menuToggle = document.querySelector('.shop-menu-toggle');
    menuToggle?.addEventListener('click', () => {
        const nav = document.querySelector('.shop-nav');
        const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!expanded));
        nav.classList.toggle('is-open', !expanded);
    });

    renderCart();
})();
