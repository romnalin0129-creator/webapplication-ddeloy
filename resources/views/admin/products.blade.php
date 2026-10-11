@extends('layouts.storefront')

@section('title', 'จัดการสินค้า | Kawaii Corner')

@section('content')
    <section class="orders-page product-admin-page">
        <a class="order-back" href="{{ route('home') }}">← กลับไปหน้าร้าน</a>
        <span class="section-kicker">SHOP ADMINISTRATION</span>
        <h1>จัดการสินค้า</h1>
        <p class="orders-lead">เพิ่ม แก้ไข หรือซ่อนสินค้าในหน้าร้าน รวม {{ $products->count() }} รายการ</p>

        <details class="product-editor" open>
            <summary>＋ เพิ่มสินค้าใหม่</summary>
            <form method="POST" action="{{ route('admin.products.store') }}" class="product-admin-form">
                @csrf
                <label>รหัสสินค้า<input name="slug" required maxlength="100" pattern="[a-z0-9]+(-[a-z0-9]+)*" placeholder="cute-new-sticker" value="{{ old('slug') }}"></label>
                <label>ชื่อสินค้า<input name="name" required maxlength="120" value="{{ old('name') }}"></label>
                <label>หมวดหมู่<select name="category" required>@foreach($categories as $key => $category)<option value="{{ $key }}" @selected(old('category') === $key)>{{ $category['name'] }}</option>@endforeach</select></label>
                <label>ราคา (บาท)<input name="price" type="number" min="0.01" step="0.01" required value="{{ old('price') }}"></label>
                <label>อีโมจิ<input name="icon" required maxlength="16" placeholder="🐰" value="{{ old('icon') }}"></label>
                <label>ป้ายสินค้า<input name="tag" required maxlength="40" placeholder="มาใหม่" value="{{ old('tag') }}"></label>
                <label>สีภาพ<select name="color">@foreach(['pink' => 'ชมพู', 'lavender' => 'ม่วง', 'lemon' => 'เหลือง', 'rose' => 'กุหลาบ', 'mint' => 'มิ้นต์', 'peach' => 'พีช', 'sky' => 'ฟ้า', 'night' => 'กลางคืน'] as $key => $name)<option value="{{ $key }}">{{ $name }}</option>@endforeach</select></label>
                <label class="product-admin-description">รายละเอียด<textarea name="description" rows="2" required maxlength="1000">{{ old('description') }}</textarea></label>
                <label class="admin-check"><input type="checkbox" name="is_active" value="1" checked> แสดงในหน้าร้าน</label>
                <label class="admin-check"><input type="checkbox" name="is_bestseller" value="1"> สินค้าขายดี</label>
                <button class="shop-button shop-button-primary" type="submit">เพิ่มสินค้า <span>＋</span></button>
            </form>
        </details>

        <h2 class="product-admin-list-title">สินค้าทั้งหมด</h2>
        @forelse($products as $product)
            <details class="product-editor product-edit-row">
                <summary><span class="admin-product-icon">{{ $product->icon }}</span><strong>{{ $product->name }}</strong><span class="admin-product-meta">{{ $categories[$product->category]['name'] ?? $product->category }} · ฿{{ number_format($product->price, 2) }}</span><span class="admin-product-state">{{ $product->is_active ? 'แสดงอยู่' : 'ซ่อน' }}</span></summary>
                <form method="POST" action="{{ route('admin.products.update', $product) }}" class="product-admin-form">
                    @csrf @method('PUT')
                    <label>รหัสสินค้า<input name="slug" required maxlength="100" pattern="[a-z0-9]+(-[a-z0-9]+)*" value="{{ $product->slug }}"></label>
                    <label>ชื่อสินค้า<input name="name" required maxlength="120" value="{{ $product->name }}"></label>
                    <label>หมวดหมู่<select name="category">@foreach($categories as $key => $category)<option value="{{ $key }}" @selected($product->category === $key)>{{ $category['name'] }}</option>@endforeach</select></label>
                    <label>ราคา (บาท)<input name="price" type="number" min="0.01" step="0.01" required value="{{ $product->price }}"></label>
                    <label>อีโมจิ<input name="icon" required maxlength="16" value="{{ $product->icon }}"></label>
                    <label>ป้ายสินค้า<input name="tag" required maxlength="40" value="{{ $product->tag }}"></label>
                    <label>สีภาพ<select name="color">@foreach(['pink' => 'ชมพู', 'lavender' => 'ม่วง', 'lemon' => 'เหลือง', 'rose' => 'กุหลาบ', 'mint' => 'มิ้นต์', 'peach' => 'พีช', 'sky' => 'ฟ้า', 'night' => 'กลางคืน'] as $key => $name)<option value="{{ $key }}" @selected($product->color === $key)>{{ $name }}</option>@endforeach</select></label>
                    <label class="product-admin-description">รายละเอียด<textarea name="description" rows="2" required maxlength="1000">{{ $product->description }}</textarea></label>
                    <label class="admin-check"><input type="checkbox" name="is_active" value="1" @checked($product->is_active)> แสดงในหน้าร้าน</label>
                    <label class="admin-check"><input type="checkbox" name="is_bestseller" value="1" @checked($product->is_bestseller)> สินค้าขายดี</label>
                    <button class="shop-button shop-button-primary" type="submit">บันทึกการแก้ไข</button>
                </form>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="product-delete-form" onsubmit="return confirm('ยืนยันการลบสินค้านี้?')">
                    @csrf @method('DELETE')
                    <button type="submit">ลบสินค้านี้</button>
                </form>
            </details>
        @empty
            <div class="orders-empty"><span>🎨</span><h2>ยังไม่มีสินค้า</h2><p>เพิ่มสินค้าใหม่เพื่อเริ่มต้นแคตตาล็อก</p></div>
        @endforelse
    </section>
@endsection
