@extends('layouts.storefront')

@section('title', 'จัดการคำสั่งซื้อ | Kawaii Corner')

@section('content')
    <section class="orders-page admin-orders-page">
        <a class="order-back" href="{{ route('home') }}">← กลับไปหน้าร้าน</a>
        <span class="section-kicker">SHOP ADMINISTRATION</span>
        <h1>จัดการคำสั่งซื้อ</h1>
        <p class="orders-lead">ตรวจสอบรายการ ยืนยันยอดชำระ และอัปเดตสถานะคำสั่งซื้อ</p>
        @if(session('error'))<p class="payment-alert payment-alert-error" role="alert">{{ session('error') }}</p>@endif
        @forelse($orders as $number => $items)
            @php($first = $items->first())
            <article class="order-card">
                <div class="order-card-head"><div><span>เลขที่คำสั่งซื้อ · {{ $first->customer_name }}</span><strong>{{ $number }}</strong></div><div class="order-badges"><span class="order-status status-{{ $first->status }}">{{ ['pending' => 'รอดำเนินการ', 'processing' => 'กำลังดำเนินการ', 'completed' => 'สำเร็จแล้ว', 'cancelled' => 'ยกเลิก'][ $first->status ] ?? $first->status }}</span><span class="payment-status payment-{{ $first->payment_status }}">{{ ['unpaid' => 'ยังไม่ชำระ', 'reported' => 'รอตรวจสอบยอด', 'paid' => 'ชำระแล้ว'][ $first->payment_status ] ?? $first->payment_status }}</span></div></div>
                <div class="order-preview">@foreach($items as $item)<span>{{ $item->product_name }} × {{ $item->quantity }}</span>@endforeach</div>
                <div class="order-card-foot"><span>{{ $first->created_at->format('d/m/Y H:i') }} น.</span><strong>฿{{ number_format($items->sum('total'), 2) }}</strong></div>
                @if($first->payment_status === 'reported')
                    <form class="payment-confirm-form" method="POST" action="{{ route('admin.orders.payment', $number) }}">
                        @csrf @method('PATCH')
                        <span>ลูกค้าแจ้งชำระแล้ว · ตรวจยอดเงินจริงก่อนยืนยัน</span>
                        <button class="shop-button shop-button-primary" type="submit" name="payment_status" value="paid">ยืนยันว่าชำระแล้ว ✓</button>
                        <button class="payment-reject-button" type="submit" name="payment_status" value="unpaid">ยอดไม่ตรง · ส่งกลับ</button>
                    </form>
                @endif
                <form class="order-status-form" method="POST" action="{{ route('admin.orders.update', $number) }}">
                    @csrf @method('PATCH')
                    <label for="status-{{ $loop->index }}">สถานะ</label>
                    <select id="status-{{ $loop->index }}" name="status">
                        <option value="pending" @selected($first->status === 'pending')>รอดำเนินการ</option>
                        <option value="processing" @selected($first->status === 'processing') @disabled($first->payment_status !== 'paid')>กำลังดำเนินการ</option>
                        <option value="completed" @selected($first->status === 'completed') @disabled($first->payment_status !== 'paid')>สำเร็จแล้ว</option>
                        <option value="cancelled" @selected($first->status === 'cancelled')>ยกเลิก</option>
                    </select>
                    <button class="shop-button shop-button-primary" type="submit">บันทึกสถานะ</button>
                </form>
            </article>
        @empty
            <div class="orders-empty"><span>📦</span><h2>ยังไม่มีคำสั่งซื้อ</h2><p>คำสั่งซื้อใหม่จะแสดงที่นี่</p></div>
        @endforelse
    </section>
@endsection
