<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl border border-transparent bg-amber-800 px-4 py-2 font-semibold text-sm text-white shadow-sm transition hover:bg-amber-900 focus:bg-amber-900 active:bg-amber-950 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
