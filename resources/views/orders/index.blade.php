@extends('layouts.storefront')

@section('title', 'คำสั่งซื้อของฉัน | Kawaii Corner')

@section('content')
    <section class="orders-page">
        <a class="order-back" href="{{ route('home') }}">← กลับไปเลือกสินค้า</a>
        <span class="section-kicker">YOUR ORDER HISTORY</span>
        <h1>คำสั่งซื้อของฉัน <span>♡</span></h1>
        <p class="orders-lead">รวมทุกไอเทมน่ารักที่คุณเลือกไว้ในที่เดียว</p>
        @forelse($orders as $number => $items)
            @php($first = $items->first())
            <article class="order-card">
                <div class="order-card-head"><div><span>เลขที่คำสั่งซื้อ</span><strong>{{ $number }}</strong></div><div class="order-badges"><span class="order-status status-{{ $first->status }}">{{ ['pending' => 'รอดำเนินการ', 'processing' => 'กำลังดำเนินการ', 'completed' => 'สำเร็จแล้ว', 'cancelled' => 'ยกเลิก'][ $first->status ] ?? $first->status }}</span><span class="payment-status payment-{{ $first->payment_status }}">{{ ['unpaid' => 'ยังไม่ชำระ', 'reported' => 'รอตรวจสอบยอด', 'paid' => 'ชำระแล้ว'][ $first->payment_status ] ?? $first->payment_status }}</span></div></div>
                <div class="order-preview">@foreach($items as $item)<span>{{ $item->product_name }} × {{ $item->quantity }}</span>@endforeach</div>
                <div class="order-card-foot"><span>{{ $first->created_at->format('d/m/Y H:i') }} น.</span><strong>฿{{ number_format($items->sum('total'), 2) }}</strong><a href="{{ route('orders.show', $number) }}">ดูรายละเอียด ↗</a></div>
            </article>
        @empty
            <div class="orders-empty"><span>💌</span><h2>ยังไม่มีคำสั่งซื้อเลย</h2><p>เลือกของน่ารักชิ้นโปรด แล้วเริ่มต้นช้อปกันได้เลย</p><a class="shop-button shop-button-primary" href="{{ route('home') }}#products">ไปเลือกสินค้า <span>↗</span></a></div>
        @endforelse
    </section>
@endsection
