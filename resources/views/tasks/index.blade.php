<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div><p class="text-sm font-semibold text-pink-600">พื้นที่ส่วนตัว ✿</p><h2 class="mt-1 text-2xl font-bold text-gray-900">งานของฉัน</h2></div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-pink-600">กลับภาพรวมร้าน ↗</a>
        </div>
    </x-slot>
    <div class="min-h-screen bg-gradient-to-b from-rose-50/70 to-pink-50">
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            @if (session('status'))<div role="status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('status') }}</div>@endif
            <section class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-pink-100"><p class="text-sm font-medium text-gray-500">งานทั้งหมด</p><p class="mt-2 text-3xl font-bold">{{ $tasks->count() }}</p></div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-pink-100"><p class="text-sm font-medium text-gray-500">กำลังดำเนินการ</p><p class="mt-2 text-3xl font-bold text-pink-600">{{ $tasks->where('status', 'In Progress')->count() }}</p></div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-pink-100"><p class="text-sm font-medium text-gray-500">เสร็จแล้ว</p><p class="mt-2 text-3xl font-bold text-emerald-600">{{ $tasks->where('status', 'Completed')->count() }}</p></div>
            </section>
            <section class="grid items-start gap-6 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-pink-100">
                    <h3 class="text-lg font-bold">เพิ่มงานใหม่</h3><p class="mt-1 text-sm text-gray-500">บันทึกสิ่งที่ต้องทำไว้ในรายการของคุณ</p>
                    <form action="{{ route('tasks.store') }}" method="POST" class="mt-5 space-y-4">@csrf
                        <div><label for="new-title" class="mb-1.5 block text-sm font-medium">ชื่องาน</label><input id="new-title" name="title" value="{{ old('title') }}" required maxlength="255" class="w-full rounded-xl border-pink-200 shadow-sm focus:border-pink-400 focus:ring-pink-300" placeholder="เช่น วางแผนโปรเจกต์ใหม่">@error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div><label for="new-description" class="mb-1.5 block text-sm font-medium">รายละเอียด <span class="font-normal text-gray-400">(ไม่บังคับ)</span></label><textarea id="new-description" name="description" rows="4" maxlength="5000" class="w-full rounded-xl border-pink-200 shadow-sm focus:border-pink-400 focus:ring-pink-300" placeholder="เพิ่มรายละเอียดหรือบันทึกช่วยจำ">{{ old('description') }}</textarea>@error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-pink-500 to-rose-500 px-4 py-3 text-sm font-semibold text-white shadow-md">♡ เพิ่มงาน</button>
                    </form>
                </div>
                <div class="space-y-4"><div><h3 class="text-lg font-bold">รายการงานของฉัน</h3><p class="mt-1 text-sm text-gray-500">แก้ไขรายละเอียดหรืออัปเดตสถานะได้ทุกเมื่อ</p></div>
                    @forelse ($tasks as $task)
                        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-pink-100">
                            <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-3">@csrf @method('PUT')
                                <input name="title" value="{{ $task->title }}" required maxlength="255" aria-label="ชื่องาน" class="w-full rounded-lg border-pink-100 text-base font-semibold shadow-sm">
                                <textarea name="description" rows="2" maxlength="5000" aria-label="รายละเอียด" class="w-full rounded-lg border-pink-100 text-sm shadow-sm" placeholder="ยังไม่มีรายละเอียด">{{ $task->description }}</textarea>
                                <div class="flex flex-wrap items-center justify-between gap-3 border-t pt-3"><select name="status" aria-label="สถานะ" class="rounded-lg border-pink-100 py-2 text-sm">@foreach(['Pending'=>'รอดำเนินการ','In Progress'=>'กำลังทำ','Completed'=>'เสร็จแล้ว'] as $value=>$label)<option value="{{ $value }}" @selected($task->status === $value)>{{ $label }}</option>@endforeach</select><div class="flex gap-2"><button type="submit" class="rounded-lg bg-pink-100 px-3 py-2 text-sm font-semibold text-pink-700">บันทึก</button><button type="submit" form="delete-task-{{ $task->id }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-red-600" onclick="return confirm('ยืนยันการลบงานนี้หรือไม่?')">ลบ</button></div></div>
                            </form>
                            <form id="delete-task-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                        </article>
                    @empty
                        <div class="rounded-3xl border-2 border-dashed border-pink-200 bg-white/70 p-10 text-center text-sm text-gray-500">ยังไม่มีงานในรายการ เริ่มเพิ่มงานชิ้นแรกได้เลย ✨</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
