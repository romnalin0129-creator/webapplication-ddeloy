<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">ตั้งรหัสผ่านใหม่</h1>
                <p class="mt-2 text-sm text-gray-600">ตั้งรหัสผ่านใหม่เพื่อกลับไปช้อปสินค้าน่ารักที่ Kawaii Corner</p>
            </div>

            <div>
                <x-label for="email" value="อีเมล" />
                <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="รหัสผ่านใหม่" />
                <x-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="ยืนยันรหัสผ่านใหม่" />
                <x-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-6">
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-pink-500 to-fuchsia-500 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-pink-200 transition hover:-translate-y-0.5 hover:from-pink-600 hover:to-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-pink-400 focus:ring-offset-2">
                    บันทึกรหัสผ่านใหม่
                </button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
