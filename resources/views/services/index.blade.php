<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการบริการ | Relax Massage &amp; Spa</title>
    <link rel="stylesheet" href="{{ asset('css/relax.css') }}">
</head>
<body>
<header class="admin-topbar">
    <a class="logo" href="{{ route('home') }}"><span class="logo-symbol">R</span><span>RELAX<small>ADMIN STUDIO</small></span></a>
    <nav><a href="{{ route('dashboard') }}">แดชบอร์ด</a><a href="{{ route('home') }}">ดูหน้าร้าน ↗</a></nav>
</header>
<main class="admin-main">
    <div class="admin-welcome">
        <div><span class="eyebrow">TREATMENT MENU</span><h1>จัดการบริการนวด</h1><p>เพิ่ม ดู แก้ไข และลบบริการได้จากหน้านี้</p></div>
        <a class="button button-gold" href="#create-service">＋ เพิ่มบริการ</a>
    </div>
    @if(session('success'))<div class="notice notice-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif

    <details id="create-service" class="service-create" open>
        <summary><span><strong>＋ เพิ่มบริการใหม่</strong><small>ระบุชื่อ รายละเอียด ราคา ระยะเวลา และรูปภาพ</small></span><span class="create-toggle">เปิดฟอร์ม</span></summary>
        <form class="service-editor" method="POST" enctype="multipart/form-data" action="{{ route('services.store') }}">
            @csrf
            <div class="fields-two">
                <label><span class="field-label">ชื่อบริการ *</span><input name="name" value="{{ old('name') }}" required maxlength="150" placeholder="เช่น นวดแผนไทย"></label>
                <label><span class="field-label">ราคา (บาท) *</span><input type="number" min="0" step="1" name="price" value="{{ old('price') }}" required></label>
                <label><span class="field-label">ระยะเวลา (นาที) *</span><input type="number" min="15" max="240" step="15" name="duration_minutes" value="{{ old('duration_minutes') }}" required></label>
                <label class="full-field"><span class="field-label">รายละเอียด *</span><textarea name="description" rows="3" required maxlength="2000"></textarea></label>
                <label class="full-field"><span class="field-label">รูปภาพบริการ</span><input type="file" name="image" accept="image/*"><small>ไฟล์ภาพไม่เกิน 5 MB</small></label>
                <label class="active-check full-field"><input type="checkbox" name="is_active" value="1" checked> เปิดให้ลูกค้าจองบริการนี้</label>
            </div>
            <div class="editor-actions"><button class="button button-gold" type="submit">เพิ่มบริการ</button></div>
        </form>
    </details>

    <section class="admin-panel">
        <div class="table-scroll">
            <table class="admin-table">
                <thead><tr><th>บริการ</th><th>ระยะเวลา</th><th>ราคา</th><th>สถานะ</th><th>จัดการ</th></tr></thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <div class="service-admin-name">
                                    @if($service->image_path)
                                        <img src="{{ str_starts_with($service->image_path, 'http') ? $service->image_path : \Illuminate\Support\Facades\Storage::url($service->image_path) }}" alt="">
                                    @endif
                                    <div><strong>{{ $service->name }}</strong><small>{{ \Illuminate\Support\Str::limit($service->description, 80) }}</small></div>
                                </div>
                            </td>
                            <td>{{ $service->duration_minutes }} นาที</td>
                            <td>฿{{ number_format($service->price) }}</td>
                            <td>{{ $service->is_active ? 'เปิดให้จอง' : 'ปิดบริการ' }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="#edit-service-{{ $service->id }}">แก้ไข</a>
                                    <form method="POST" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('ยืนยันลบบริการ {{ $service->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">ลบ</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr class="service-edit-row" id="edit-service-{{ $service->id }}">
                            <td colspan="5">
                                <details class="service-edit">
                                    <summary>แก้ไขข้อมูล {{ $service->name }}</summary>
                                    <form class="service-editor" method="POST" enctype="multipart/form-data" action="{{ route('services.update', $service) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="fields-two">
                                            <label><span class="field-label">ชื่อบริการ *</span><input name="name" value="{{ $service->name }}" required maxlength="150"></label>
                                            <label><span class="field-label">ราคา (บาท) *</span><input type="number" min="0" step="1" name="price" value="{{ $service->price }}" required></label>
                                            <label><span class="field-label">ระยะเวลา (นาที) *</span><input type="number" min="15" max="240" step="15" name="duration_minutes" value="{{ $service->duration_minutes }}" required></label>
                                            <label class="full-field"><span class="field-label">รายละเอียด *</span><textarea name="description" rows="3" required maxlength="2000">{{ $service->description }}</textarea></label>
                                            <label class="full-field">
                                                <span class="field-label">รูปภาพบริการ</span>
                                                @if($service->image_path)
                                                    <img class="editor-image" src="{{ str_starts_with($service->image_path, 'http') ? $service->image_path : \Illuminate\Support\Facades\Storage::url($service->image_path) }}" alt="ภาพปัจจุบันของ {{ $service->name }}">
                                                @endif
                                                <input type="file" name="image" accept="image/*">
                                                <small>เว้นว่างเพื่อคงรูปเดิม · ไฟล์ไม่เกิน 5 MB</small>
                                            </label>
                                            <label class="active-check full-field"><input type="checkbox" name="is_active" value="1" @checked($service->is_active)> เปิดให้ลูกค้าจองบริการนี้</label>
                                        </div>
                                        <div class="editor-actions"><button class="button button-gold" type="submit">บันทึกการแก้ไข</button></div>
                                    </form>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="table-empty">ยังไม่มีบริการ เพิ่มบริการแรกได้จากแบบฟอร์มด้านบน</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $services->links() }}</div>
    </section>
</main>
<script>
    document.querySelectorAll('.service-edit-row').forEach(row => {
        if (window.location.hash === `#${row.id}`) row.querySelector('details')?.setAttribute('open', '');
    });
</script>
</body>
</html>
