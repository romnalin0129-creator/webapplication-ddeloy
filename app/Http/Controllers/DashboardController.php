<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MassageService;
use App\Models\User;
use App\Services\AppointmentAvailability;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->is_admin, 403);

        $weekStart = today()->subDays(6);
        $dailyBookings = Booking::query()
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereDate('booking_date', '>=', $weekStart)
            ->selectRaw('booking_date as day, SUM(price) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $chart = collect(range(6, 0))->map(function (int $daysAgo) use ($dailyBookings) {
            $date = today()->subDays($daysAgo);

            return [
                'label' => $date->format('d/m'),
                'total' => (float) ($dailyBookings->get($date->toDateString()) ?? 0),
            ];
        });

        $query = Booking::query()->with('service', 'user')->latest('booking_date')->latest('booking_time');
        if ($request->filled('status') && in_array($request->input('status'), ['pending', 'confirmed', 'cancelled', 'completed'], true)) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($builder) use ($term) {
                $builder->where('booking_code', 'like', '%'.$term.'%')
                    ->orWhere('customer_name', 'like', '%'.$term.'%')
                    ->orWhere('phone', 'like', '%'.$term.'%');
            });
        }

        return view('dashboard', [
            'bookings' => $query->paginate(12)->withQueryString(),
            'serviceCount' => MassageService::count(),
            'memberCount' => User::count(),
            'todayCount' => Booking::whereDate('booking_date', today())->count(),
            'pendingCount' => Booking::where('status', 'pending')->count(),
            'weekRevenue' => Booking::whereIn('status', ['confirmed', 'completed'])
                ->whereDate('booking_date', '>=', $weekStart)->sum('price'),
            'chart' => $chart,
            'chartMaximum' => max(1, (float) $chart->max('total')),
        ]);
    }

    public function updateBooking(Request $request, Booking $booking, AppointmentAvailability $availability): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
        ], [
            'status.required' => 'กรุณาเลือกสถานะการจอง',
            'status.in' => 'สถานะการจองไม่ถูกต้อง',
        ]);

        if (in_array($booking->status, ['cancelled', 'completed'], true) && $booking->status !== $validated['status']) {
            throw ValidationException::withMessages(['booking' => 'รายการนี้ปิดงานแล้ว ไม่สามารถเปลี่ยนสถานะได้']);
        }

        DB::transaction(function () use ($booking, $validated, $availability) {
            $availability->lockDate($booking->booking_date->toDateString());
            $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if (in_array($lockedBooking->status, ['cancelled', 'completed'], true) && $lockedBooking->status !== $validated['status']) {
                throw ValidationException::withMessages(['booking' => 'รายการนี้ปิดงานแล้ว ไม่สามารถเปลี่ยนสถานะได้']);
            }

            if ($validated['status'] === 'confirmed') {
                $start = Carbon::parse($lockedBooking->booking_date->toDateString().' '.$lockedBooking->booking_time);
                if ($availability->hasOverlap($lockedBooking->booking_date->toDateString(), $start, $lockedBooking->duration_minutes, $lockedBooking->id)) {
                    throw ValidationException::withMessages(['booking' => 'ไม่สามารถยืนยันได้ เนื่องจากช่วงเวลานี้มีคิวอื่นทับซ้อน']);
                }
            }

            $lockedBooking->update([
                'status' => $validated['status'],
                'slot_key' => in_array($validated['status'], ['cancelled', 'completed'], true) ? null : $lockedBooking->slot_key,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'อัปเดตสถานะการจองเรียบร้อยแล้ว');
    }

    public function members(Request $request): View
    {
        abort_unless($request->user()->is_admin, 403);

        return view('admin.members', ['members' => User::query()->latest()->paginate(20)]);
    }
}
