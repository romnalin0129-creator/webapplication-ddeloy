<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ประวัติการจอง | Relax Massage &amp; Spa</title>
    <link rel="stylesheet" href="{{ asset('css/relax.css') }}">
</head>
<body>
<header class="inner-header">
    <a class="logo" href="{{ route('home') }}">
        <span class="logo-symbol">R</span>
        <span>RELAX<small>MASSAGE &amp; SPA</small></span>
    </a>
    <nav class="inner-nav">
        <a href="{{ route('home') }}">หน้าหลัก</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-login" type="submit">ออกจากระบบ</button></form>
    </nav>
</header>
<main class="section-wrap history-page">
    <div class="history-heading">
        <span class="eyebrow">YOUR VISITS</span>
        <h1>ประวัติการจอง</h1>
        <p>จัดการนัดหมายและติดตามสถานะของคุณ</p>
        <a class="button button-gold" href="{{ route('booking.create') }}">＋ จองคิวใหม่</a>
    </div>
    @if(session('success'))<div class="notice notice-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
    <div class="history-list">
        @forelse($bookings as $booking)
            <article class="history-card">
                <div class="history-date">
                    <span>{{ $booking->booking_date->format('M') }}</span>
                    <strong>{{ $booking->booking_date->format('d') }}</strong>
                    <small>{{ $booking->booking_date->format('Y') }}</small>
                </div>
                <div class="history-detail">
                    <span class="eyebrow">BOOKING {{ $booking->booking_code }}</span>
                    <h2>{{ $booking->service?->name ?? 'บริการที่ยกเลิกแล้ว' }}</h2>
                    <p class="history-customer">ชื่อผู้จอง: <strong>{{ $booking->customer_name ?: ($booking->user?->name ?? 'ไม่ระบุชื่อ') }}</strong></p>
                    <p>{{ $booking->booking_date->format('d/m/Y') }} &nbsp;·&nbsp; {{ substr($booking->booking_time, 0, 5) }} น. &nbsp;·&nbsp; {{ $booking->duration_minutes }} นาที</p>
                    <span class="status-pill status-{{ $booking->status }}">{{ ['pending' => 'รอยืนยัน', 'confirmed' => 'ยืนยันแล้ว', 'cancelled' => 'ยกเลิก', 'completed' => 'เสร็จสิ้น'][$booking->status] }}</span>
                </div>
                <div class="history-price">
                    <span>ราคาบริการ</span>
                    <strong>฿{{ number_format($booking->price) }}</strong>
                    @if($booking->status === 'pending')
                        <div class="history-actions">
                            <a class="edit-link" href="{{ route('bookings.edit', $booking) }}">แก้ไขการจอง</a>
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('ยืนยันยกเลิกการจองนี้?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cancel-link">ยกเลิกการจอง</button>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="history-empty">
                <span>✦</span>
                <h2>ยังไม่มีรายการนัดหมาย</h2>
                <p>เลือกบริการที่ใช่ แล้วให้เราดูแลช่วงเวลาพักผ่อนของคุณ</p>
                <a href="{{ route('booking.create') }}" class="button button-gold">จองคิวแรกของคุณ</a>
            </div>
        @endforelse
    </div>
    <div class="pagination">{{ $bookings->links() }}</div>
</main>
<footer class="inner-footer">RELAX MASSAGE &amp; SPA · A MOMENT JUST FOR YOU</footer>
</body>
</html>
