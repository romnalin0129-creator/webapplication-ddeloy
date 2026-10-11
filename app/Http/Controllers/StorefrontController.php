<?php

namespace App\Http\Controllers;

use App\Models\MassageService;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'categories' => config('store_catalog.categories'),
            'products' => StoreProduct::query()
                ->where('is_active', true)
                ->orderBy('id')
                ->get()
                ->map(fn (StoreProduct $product): array => [
                    'id' => $product->slug,
                    'name' => $product->name,
                    'category' => $product->category,
                    'price' => (float) $product->price,
                    'icon' => $product->icon,
                    'tag' => $product->tag,
                    'color' => $product->color,
                    'description' => $product->description,
                    'bestseller' => $product->is_bestseller,
                ]),
        ]);
    }

    public function services(): View
    {
        $services = MassageService::query()->where('is_active', true)->orderBy('price')->get();

        return view('storefront.services', compact('services'));
    }

    public function about(): View
    {
        return view('storefront.about');
    }

    public function contact(): View
    {
        return view('storefront.contact');
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
