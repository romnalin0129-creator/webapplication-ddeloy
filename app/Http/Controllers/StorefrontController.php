<?php

namespace App\Http\Controllers;

use App\Models\MassageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(): View
    {
        $services = MassageService::query()->where('is_active', true)->orderBy('price')->get();

        return view('home', compact('services'));
    }

    public function availability(Request $request, AppointmentAvailability $availability)
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ], [
            'date.required' => 'กรุณาเลือกวันที่ต้องการจอง',
            'date.after_or_equal' => 'วันที่จองต้องเป็นวันนี้หรือวันหลังจากนี้',
        ]);

        return response()->json([
            'date' => $validated['date'],
            'times' => $availability->availableTimes($validated['date']),
        ]);
    }
}
