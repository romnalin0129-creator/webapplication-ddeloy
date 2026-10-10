<!DOCTYPE html>
<html lang="th">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Relax Massage &amp; Spa — บัญชีผู้ใช้</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @php
            $cssPath = mix('/css/app.css');
            $jsPath = mix('/js/app.js');
        @endphp
        <link rel="stylesheet" href="{{ $cssPath }}">
        <script src="{{ $jsPath }}" defer></script>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-gray-800">
        <x-banner />

        <div class="min-h-screen bg-rose-50/70">
            @livewire('navigation-menu')

            @if (isset($header))
                <header class="border-b border-rose-100 bg-white/85 shadow-sm backdrop-blur">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
