<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StoreProductController extends Controller
{
    public function index(): View
    {
        $products = StoreProduct::query()->orderBy('category')->orderBy('name')->get();

        return view('admin.products', [
            'products' => $products,
            'categories' => config('store_catalog.categories'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedProduct($request);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');

        StoreProduct::create($validated);

        return back()->with('success', 'เพิ่มสินค้าในหน้าร้านแล้ว');
    }

    public function update(Request $request, StoreProduct $product): RedirectResponse
    {
        $validated = $this->validatedProduct($request, $product);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_bestseller'] = $request->boolean('is_bestseller');

        $product->update($validated);

        return back()->with('success', 'บันทึกการแก้ไขสินค้าแล้ว');
    }

    public function destroy(StoreProduct $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'ลบสินค้าออกจากหน้าร้านแล้ว');
    }

    private function validatedProduct(Request $request, ?StoreProduct $product = null): array
    {
        $categories = array_keys(config('store_catalog.categories'));

        return $request->validate([
            'slug' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('store_products', 'slug')->ignore($product?->id),
            ],
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in($categories)],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999'],
            'icon' => ['required', 'string', 'max:16'],
            'tag' => ['required', 'string', 'max:40'],
            'color' => ['required', Rule::in(['pink', 'lavender', 'lemon', 'rose', 'mint', 'peach', 'sky', 'night'])],
            'description' => ['required', 'string', 'max:1000'],
            'is_active' => ['sometimes', 'boolean'],
            'is_bestseller' => ['sometimes', 'boolean'],
        ], [
            'slug.regex' => 'รหัสสินค้าต้องใช้อักษรภาษาอังกฤษตัวเล็ก ตัวเลข และขีดกลางเท่านั้น',
            'slug.unique' => 'รหัสสินค้านี้ถูกใช้งานแล้ว',
            'category.in' => 'กรุณาเลือกหมวดหมู่ที่ถูกต้อง',
        ]);
    }
}
