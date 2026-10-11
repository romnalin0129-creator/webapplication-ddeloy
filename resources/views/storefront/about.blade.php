@extends('layouts.storefront')

@section('title', 'เกี่ยวกับเรา | Relax Massage & Spa')

@section('description', 'รู้จัก Relax Massage & Spa พื้นที่สงบสำหรับการพักผ่อนและดูแลตัวเอง')

@section('content')
    <section class="page-hero page-hero-about">
        <span class="eyebrow light">WELCOME TO RELAX</span>
        <h1>พื้นที่พักใจในวันที่โลกหมุนเร็ว</h1>
        <p>กลับมาใส่ใจตัวเอง ในบรรยากาศอบอุ่นและเป็นส่วนตัว</p>
    </section>

    <section class="about-story section-wrap">
        <div class="about-image-wrap"><img src="https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=1200&q=85" alt="บรรยากาศการดูแลและผ่อนคลายที่ Relax Massage & Spa" loading="lazy"></div>
        <div class="about-copy">
            <span class="eyebrow">THE RELAX RITUAL</span>
            <h2>การพักผ่อนที่ตั้งใจ<br>ออกแบบเพื่อคุณ</h2>
            <p>เราเชื่อว่าการดูแลตัวเองไม่ใช่เรื่องฟุ่มเฟือย Relax Massage &amp; Spa จึงสร้างพื้นที่สงบ อบอุ่น และเป็นส่วนตัว ให้ทุกการมาเยือนเป็นช่วงเวลาที่คุณได้หยุดพักอย่างแท้จริง</p>
            <p>ตั้งแต่การต้อนรับ กลิ่นหอมอ่อน ๆ ไปจนถึงบริการนวดที่เลือกได้หลากหลาย เราใส่ใจทุกรายละเอียดเพื่อให้คุณกลับไปพร้อมความรู้สึกที่เบาสบายกว่าเดิม</p>
            <a class="button button-gold" href="{{ route('storefront.services') }}">เลือกบริการของเรา <span>↗</span></a>
        </div>
    </section>

    <section class="about-values">
        <div class="section-wrap">
            <div class="section-title"><div><span class="eyebrow">OUR PROMISE</span><h2>ใส่ใจในทุกสัมผัส</h2><p>ความสบายใจของคุณคือหัวใจของบริการ</p></div></div>
            <div class="value-grid">
                <article><span>01</span><h3>ดูแลด้วยความใส่ใจ</h3><p>ให้บริการอย่างสุภาพ รับฟังความต้องการเฉพาะของลูกค้าแต่ละท่าน</p></article>
                <article><span>02</span><h3>พื้นที่สงบเป็นส่วนตัว</h3><p>บรรยากาศอบอุ่น เหมาะกับการพักกายและปล่อยใจให้ผ่อนคลาย</p></article>
                <article><span>03</span><h3>จองคิวสะดวก</h3><p>ดูบริการและส่งคำขอจองออนไลน์ พร้อมติดตามสถานะได้ทุกเวลา</p></article>
            </div>
        </div>
    </section>

    <section class="faq-section about-faq">
        <div class="section-wrap faq-layout">
            <div><span class="eyebrow">GOOD TO KNOW</span><h2>คำถามที่พบบ่อย</h2><p>ข้อมูลสำหรับเตรียมตัวก่อนมาใช้บริการ</p><a class="subtle-link" href="{{ route('storefront.contact') }}">สอบถามเพิ่มเติม <span>↗</span></a></div>
            <div class="faq-list">
                <details><summary>ต้องเข้าสู่ระบบก่อนจองหรือไม่?</summary><p>สามารถดูบริการได้โดยไม่ต้องเข้าสู่ระบบ แต่ต้องเข้าสู่ระบบก่อนส่งคำขอจอง</p></details>
                <details><summary>จะทราบได้อย่างไรว่าจองสำเร็จ?</summary><p>ระบบจะแสดงเลขที่การจอง และรายการจะแสดงในประวัติการจองพร้อมสถานะรอยืนยัน</p></details>
                <details><summary>ถ้าต้องการเปลี่ยนวันหรือเวลา ทำอย่างไร?</summary><p>แก้ไขหรือยกเลิกรายการได้จากประวัติการจอง ตราบใดที่รายการยังรอยืนยันจากทางร้าน</p></details>
            </div>
        </div>
    </section>
@endsection
