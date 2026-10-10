<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class AppointmentAvailability
{
    public const TIMES = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

    public function availableTimes(string $date): array
    {
        return collect(self::TIMES)->mapWithKeys(function (string $time) use ($date) {
            $start = Carbon::parse($date.' '.$time);
            $available = ! $start->isPast() && ! $this->hasOverlap($date, $start, 0);

            return [$time => $available];
        })->all();
    }

    public function hasOverlap(string $date, Carbon $start, int $duration, ?int $exceptBookingId = null): bool
    {
        $bookings = Booking::query()
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->when($exceptBookingId, fn ($query) => $query->where('id', '!=', $exceptBookingId))
            ->get(['id', 'booking_date', 'booking_time', 'duration_minutes']);

        return $bookings->contains(function (Booking $booking) use ($start, $duration) {
            $existingStart = Carbon::parse($booking->booking_date->toDateString().' '.$booking->booking_time);
            $existingEnd = $existingStart->copy()->addMinutes($booking->duration_minutes);
            $requestedEnd = $start->copy()->addMinutes($duration ?: 1);

            return $start->lt($existingEnd) && $existingStart->lt($requestedEnd);
        });
    }

    public function lockDate(string $date): void
    {
        \Illuminate\Support\Facades\DB::table('booking_days')->insertOrIgnore([
            'booking_date' => $date,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        \Illuminate\Support\Facades\DB::table('booking_days')
            ->where('booking_date', $date)
            ->lockForUpdate()
            ->first();
    }
}
