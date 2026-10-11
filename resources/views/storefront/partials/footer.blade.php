<footer class="shop-footer">
    <div class="shop-footer-main">
        <div class="footer-about"><a class="shop-brand" href="{{ route('home') }}#home"><span class="brand-mark">♡</span><span>Kawaii Corner<small>little things, big smiles</small></span></a><p>ของน่ารักชิ้นเล็ก ๆ ที่เติมความสุขให้ทุกวัน<br>เลือกช้อปได้ตามสไตล์ที่เป็นคุณ 💗</p></div>
        <div><strong>เลือกช้อป</strong><a href="{{ route('home') }}#products">สินค้าทั้งหมด</a><a href="{{ route('home') }}#categories">หมวดหมู่สินค้า</a></div>
        <div><strong>บัญชีของฉัน</strong>@auth<a href="{{ route('orders.index') }}">คำสั่งซื้อของฉัน</a>@else<a href="{{ route('login') }}">เข้าสู่ระบบ</a><a href="{{ route('register') }}">สมัครสมาชิก</a>@endauth<a href="{{ route('password.request') }}">ลืมรหัสผ่าน?</a></div>
        <div class="footer-note"><strong>ทำด้วยความน่ารัก</strong><p>สินค้าตัวอย่างเพื่อการศึกษา<br>ก่อนจำหน่ายจริง โปรดตรวจสอบสิทธิ์ในไฟล์และผลงานทุกชิ้น</p></div>
    </div>
    <div class="shop-footer-bottom"><span>© {{ date('Y') }} Kawaii Corner</span><span>MADE WITH ♡ IN THAILAND</span></div>
</footer>
