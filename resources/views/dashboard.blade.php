<!doctype html><html lang="th">
    <head><meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการร้าน | Relax Massage &amp; Spa</title>
    <link rel="stylesheet" href="{{ asset('css/relax.css') }}"></head>
<body>
    <header class="admin-topbar"><a class="logo" href="{{ route('home') }}">
        <span class="logo-symbol">R</span>
        <span>RELAX<small>ADMIN STUDIO</small>
    </span>
</a>
<nav>
    <a href="{{ route('home') }}">ดูหน้าร้าน ↗</a>
    <a href="{{ route('services.index') }}">จัดการบริการ</a>
    <a href="{{ route('admin.members') }}">สมาชิก</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf<button type="submit">ออกจากระบบ</button>
    </form>
</nav>
</header>
<main class="admin-main">
    <div class="admin-welcome"><div>
        <span class="eyebrow">DASHBOARD / OVERVIEW</span>
        <h1>ยินดีต้อนรับ, {{ auth()->user()->name }}</h1>
        <p>จัดการการจองและภาพรวมของ Relax Massage &amp; Spa</p>
    </div>
    <a class="button button-gold" href="{{ route('services.index') }}#create-service">＋ จัดการบริการ</a>
</div>
    @if(session('success'))<div class="notice notice-success">{{ session('success') }}</div>@endif
    @if($errors->any())
    <div class="notice notice-error">{{ $errors->first() }}</div>
    @endif
    <section class="stat-grid">
        <article><span>บริการทั้งหมด</span>
        <strong>{{ number_format($serviceCount) }}</strong>
        <small>รายการในหน้าร้าน</small>
    </article>
        <article>
            <span>นัดหมายวันนี้</span>
            <strong>{{ number_format($todayCount) }}</strong>
            <small>จองคิวสำหรับวันนี้</small>
        </article>
        <article>
            <span>รอยืนยัน</span>
            <strong>{{ number_format($pendingCount) }}</strong>
            <small>รอดำเนินการ</small>
        </article>
        <article>
            <span>ลูกค้าสมาชิก</span>
            <strong>{{ number_format($memberCount) }}</strong>
            <small>บัญชีทั้งหมด</small>
        </article>
    </section>
    <section class="admin-panel revenue-panel">
        <div>
            <span class="eyebrow">BOOKING OVERVIEW</span>
            <h2>ยอดจองบริการ 7 วันล่าสุด</h2>
            <p>รวมรายการที่ยืนยันแล้วและเสร็จสิ้น · ฿{{ number_format($weekRevenue) }}</p>
        </div>
        <div class="revenue-chart">
            @foreach($chart as $day)
                <div class="chart-item">
                    <span class="chart-bar" style="height:{{ max(4, (int) ($day['total'] / $chartMaximum * 115)) }}px" title="฿{{ number_format($day['total']) }}"></span>
                    <small>{{ $day['label'] }}</small>
                </div>
            @endforeach
        </div>
    </section>
    <section class="admin-panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">APPOINTMENTS</span>
                <h2>รายการจองทั้งหมด</h2>
            </div>
        </div>
        <form class="admin-filters" method="GET" action="{{ route('dashboard') }}">
            <input name="q" value="{{ request('q') }}" placeholder="ค้นหาชื่อ เบอร์ หรือเลขจอง">
            <select name="status">
                <option value="">ทุกสถานะ</option>
                @foreach(['pending' => 'รอยืนยัน', 'confirmed' => 'ยืนยันแล้ว', 'cancelled' => 'ยกเลิก', 'completed' => 'เสร็จสิ้น'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="button button-dark">ค้นหา</button>
            <a class="clear-filter" href="{{ route('dashboard') }}">ล้าง</a>
        </form>
        <div class="table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>เลขที่จอง</th>
                        <th>ผู้จอง</th>
                        <th>บริการ</th>
                        <th>วันและเวลา</th>
                        <th>ราคา</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->booking_code }}</strong>
                                <small>{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                <strong>{{ filled($booking->customer_name) ? $booking->customer_name : ($booking->user?->name ?? 'ไม่ระบุชื่อ') }}</strong>
                                <small>{{ $booking->phone }}</small>
                                @if($booking->user?->name && $booking->user->name !== $booking->customer_name)
                                    <small>สมาชิก: {{ $booking->user->name }}</small>
                                @endif
                            </td>
                            <td>
                                {{ $booking->service?->name ?? 'บริการถูกลบ' }}
                                <small>{{ $booking->duration_minutes }} นาที</small>
                            </td>
                            <td>
                                {{ $booking->booking_date->format('d/m/Y') }}
                                <small>{{ substr($booking->booking_time, 0, 5) }} น.</small>
                            </td>
                            <td>฿{{ number_format($booking->price) }}</td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'pending' => 'รอยืนยัน',
                                        'confirmed' => 'ยืนยันแล้ว',
                                        'cancelled' => 'ยกเลิก',
                                        'completed' => 'เสร็จสิ้น',
                                    ];
                                @endphp
                                <span class="status-pill status-{{ $booking->status }}">
                                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                                </span>
                            </td>
                            <td>
                                @if(in_array($booking->status, ['pending', 'confirmed'], true))
                                    <form class="admin-actions" method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" aria-label="เปลี่ยนสถานะ {{ $booking->booking_code }}">
                                            <option value="confirmed" @selected($booking->status === 'confirmed')>ยืนยันแล้ว</option>
                                            <option value="cancelled">ยกเลิก</option>
                                            <option value="completed">เสร็จสิ้น</option>
                                        </select>
                                        <button type="submit">บันทึก</button>
                                    </form>
                                @else
                                    <span>ปิดรายการแล้ว</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="table-empty">ยังไม่มีรายการจองที่ตรงกับการค้นหา</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $bookings->links() }}</div>
    </section>
</main>
</body>
</html>
