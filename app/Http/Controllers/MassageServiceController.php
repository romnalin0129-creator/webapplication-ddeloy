<?php

namespace App\Http\Controllers;

use App\Models\MassageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MassageServiceController extends Controller
{
    public function index(): View
    {
        $services = MassageService::query()->latest()->paginate(12);

        return view('services.index', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateService($request);
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }
        $validated['is_active'] = $request->boolean('is_active');
        MassageService::create($validated);

        return redirect()->route('services.index')->with('success', 'เพิ่มบริการเรียบร้อยแล้ว');
    }

    public function update(Request $request, MassageService $service): RedirectResponse
    {
        $validated = $this->validateService($request);
        $previousImage = $service->image_path;
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }
        $validated['is_active'] = $request->boolean('is_active');
        $service->update($validated);

        if ($service->image_path !== $previousImage && $previousImage) {
            Storage::disk('public')->delete($previousImage);
        }

        return redirect()->route('services.index')->with('success', 'บันทึกการแก้ไขบริการแล้ว');
    }

    public function destroy(MassageService $service): RedirectResponse
    {
        if ($service->bookings()->exists()) {
            throw ValidationException::withMessages(['service' => 'ไม่สามารถลบบริการที่มีประวัติการจองได้']);
        }

        $image = $service->image_path;
        $service->delete();
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()->route('services.index')->with('success', 'ลบบริการแล้ว');
    }

    private function validateService(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:240'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999'],
            'image' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'กรุณากรอกชื่อบริการ',
            'description.required' => 'กรุณากรอกรายละเอียดบริการ',
            'duration_minutes.required' => 'กรุณากรอกระยะเวลา',
            'duration_minutes.min' => 'ระยะเวลาต้องไม่น้อยกว่า 15 นาที',
            'price.required' => 'กรุณากรอกราคา',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพ',
        ]);
    }
}
