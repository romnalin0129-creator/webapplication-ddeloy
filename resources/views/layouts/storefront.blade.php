<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Kawaii Corner — เมโลดี้ ธีม และสติกเกอร์ LINE น่ารัก ๆ เติมความสดใสให้ทุกวัน')">
    <meta name="theme-color" content="#fff8fb">
    <title>@yield('title', 'Kawaii Corner | เติมความน่ารักให้ทุกแชต')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Sarabun:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/relax.css') }}">
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>
    @include('storefront.partials.header')
    @if(session('success'))<div class="notice notice-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="notice notice-error">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="notice notice-error">{{ $errors->first() }}</div>@endif
    <main>@yield('content')</main>
    @include('storefront.partials.footer')
    <script src="{{ asset('js/relax.js') }}"></script>
    <script src="{{ asset('js/store.js') }}" defer></script>
</body>
</html>
