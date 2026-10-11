@extends('layouts.storefront')

@section('title', 'บริการนวด | Relax Massage & Spa')

@section('description', 'เลือกบริการนวดที่เหมาะกับคุณ พร้อมรายละเอียด ราคา และระยะเวลา')

@section('content')
    <section class="page-hero page-hero-services">
        <span class="eyebrow light">OUR TREATMENTS</span>
        <h1>บริการของเรา</h1>
        <p>เลือกการดูแลที่เหมาะกับช่วงเวลาพักผ่อนของคุณ</p>
    </section>

    <section class="services-section services-page-section">
        <div class="section-wrap">
            <div class="section-title">
                <div><span class="eyebrow">A RITUAL FOR EVERY YOU</span><h2>ศาสตร์แห่งการผ่อนคลาย</h2><p>บริการทุกรายการแสดงจากข้อมูลล่าสุดของทางร้าน</p></div>
                <span class="service-count-label">{{ $services->count() }} บริการ</span>
            </div>
            <div class="service-grid">
                @forelse($services as $service)
                    <article class="service-card">
                        @if($service->image_path)
                            <img class="service-card-image" src="{{ str_starts_with($service->image_path, 'http') ? $service->image_path : \Illuminate\Support\Facades\Storage::url($service->image_path) }}" alt="บริการ{{ $service->name }}" loading="lazy">
                        @else
                            <div class="service-card-placeholder" aria-hidden="true">RELAX</div>
                        @endif
                        <div class="service-card-overlay"></div>
                        <span class="service-card-duration">{{ $service->duration_minutes }} นาที</span>
                        <div class="service-card-content">
                            <span class="service-card-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} &nbsp; TREATMENT</span>
                            <h3>{{ $service->name }}</h3>
                            <p>{{ $service->description }}</p>
                            <div class="service-card-footer">
                                <strong>฿{{ number_format($service->price) }} <small>/ ท่าน</small></strong>
                                <a href="{{ route('booking.create', ['service' => $service->id]) }}">จองบริการ <span>↗</span></a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-services">กำลังเตรียมบริการพิเศษสำหรับคุณ กรุณากลับมาเยี่ยมชมอีกครั้ง</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="page-callout section-wrap">
        <div><span class="eyebrow">READY TO UNWIND?</span><h2>พบบริการที่ถูกใจแล้วหรือยัง</h2><p>เลือกวันและเวลาที่สะดวก แล้วให้เราดูแลช่วงเวลาพักผ่อนของคุณ</p></div>
        <a class="button button-gold" href="{{ route('booking.create') }}">จองคิวเลย <span>↗</span></a>
    </section>
@endsection
