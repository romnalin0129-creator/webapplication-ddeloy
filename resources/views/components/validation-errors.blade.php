@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-rose-700">ตรวจสอบข้อมูลอีกครั้งนะคะ</div>

        <ul class="mt-3 list-disc list-inside text-sm text-rose-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
