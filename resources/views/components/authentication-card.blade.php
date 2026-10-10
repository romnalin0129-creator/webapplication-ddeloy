<div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 sm:py-12 bg-gradient-to-br from-amber-50 via-white to-stone-100">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-7 bg-white/95 shadow-xl shadow-stone-200/70 ring-1 ring-stone-200 overflow-hidden rounded-3xl sm:px-8">
        {{ $slot }}
    </div>
</div>
