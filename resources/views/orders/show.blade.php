@extends('layouts.storefront')

@section('title', 'รายละเอียดคำสั่งซื้อ | Kawaii Corner')

@section('content')
    <section class="orders-page" data-order-confirmed>
        <a class="order-back" href="{{ route('orders.index') }}">← กลับไปคำสั่งซื้อของฉัน</a>
        @if(session('error'))<p class="payment-alert payment-alert-error" role="alert">{{ session('error') }}</p>@endif
        @php($paymentStatus = $items->first()->payment_status)
        <article class="order-card order-detail-card">
            <div class="order-card-head"><div><span>เลขที่คำสั่งซื้อ</span><strong>{{ $orderNumber }}</strong></div><span class="order-status status-{{ $items->first()->status }}">{{ ['pending' => 'รอดำเนินการ', 'processing' => 'กำลังดำเนินการ', 'completed' => 'สำเร็จแล้ว', 'cancelled' => 'ยกเลิก'][ $items->first()->status ] ?? $items->first()->status }}</span></div>
            @foreach($items as $item)
                <div class="order-line"><div><strong>{{ $item->product_name }}</strong><span>{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</span></div><b>฿{{ number_format($item->total, 2) }}</b></div>
            @endforeach
            <div class="order-total"><span>ยอดรวมทั้งหมด</span><strong>฿{{ number_format($items->sum('total'), 2) }}</strong></div>
        </article>
        <div class="order-thank-you">
            <div class="order-success-mark">♡</div>
            <span class="section-kicker">THANK YOU FOR YOUR ORDER</span>
            <h1>ขอบคุณที่ช็อปกับเรา!</h1>
            @if(session('success'))<p class="orders-lead">{{ session('success') }}</p>@endif
        </div>
        <section class="payment-box" aria-labelledby="payment-title">
            <div class="payment-heading"><span>💳</span><div><h2 id="payment-title">การชำระเงิน</h2><p>ชำระผ่านช่องทางที่ร้านแจ้ง แล้วกดแจ้งให้ร้านตรวจสอบ</p></div></div>
            @if($paymentStatus === 'paid')
                <div class="payment-alert payment-alert-success">ยืนยันชำระ{{ $items->first()->paid_at ? ' · '.$items->first()->paid_at->format('d/m/Y H:i').' น.' : '' }} ✓</div>
            @elseif($paymentStatus === 'reported')
                <div class="payment-alert payment-alert-pending">แจ้งชำระเงินแล้ว กรุณารอแอดมินตรวจสอบยอด</div>
            @else
                <p class="payment-amount">ยอดที่ต้องชำระ <strong>฿{{ number_format($items->sum('total'), 2) }}</strong></p>
                <form method="POST" action="{{ route('orders.report-payment', $orderNumber) }}">
                    @csrf
                    <button class="shop-button shop-button-primary payment-report-button" type="submit">ยืนยันชำระ <span>✓</span></button>
                </form>
                <p class="payment-note">กรุณาตรวจสอบยอดชำระและรายละเอียดคำสั่งซื้อก่อนดำเนินการ</p>
            @endif
        </section>
        <a class="shop-button shop-button-primary" href="{{ route('home') }}#products">ช็อปต่อกันเลย <span>♡</span></a>
    </section>
@endsection
