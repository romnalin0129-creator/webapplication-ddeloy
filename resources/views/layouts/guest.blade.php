<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @if (request()->routeIs('login', 'welcome'))
                Kawaii Corner — เข้าสู่ระบบ
            @elseif (request()->routeIs('register'))
                Kawaii Corner — สมัครสมาชิก
            @elseif (request()->routeIs('password.request'))
                Kawaii Corner — ลืมรหัสผ่าน
            @elseif (request()->routeIs('password.reset'))
                Kawaii Corner — ตั้งรหัสผ่านใหม่
            @else
                Kawaii Corner — บัญชีผู้ใช้
            @endif
        </title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @php
            $cssPath = mix('/css/app.css').'?v='.filemtime(public_path('css/app.css'));
            $jsPath = mix('/js/app.js');
        @endphp
        <link rel="stylesheet" href="{{ $cssPath }}">
        <link rel="stylesheet" href="{{ asset('css/auth.css').'?v='.filemtime(public_path('css/auth.css')) }}">
        <script src="{{ $jsPath }}" defer></script>
    </head>
    <body class="auth-body">
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
</html>
