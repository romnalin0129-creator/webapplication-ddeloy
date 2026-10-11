<header class="shop-header">
    <a class="shop-brand" href="{{ route('home') }}#home" aria-label="Kawaii Corner หน้าแรก">
        <span class="brand-mark">♡</span>
        <span>Kawaii Corner<small>little things, big smiles</small></span>
    </a>
    <button class="shop-menu-toggle" type="button" aria-label="เปิดเมนู" aria-expanded="false">☰</button>
    <nav class="shop-nav" aria-label="เมนูหลัก">
        <a href="{{ route('home') }}#home">หน้าแรก</a>
        <a href="{{ route('home') }}#categories">หมวดหมู่</a>
        <a href="{{ route('home') }}#products">สินค้าทั้งหมด</a>
        @auth
            @if(auth()->user()->is_admin)<a href="{{ route('admin.products.index') }}">จัดการสินค้า</a>@endif
            @if(auth()->user()->is_admin)<a href="{{ route('admin.orders.index') }}">จัดการคำสั่งซื้อ</a>@endif
            <a href="{{ route('orders.index') }}">คำสั่งซื้อของฉัน</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">ออกจากระบบ</button></form>
        @else
            <a href="{{ route('login') }}">เข้าสู่ระบบ</a>
            <a href="{{ route('register') }}">สมัครสมาชิก</a>
        @endauth
    </nav>
    <div class="shop-header-actions">
        @auth
            @if(auth()->user()->is_admin)<a class="admin-shortcut" href="{{ route('admin.orders.index') }}">จัดการคำสั่งซื้อ</a>@endif
        @endauth
        <button class="cart-trigger" type="button" data-cart-open aria-label="เปิดตะกร้าสินค้า">🛍️ <span>ตะกร้า</span><b data-cart-count>0</b></button>
    </div>
</header>
