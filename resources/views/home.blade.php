@extends('layouts.storefront')

@section('title', 'Kawaii Corner | เมโลดี้ ธีม และสติกเกอร์ LINE')
@section('description', 'ช้อปเมโลดี้ ธีม และสติกเกอร์ LINE สุดน่ารัก เติมความสดใสให้ทุกแชต')

@section('content')
    <section class="shop-hero" id="home">
        <div class="hero-sparkle sparkle-one">✦</div>
        <div class="hero-sparkle sparkle-two">✧</div>
        <div class="hero-copy">
            <span class="hero-kicker"><i></i> A LITTLE WORLD OF CUTENESS ♡ </span>
            <h1>เติมความน่ารัก<br> ให้ทุกแชต <em>เป็นวันพิเศษ ✨</em></h1>
            <p>ค้นพบธีม LINE เมโลดี้สุดละมุน และสติกเกอร์สุดคิวต์<br>ที่จะทำให้ทุกข้อความของคุณเต็มไปด้วยความสุข ♡</p>
            <a class="shop-button shop-button-primary" href="#products">เลือกช้อปเลย <span>↗</span></a>
            <div class="hero-proof"><span class="proof-avatars"><i>🐻</i><i>🐈</i><i>🐰</i></span><span>ของน่ารักที่ทำให้ทุกวันใจฟู</span></div>
        </div>
        <div class="hero-art" aria-label="ภาพตัวอย่างสินค้าน่ารัก">
            <div class="hero-blob"></div>
            <div class="art-sun">🥐</div>
            <div class="art-cloud">🥨</div>
            <div class="art-card art-card-main"><span> เชฟจิ๋วชวนฝัน</span><b>👩🏻‍🍳 </b><small>แต่งหน้าจอให้หวานละมุน </small></div>
            <div class="art-card art-card-note"><b>🍔</b><span> เบอร์เกอร์คำโต </span></div>
            <div class="art-sticker sticker-bunny">🍟</div>
            <div class="art-sticker sticker-heart">🥙 </div>
            <div class="art-star star-a">✦</div><div class="art-star star-b">✧</div>
        </div>
        <a class="hero-scroll" href="#categories"><span></span> เลื่อนลงเพื่อค้นพบ</a>
    </section>

    <section class="category-section" id="categories">
        <div class="section-heading">
            <span class="section-kicker">PICK YOUR HAPPY</span>
            <h2>วันนี้อยากเติมความน่ารักแบบไหน?</h2>
            <p>เลือกไอเทมชิ้นโปรด แล้วมาเปลี่ยนวันธรรมดาให้พิเศษกว่าเดิม ด้วยเมโลดี้ ธีม และสติกเกอร์สุดคิวต์ ♡</p>
        </div>
        <div class="category-grid">
            @foreach($categories as $key => $category)
                <a class="category-card category-{{ $key }}" href="#products" data-category-link="{{ $key }}">
                    <span class="category-icon">{{ $category['icon'] }}</span>
                    <span class="category-copy"><strong>{{ $category['name'] }}</strong><small>{{ $category['description'] }}</small></span>
                    <span class="category-arrow">↗</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="bestseller-section">
        <div class="bestseller-inner">
            <div><span class="section-kicker">LOVED BY EVERYONE</span><h2>คอลเลกชันสุดคิ้วต์ที่ต้องมี <span>♡</span></h2><p>รวมไอเทมสุดโปรดที่คัดมาเพื่อคนรักความน่ารักโดยเฉพาะ เลือกชิ้นที่ใช่ แล้วเติมความสุขให้ทุกวันของคุณ ✨</p></div>
            <button class="text-link" type="button" data-filter-link="bestseller">ดูสินค้าขายดี <span>↗</span></button>
        </div>
        <div class="bestseller-chips" aria-label="สินค้าแนะนำ">
            @foreach($products->where('bestseller', true)->take(4) as $product)
                <span>{{ $product['icon'] }} {{ $product['name'] }}</span>
            @endforeach
        </div>
    </section>

    <section class="catalog-section" id="products">
        <div class="catalog-heading">
            <div><span class="section-kicker">THE KAWAII COLLECTION</span><h2>สินค้าแสนน่ารัก <span class="catalog-total">{{ count($products) }} ชิ้น</span></h2></div>
            <label class="sort-control">เรียงตาม
                <select id="product-sort" aria-label="เรียงสินค้าตามราคา">
                    <option value="recommended">แนะนำ</option>
                    <option value="price-asc">ราคาน้อยไปมาก</option>
                    <option value="price-desc">ราคามากไปน้อย</option>
                    <option value="name">ชื่อสินค้า</option>
                </select>
            </label>
        </div>
        <div class="catalog-tools">
            <div class="filter-tabs" role="group" aria-label="กรองตามหมวดหมู่">
                <button class="filter-tab is-active" type="button" data-filter="all">ทั้งหมด <span>{{ count($products) }}</span></button>
                @foreach($categories as $key => $category)
                    <button class="filter-tab" type="button" data-filter="{{ $key }}">{{ $category['icon'] }} {{ $category['name'] }}</button>
                @endforeach
                <button class="filter-tab" type="button" data-filter="bestseller">⭐ ขายดี</button>
            </div>
            <label class="product-search"><span aria-hidden="true">⌕</span><input id="product-search" type="search" placeholder="ค้นหาสินค้าน่ารัก..." autocomplete="off"><kbd>/</kbd></label>
        </div>
        <div class="product-grid" id="product-grid">
            @foreach($products as $product)
                <article class="product-card" data-product-card data-id="{{ $product['id'] }}" data-name="{{ $product['name'] }}" data-category="{{ $product['category'] }}" data-price="{{ $product['price'] }}" data-bestseller="{{ $product['bestseller'] ? '1' : '0' }}" data-product='@json($product)'>
                    <div class="product-art art-{{ $product['color'] }}">
                        @if($product['bestseller'])<span class="product-ribbon">ฮิตติดใจ ✨</span>@endif
                        <span class="product-art-sparkle sparkle-left">✦</span><span class="product-art-sparkle sparkle-right">✧</span>
                        <span class="product-emoji">{{ $product['icon'] }}</span>
                        <span class="product-art-caption">{{ $product['tag'] }}</span>
                        <button class="quick-add" type="button" data-add="{{ $product['id'] }}" aria-label="เพิ่ม{{ $product['name'] }}ลงตะกร้า">+</button>
                    </div>
                    <div class="product-info">
                        <span class="product-category">{{ $categories[$product['category']]['name'] }}</span>
                        <h3>{{ $product['name'] }}</h3>
                        <p>{{ $product['description'] }}</p>
                        <div class="product-card-bottom"><strong>฿{{ number_format($product['price']) }}</strong><button type="button" class="add-button" data-add="{{ $product['id'] }}">เพิ่มใส่ตะกร้า <span>+</span></button></div>
                    </div>
                </article>
            @endforeach
        </div>
        <p class="empty-products" id="empty-products" hidden>ไม่เจอสินค้าที่ค้นหา ลองเปลี่ยนคำหรือเลือกหมวดหมู่อื่นดูนะ ♡</p>
    </section>

    <section class="shop-promise" id="about">
        <span class="promise-bubble">💌</span>
        <div><span class="section-kicker">A LITTLE NOTE FROM US</span><h2>เติมความคิ้วต์<br>ให้ทุกวันพิเศษ ♡</h2><p>ไม่ว่าจะเป็นเสียงเพลง ธีมสุดละมุน<br>หรือสติกเกอร์ตัวโปรด เรารวมความน่ารักไว้ให้คุณแล้วที่ Kawaii Corner</p></div>
        <a href="#products" class="shop-button shop-button-light">ค้นพบไอเทมชิ้นโปรด <span>↗</span></a>
        <span class="promise-doodle">♡</span>
    </section>

    <dialog class="cart-dialog" id="cart-dialog" aria-labelledby="cart-title">
        <div class="cart-panel">
            <div class="cart-heading"><div><span class="section-kicker">YOUR LITTLE TREATS</span><h2 id="cart-title">ตะกร้าของคุณ <span>♡</span></h2></div><button class="cart-close" type="button" data-cart-close aria-label="ปิดตะกร้า">×</button></div>
            <div class="cart-items" data-cart-items></div>
            <div class="cart-empty" data-cart-empty><span>🛍️</span><strong>ยังไม่มีไอเทมชิ้นโปรดเลย!</strong><p>มาเลือกเมโลดี้ ธีม LINE และสติกเกอร์สุดคิวต์ เติมความน่ารักลงในตะกร้าของคุณกันเถอะ ♡</p><button type="button" class="shop-button shop-button-soft" data-cart-close>ไปช็อปความน่ารักกัน</button></div>
            <div class="cart-summary"><span>รวมทั้งหมด</span><strong data-cart-total>฿0</strong></div>
            @auth
                <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                    @csrf
                    <div data-cart-inputs></div>
                    <button class="shop-button shop-button-primary checkout-button" type="submit" data-checkout>เลือกสินค้าก่อนสั่งซื้อ <span>♡</span></button>
                </form>
            @else
                <a class="shop-button shop-button-primary checkout-button" href="{{ route('login') }}">เข้าสู่ระบบเพื่อสั่งซื้อ <span>↗</span></a>
            @endauth
            <p class="cart-footnote">ขอบคุณที่แวะมาเติมความสุขเล็กๆ ไปกับเรา</p>
        </div>
    </dialog>
@endsection
