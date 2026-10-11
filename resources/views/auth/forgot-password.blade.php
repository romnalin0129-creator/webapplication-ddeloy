<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">ลืมรหัสผ่าน?</h1>
            <p class="mt-2 text-sm leading-6 text-gray-600">
                กรอกอีเมลที่ใช้สมัครสมาชิก แล้วเราจะส่งลิงก์สำหรับตั้งรหัสผ่านใหม่ให้คุณ
            </p>
        </div>

        @if (session('status'))
            <div role="status" class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="อีเมล" />
                <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
            </div>

            <div class="mt-6">
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-500 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-pink-200 transition hover:-translate-y-0.5 hover:from-pink-600 hover:to-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2">
                    ส่งลิงก์ตั้งรหัสผ่านใหม่
                </button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            จำรหัสผ่านได้แล้ว?
            <a class="font-semibold text-pink-600 hover:text-pink-800" href="{{ route('login') }}">กลับไปเข้าสู่ระบบ</a>
        </p>
    </x-authentication-card>
</x-guest-layout>
