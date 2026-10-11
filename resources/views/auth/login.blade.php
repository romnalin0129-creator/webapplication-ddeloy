<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <span class="auth-eyebrow">WELCOME BACK ♡</span>
            <h1 class="text-2xl font-bold text-gray-900">เข้าสู่ระบบ</h1>
            <p class="mt-2 text-sm text-gray-600">เข้าสู่ระบบเพื่อสั่งซื้อและดูประวัติคำสั่งซื้อของคุณ</p>
        </div>

        @if (session('status'))
            <div role="status" class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="อีเมล" />
                <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="รหัสผ่าน" />
                <x-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="กรอกรหัสผ่านของคุณ" />
            </div>

            <div class="mt-4 flex items-center justify-between">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">จดจำการเข้าสู่ระบบ</span>
                </label>
                <a class="text-sm font-medium text-pink-600 hover:text-pink-800" href="{{ route('password.request') }}">
                    ลืมรหัสผ่าน?
                </a>
            </div>

            <div class="mt-6">
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-500 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-pink-200 transition hover:-translate-y-0.5 hover:from-pink-600 hover:to-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2">
                    เข้าสู่ระบบ
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            ยังไม่มีบัญชี?
            <a class="font-semibold text-pink-600 hover:text-pink-800" href="{{ route('register') }}">สมัครสมาชิก</a>
        </p>
    </x-authentication-card>
</x-guest-layout>
