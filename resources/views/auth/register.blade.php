<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6 text-center">
            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">เริ่มต้นวันดี ๆ ของคุณ</span>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">สร้างบัญชีใหม่</h1>
            <p class="mt-2 text-sm text-gray-600">สมัครสมาชิกเพื่อจองคิวและติดตามการนัดหมายของคุณ</p>
        </div>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <x-label for="name" value="ชื่อ" />
                <x-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="ชื่อของคุณ" />
            </div>

            <div>
                <x-label for="email" value="อีเมล" />
                <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            </div>

            <div>
                <x-label for="password" value="รหัสผ่าน" />
                <x-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" placeholder="อย่างน้อย 8 ตัวอักษร" />
            </div>

            <div>
                <x-label for="password_confirmation" value="ยืนยันรหัสผ่าน" />
                <x-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="กรอกรหัสผ่านอีกครั้ง" />
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-amber-700 to-yellow-700 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-amber-200 transition hover:-translate-y-0.5 hover:from-amber-800 hover:to-yellow-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                สมัครสมาชิก
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            มีบัญชีอยู่แล้ว?
            <a class="font-semibold text-amber-700 hover:text-amber-900" href="{{ route('login') }}">เข้าสู่ระบบ</a>
        </p>
    </x-authentication-card>
</x-guest-layout>
