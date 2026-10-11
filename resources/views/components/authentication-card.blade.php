<div class="auth-page">
    <section class="auth-visual" aria-label="Kawaii Corner ร้านเมโลดี้ ธีม และสติกเกอร์ LINE">
        <div class="auth-visual-copy">
            <span>A LITTLE SHOP FULL OF JOY</span>
            <h2>เติมความน่ารัก<br>ให้ทุก <em>วันของคุณ</em></h2>
            <p>เมโลดี้ ธีม และสติกเกอร์แสนน่ารัก<br>คัดมาให้แชตของคุณสดใสกว่าที่เคย ♡</p>
            <div class="auth-visual-hours"><span></span> LITTLE THINGS, BIG SMILES</div>
        </div>
        <span class="auth-visual-caption">KAWAII CORNER · MADE WITH ♡ IN THAILAND</span>
    </section>
    <section class="auth-panel">
        <div class="auth-brand">
            {{ $logo }}
        </div>
        <div class="auth-card">
            {{ $slot }}
        </div>
        <a class="auth-store-link" href="{{ route('home') }}">เลือกช้อปสินค้าน่ารัก <span>↗</span></a>
        <p class="auth-caption">KAWAII CORNER · LITTLE THINGS, BIG SMILES</p>
    </section>
</div>
