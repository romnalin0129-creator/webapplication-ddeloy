@extends('layouts.storefront')

@section('title', 'ติดต่อเรา | Relax Massage & Spa')

@section('description', 'ติดต่อ Relax Massage & Spa สอบถามบริการและเวลาทำการ')

@section('content')
    <section class="page-hero page-hero-contact">
        <span class="eyebrow light">WE ARE HERE FOR YOU</span>
        <h1>ติดต่อเรา</h1>
        <p>ยินดีช่วยแนะนำบริการและตอบทุกข้อสงสัยของคุณ</p>
    </section>

    <section class="contact-page section-wrap">
        <div class="contact-intro"><span class="eyebrow">SAY HELLO</span><h2>เริ่มต้นช่วงเวลาพักผ่อน<br>ได้ที่นี่</h2><p>ติดต่อเราเพื่อสอบถามบริการ เวลาว่าง หรือข้อมูลก่อนเข้ารับบริการ ทีมงานพร้อมช่วยดูแลคุณ</p></div>
        <div class="contact-card-grid">
            <article class="contact-card"><span class="contact-icon">☎</span><span class="eyebrow">CALL US</span><h3>โทรศัพท์</h3><a href="tel:025550100">02-555-0100</a><p>เปิดทุกวัน 09:00–18:00 น.</p></article>
            <article class="contact-card"><span class="contact-icon">LINE</span><span class="eyebrow">CHAT WITH US</span><h3>LINE Official</h3><a href="https://line.me/R/ti/p/@relaxmassage" target="_blank" rel="noopener noreferrer">@relaxmassage</a><p>สอบถามรายละเอียดและช่วงเวลาว่าง</p></article>
            <article class="contact-card"><span class="contact-icon">R</span><span class="eyebrow">VISIT RELAX</span><h3>เวลาทำการ</h3><strong>ทุกวัน 09:00–18:00 น.</strong><p>กรุณาจองคิวล่วงหน้าเพื่อความสะดวก</p></article>
        </div>
    </section>

    <section class="contact-cta">
        <span class="eyebrow light">YOUR TIME, YOUR WAY</span>
        <h2>พร้อมให้เราดูแลคุณหรือยัง</h2>
        <p>เลือกบริการและช่วงเวลาที่คุณสะดวกได้เลย</p>
        <a class="button button-gold" href="{{ route('storefront.services') }}">ดูบริการทั้งหมด <span>↗</span></a>
    </section>
@endsection
