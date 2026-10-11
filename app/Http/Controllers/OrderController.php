<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StoreProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:18'],
            'items.*' => ['required', 'integer', 'min:1', 'max:10'],
        ], [
            'items.required' => 'กรุณาเลือกสินค้าก่อนสั่งซื้อ',
            'items.min' => 'กรุณาเลือกสินค้าก่อนสั่งซื้อ',
            'items.max' => 'สามารถสั่งซื้อสินค้าได้ไม่เกิน 18 รายการต่อคำสั่งซื้อ',
            'items.*.max' => 'เลือกสินค้าแต่ละรายการได้ไม่เกิน 10 ชิ้น',
        ]);

        $catalog = StoreProduct::query()
            ->where('is_active', true)
            ->whereIn('slug', array_keys($validated['items']))
            ->get()
            ->keyBy('slug');
        $unknownIds = array_diff(array_keys($validated['items']), $catalog->keys()->all());
        if ($unknownIds) {
            throw ValidationException::withMessages(['items' => 'มีสินค้าในตะกร้าที่ไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง']);
        }

        $orderNumber = 'KC-'.strtoupper(Str::random(10));

        DB::transaction(function () use ($validated, $catalog, $orderNumber, $request): void {
            foreach ($validated['items'] as $productId => $quantity) {
                $product = $catalog->get($productId);
                $unitPrice = (float) $product->price;

                Order::create([
                    'user_id' => $request->user()->id,
                    'order_number' => $orderNumber,
                    'product_name' => $product->name,
                    'customer_name' => $request->user()->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total' => $unitPrice * $quantity,
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('orders.show', $orderNumber)
            ->with('success', 'รับคำสั่งซื้อของคุณแล้ว ขอบคุณที่อุดหนุน Kawaii Corner 💗');
    }

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->groupBy('order_number');

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, string $orderNumber): View
    {
        $items = Order::query()
            ->where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber)
            ->get();

        abort_if($items->isEmpty(), 404);

        return view('orders.show', [
            'items' => $items,
            'orderNumber' => $orderNumber,
        ]);
    }

    public function adminIndex(): View
    {
        $orders = Order::query()->latest()->get()->groupBy('order_number');

        return view('admin.orders', compact('orders'));
    }

    public function reportPayment(Request $request, string $orderNumber): RedirectResponse
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber);

        abort_if(! $orders->exists(), 404);

        $updated = $orders->where('payment_status', 'unpaid')
            ->update(['payment_status' => 'reported']);

        if (! $updated) {
            return back()->with('error', 'รายการนี้แจ้งชำระแล้วหรือได้รับการยืนยันชำระเงินแล้ว');
        }

        return back()->with('success', 'แจ้งชำระเงินแล้ว กรุณารอร้านตรวจสอบยอด');
    }

    public function confirmPayment(Request $request, string $orderNumber): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:paid,unpaid'],
        ]);

        $orders = Order::query()->where('order_number', $orderNumber);
        abort_if(! $orders->exists(), 404);

        $updated = $orders->where('payment_status', 'reported')->update([
            'payment_status' => $validated['payment_status'],
            'paid_at' => $validated['payment_status'] === 'paid' ? now() : null,
        ]);

        if (! $updated) {
            return back()->with('error', 'ยืนยันได้เฉพาะรายการที่ลูกค้าแจ้งชำระแล้ว');
        }

        return back()->with('success', $validated['payment_status'] === 'paid'
            ? 'ยืนยันรับชำระเงินแล้ว'
            : 'ส่งรายการกลับเป็นรอชำระเงินแล้ว');
    }

    public function updateStatus(Request $request, string $orderNumber): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
        ]);

        $orders = Order::query()->where('order_number', $orderNumber);
        $firstOrder = $orders->first();
        abort_if(! $firstOrder, 404);

        if (in_array($validated['status'], ['processing', 'completed'], true) && $firstOrder->payment_status !== 'paid') {
            return back()->with('error', 'ต้องยืนยันการชำระเงินก่อนเปลี่ยนสถานะเป็นกำลังดำเนินการหรือสำเร็จแล้ว');
        }

        $orders->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        return back()->with('success', 'อัปเดตสถานะคำสั่งซื้อแล้ว');
    }
}
