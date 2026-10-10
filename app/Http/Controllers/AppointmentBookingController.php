<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MassageService;
use App\Services\AppointmentAvailability;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentBookingController extends Controller
{
    public function create(Request $request): View
    {
        $services = MassageService::query()->where('is_active', true)->orderBy('price')->get();
        $selectedService = $request->integer('service', $request->integer('service_id'));
        $times = AppointmentAvailability::TIMES;
        $editingBooking = null;

        return view('bookings.create', compact('services', 'selectedService', 'times', 'editingBooking'));
    }

    public function availability(Request $request, AppointmentAvailability $availability)
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
        ], [
            'date.required' => 'กรุณาเลือกวันที่ต้องการจอง',
            'date.after_or_equal' => 'วันที่จองต้องเป็นวันนี้หรือวันหลังจากนี้',
            'service_id.required' => 'กรุณาเลือกบริการ',
        ]);

        $exceptBookingId = null;
        if (isset($validated['booking_id'])) {
            $booking = Booking::findOrFail($validated['booking_id']);
            abort_unless(
                $request->user()
                    && $booking->user_id === $request->user()->id
                    && $booking->status === 'pending',
                404
            );
            $exceptBookingId = $booking->id;
        }

        $duration = MassageService::whereKey($validated['service_id'])->value('duration_minutes');
        $times = collect(AppointmentAvailability::TIMES)->mapWithKeys(function (string $time) use ($validated, $duration, $availability, $exceptBookingId) {
            $start = \Carbon\Carbon::parse($validated['date'].' '.$time);

            return [$time => ! $start->isPast() && ! $availability->hasOverlap($validated['date'], $start, $duration, $exceptBookingId)];
        });

        return response()->json(['times' => $times]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i', 'in:'.implode(',', AppointmentAvailability::TIMES)],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'service_id.required' => 'กรุณาเลือกบริการนวด',
            'service_id.exists' => 'ไม่พบบริการที่เลือก',
            'booking_date.required' => 'กรุณาเลือกวันที่ต้องการจอง',
            'booking_date.after_or_equal' => 'วันที่จองต้องเป็นวันนี้หรือวันหลังจากนี้',
            'booking_time.required' => 'กรุณาเลือกเวลาจอง',
            'booking_time.in' => 'กรุณาเลือกเวลาจากรอบที่ร้านเปิดให้บริการ',
            'customer_name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
        ]);

        $booking = DB::transaction(function () use ($request, $validated) {
            $service = MassageService::query()
                ->whereKey($validated['service_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->first();

            if (! $service) {
                throw ValidationException::withMessages(['service_id' => 'บริการนี้ปิดให้จองแล้ว กรุณาเลือกบริการอื่น']);
            }

            $availability = app(AppointmentAvailability::class);
            $availability->lockDate($validated['booking_date']);
            $start = \Carbon\Carbon::parse($validated['booking_date'].' '.$validated['booking_time']);
            if ($start->isPast() || $availability->hasOverlap($validated['booking_date'], $start, $service->duration_minutes)) {
                throw ValidationException::withMessages(['booking_time' => 'ช่วงเวลานี้ไม่ว่างแล้ว กรุณาเลือกเวลาอื่น']);
            }

            try {
                return Booking::create([
                    'booking_code' => 'RM'.now()->format('ymd').Str::upper(Str::random(6)),
                    'user_id' => $request->user()->id,
                    'service_id' => $service->id,
                    'booking_date' => $validated['booking_date'],
                    'booking_time' => $validated['booking_time'],
                    'customer_name' => $validated['customer_name'],
                    'phone' => $validated['phone'],
                    'notes' => $validated['notes'] ?? null,
                    'duration_minutes' => $service->duration_minutes,
                    'price' => $service->price,
                    'status' => 'pending',
                    'slot_key' => $validated['booking_date'].'_'.$validated['booking_time'],
                ]);
            } catch (QueryException $exception) {
                $message = strtolower($exception->getMessage());
                if (! str_contains($message, 'bookings_slot_key_unique')
                    && ! str_contains($message, 'unique constraint failed: bookings.slot_key')
                    && ! str_contains($message, 'duplicate entry')) {
                    throw $exception;
                }

                throw ValidationException::withMessages(['booking_time' => 'ช่วงเวลานี้เพิ่งมีผู้จอง กรุณาเลือกเวลาอื่น']);
            }
        });

        return redirect()->route('bookings.mine')->with('success', 'จองคิวสำเร็จ เลขที่การจอง '.$booking->booking_code.' — รอยืนยันจากทางร้าน');
    }

    public function mine(Request $request): View
    {
        $bookings = Booking::query()
            ->with('service')
            ->where('user_id', $request->user()->id)
            ->latest('booking_date')
            ->latest('booking_time')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function edit(Request $request, Booking $booking): View
    {
        $this->authorizeBookingOwner($request, $booking);
        abort_unless($booking->status === 'pending', 403, 'แก้ไขได้เฉพาะรายการที่รอยืนยันเท่านั้น');

        $services = MassageService::query()
            ->where('is_active', true)
            ->orWhere('id', $booking->service_id)
            ->orderBy('price')
            ->get();
        $selectedService = $booking->service_id;
        $times = AppointmentAvailability::TIMES;
        $editingBooking = $booking;

        return view('bookings.create', compact('services', 'selectedService', 'times', 'editingBooking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeBookingOwner($request, $booking);
        abort_unless($booking->status === 'pending', 403, 'แก้ไขได้เฉพาะรายการที่รอยืนยันเท่านั้น');

        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'booking_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i', 'in:'.implode(',', AppointmentAvailability::TIMES)],
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'service_id.required' => 'กรุณาเลือกบริการนวด',
            'service_id.exists' => 'ไม่พบบริการที่เลือก',
            'booking_date.required' => 'กรุณาเลือกวันที่ต้องการจอง',
            'booking_date.after_or_equal' => 'วันที่จองต้องเป็นวันนี้หรือวันหลังจากนี้',
            'booking_time.required' => 'กรุณาเลือกเวลาจอง',
            'booking_time.in' => 'กรุณาเลือกเวลาจากรอบที่ร้านเปิดให้บริการ',
            'customer_name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
        ]);

        try {
            DB::transaction(function () use ($booking, $validated) {
                $datesToLock = collect([
                    $booking->booking_date->toDateString(),
                    $validated['booking_date'],
                ])->unique()->sort()->values();

                $availability = app(AppointmentAvailability::class);
                foreach ($datesToLock as $date) {
                    $availability->lockDate($date);
                }

                $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
                if ($lockedBooking->status !== 'pending') {
                    throw ValidationException::withMessages(['booking' => 'แก้ไขได้เฉพาะรายการที่รอยืนยันเท่านั้น']);
                }
                if ($lockedBooking->booking_date->toDateString() !== $booking->booking_date->toDateString()) {
                    throw ValidationException::withMessages(['booking' => 'รายการนี้เพิ่งถูกแก้ไข กรุณาโหลดหน้าใหม่แล้วลองอีกครั้ง']);
                }

                $service = MassageService::query()->whereKey($validated['service_id'])->lockForUpdate()->first();
                if (! $service || (! $service->is_active && $service->id !== $lockedBooking->service_id)) {
                    throw ValidationException::withMessages(['service_id' => 'บริการนี้ปิดให้จองแล้ว กรุณาเลือกบริการอื่น']);
                }

                $start = \Carbon\Carbon::parse($validated['booking_date'].' '.$validated['booking_time']);
                if ($start->isPast() || $availability->hasOverlap(
                    $validated['booking_date'],
                    $start,
                    $service->duration_minutes,
                    $lockedBooking->id
                )) {
                    throw ValidationException::withMessages(['booking_time' => 'ช่วงเวลานี้ไม่ว่างแล้ว กรุณาเลือกเวลาอื่น']);
                }

                $lockedBooking->update([
                    'service_id' => $service->id,
                    'booking_date' => $validated['booking_date'],
                    'booking_time' => $validated['booking_time'],
                    'customer_name' => $validated['customer_name'],
                    'phone' => $validated['phone'],
                    'notes' => $validated['notes'] ?? null,
                    'duration_minutes' => $service->duration_minutes,
                    'price' => $service->price,
                    'slot_key' => $validated['booking_date'].'_'.$validated['booking_time'],
                ]);
            });
        } catch (QueryException $exception) {
            $message = strtolower($exception->getMessage());
            if (! str_contains($message, 'bookings_slot_key_unique')
                && ! str_contains($message, 'unique constraint failed: bookings.slot_key')
                && ! str_contains($message, 'duplicate entry')) {
                throw $exception;
            }

            throw ValidationException::withMessages(['booking_time' => 'ช่วงเวลานี้เพิ่งมีผู้จอง กรุณาเลือกเวลาอื่น']);
        }

        return redirect()->route('bookings.mine')->with('success', 'แก้ไขการจอง '.$booking->booking_code.' เรียบร้อยแล้ว');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $this->authorizeBookingOwner($request, $booking);

        DB::transaction(function () use ($booking) {
            $availability = app(AppointmentAvailability::class);
            $availability->lockDate($booking->booking_date->toDateString());
            $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();
            if ($lockedBooking->status !== 'pending') {
                throw ValidationException::withMessages(['booking' => 'ยกเลิกได้เฉพาะรายการที่รอยืนยันเท่านั้น']);
            }
            $lockedBooking->update(['status' => 'cancelled', 'slot_key' => null]);
        });

        return redirect()->route('bookings.mine')->with('success', 'ยกเลิกการจอง '.$booking->booking_code.' แล้ว');
    }

    private function authorizeBookingOwner(Request $request, Booking $booking): void
    {
        abort_unless($booking->user_id === $request->user()->id, 404);
    }
}
