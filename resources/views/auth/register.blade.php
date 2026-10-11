<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6 text-center">
            <span class="inline-flex rounded-full bg-pink-100 px-3 py-1 text-xs font-semibold text-pink-800">เริ่มต้นวันดี ๆ ของคุณ</span>
            <h1 class="mt-3 text-2xl font-bold text-gray-900">สร้างบัญชีใหม่</h1>
            <p class="mt-2 text-sm text-gray-600">สมัครสมาชิกเพื่อสั่งซื้อและติดตามสินค้าน่ารักที่คุณเลือก</p>
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

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-500 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-pink-200 transition hover:-translate-y-0.5 hover:from-pink-600 hover:to-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2">
                สมัครสมาชิก
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            มีบัญชีอยู่แล้ว?
            <a class="font-semibold text-pink-600 hover:text-pink-800" href="{{ route('login') }}">เข้าสู่ระบบ</a>
        </p>
    </x-authentication-card>
</x-guest-layout>
