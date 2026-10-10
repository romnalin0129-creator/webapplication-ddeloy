<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\MassageService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MassageBookingTest extends TestCase
{
    use RefreshDatabase;

    private function service(array $attributes = []): MassageService
    {
        return MassageService::create(array_merge([
            'name' => 'นวดแผนไทย',
            'description' => 'นวดผ่อนคลายและคลายเส้น',
            'duration_minutes' => 60,
            'price' => 300,
            'is_active' => true,
        ], $attributes));
    }

    private function makeBooking(MassageService $service, User $user, string $time, int $duration = 60): Booking
    {
        $date = today()->addDays(3)->toDateString();

        return Booking::create([
            'booking_code' => 'RM'.strtoupper(substr(md5($time.$duration.$service->id), 0, 10)),
            'user_id' => $user->id,
            'service_id' => $service->id,
            'booking_date' => $date,
            'booking_time' => $time,
            'customer_name' => $user->name,
            'phone' => '0812345678',
            'duration_minutes' => $duration,
            'price' => $service->price,
            'status' => 'pending',
            'slot_key' => $date.'_'.$time,
        ]);
    }

    public function test_home_page_displays_database_services_and_their_prices(): void
    {
        $service = $this->service();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Relax Massage')
            ->assertSee('ผ่อนคลายทุกสัมผัส')
            ->assertSee($service->name)
            ->assertSee('฿300')
            ->assertSee('60 นาที')
            ->assertSee('ค้นหารอบว่างของคุณ')
            ->assertSee('A SIMPLE RITUAL')
            ->assertSee('คำถามที่พบบ่อย')
            ->assertSee('home-availability')
            ->assertSee(route('login'))
            ->assertSee(route('register'))
            ->assertSee(route('password.request'));
    }

    public function test_homepage_shows_logout_and_booking_history_for_signed_in_members(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('home'))
            ->assertOk()
            ->assertSee(route('logout'))
            ->assertSee(route('bookings.mine'))
            ->assertDontSee(route('register'));
    }

    public function test_booking_requires_login_and_captures_service_snapshot_and_guest_history_is_private(): void
    {
        $service = $this->service();
        $payload = [
            'service_id' => $service->id,
            'booking_date' => today()->addDays(3)->toDateString(),
            'booking_time' => '10:00',
            'customer_name' => 'ลูกค้าทดสอบ',
            'phone' => '0812345678',
            'notes' => 'ขอเน้นไหล่',
        ];
        $this->post(route('booking.store'), $payload)->assertRedirect(route('login'));

        $user = User::factory()->create();
        $this->actingAs($user)->post(route('booking.store'), $payload)
            ->assertRedirect(route('bookings.mine'))
            ->assertSessionHas('success');

        $booking = Booking::firstOrFail();
        $this->assertSame($user->id, $booking->user_id);
        $this->assertSame(60, $booking->duration_minutes);
        $this->assertEquals(300, $booking->price);
        $this->assertSame('pending', $booking->status);
        $this->assertDatabaseHas('bookings', ['booking_code' => $booking->booking_code]);

        $this->get(route('bookings.mine'))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee('นวดแผนไทย')
            ->assertSee('ชื่อผู้จอง:')
            ->assertSee('ลูกค้าทดสอบ')
            ->assertSee(route('bookings.edit', $booking));
        $this->actingAs(User::factory()->create())->get(route('bookings.mine'))->assertOk()->assertDontSee($booking->booking_code);
    }

    public function test_homepage_available_slot_link_prefills_the_booking_form(): void
    {
        $user = User::factory()->create();
        $service = $this->service();
        $date = today()->addDays(4)->toDateString();

        $this->actingAs($user)
            ->get(route('booking.create', [
                'service' => $service->id,
                'date' => $date,
                'time' => '14:00',
            ]))
            ->assertOk()
            ->assertSee('value="'.$service->id.'"', false)
            ->assertSee('value="'.$date.'"', false)
            ->assertSee('value="14:00" checked', false);
    }

    public function test_same_time_and_overlapping_treatment_durations_are_rejected(): void
    {
        $user = User::factory()->create();
        $oneHour = $this->service();
        $ninetyMinutes = $this->service([
            'name' => 'นวดประคบสมุนไพร',
            'duration_minutes' => 90,
            'price' => 600,
        ]);
        $date = today()->addDays(3)->toDateString();
        $payload = [
            'service_id' => $ninetyMinutes->id,
            'booking_date' => $date,
            'booking_time' => '10:00',
            'customer_name' => $user->name,
            'phone' => '0812345678',
        ];

        $this->actingAs($user)->post(route('booking.store'), $payload)->assertRedirect(route('bookings.mine'));
        $this->post(route('booking.store'), $payload)->assertSessionHasErrors('booking_time');

        $overlappingPayload = array_merge($payload, [
            'service_id' => $oneHour->id,
            'booking_time' => '11:00',
        ]);
        $this->post(route('booking.store'), $overlappingPayload)->assertSessionHasErrors('booking_time');

        $availability = $this->getJson(route('booking.availability', [
            'date' => $date,
            'service_id' => $oneHour->id,
        ]))->assertOk()->json('times');
        $this->assertFalse($availability['10:00']);
        $this->assertFalse($availability['11:00']);
        $this->assertTrue($availability['13:00']);
    }

    public function test_customer_can_cancel_only_own_pending_booking_and_release_that_slot(): void
    {
        $service = $this->service();
        $owner = User::factory()->create();
        $booking = $this->makeBooking($service, $owner, '10:00');

        $this->actingAs(User::factory()->create())
            ->delete(route('bookings.cancel', $booking))
            ->assertNotFound();

        $this->actingAs($owner)
            ->delete(route('bookings.cancel', $booking))
            ->assertRedirect(route('bookings.mine'));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'cancelled', 'slot_key' => null]);
    }

    public function test_customer_can_edit_own_pending_booking_and_availability_excludes_that_booking(): void
    {
        $user = User::factory()->create();
        $service = $this->service();
        $booking = $this->makeBooking($service, $user, '10:00');
        $otherService = $this->service([
            'name' => 'นวดประคบสมุนไพร',
            'duration_minutes' => 90,
            'price' => 600,
        ]);

        $this->actingAs($user)
            ->get(route('bookings.edit', $booking))
            ->assertOk()
            ->assertSee('แก้ไขการจอง')
            ->assertSee('นวดแผนไทย');

        $availability = $this->getJson(route('booking.availability', [
            'date' => $booking->booking_date->toDateString(),
            'service_id' => $service->id,
            'booking_id' => $booking->id,
        ]))->assertOk()->json('times');
        $this->assertTrue($availability['10:00']);

        $newDate = today()->addDays(5)->toDateString();
        $blocker = $this->makeBooking($service, User::factory()->create(), '13:00');
        $blocker->update(['booking_date' => $newDate, 'slot_key' => $newDate.'_13:00']);
        $newAvailability = $this->getJson(route('booking.availability', [
            'date' => $newDate,
            'service_id' => $otherService->id,
            'booking_id' => $booking->id,
        ]))->assertOk()->json('times');
        $this->assertFalse($newAvailability['13:00']);

        $conflictingUpdate = [
            'service_id' => $otherService->id,
            'booking_date' => $newDate,
            'booking_time' => '13:00',
            'customer_name' => 'ผู้จองแก้ไข',
            'phone' => '0899999999',
        ];
        $this->patch(route('bookings.update', $booking), $conflictingUpdate)
            ->assertSessionHasErrors('booking_time');

        $this->patch(route('bookings.update', $booking), [
            'service_id' => $otherService->id,
            'booking_date' => $newDate,
            'booking_time' => '14:00',
            'customer_name' => 'ผู้จองแก้ไข',
            'phone' => '0899999999',
            'notes' => 'ขอเพิ่มเวลานวด',
        ])->assertRedirect(route('bookings.mine'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'service_id' => $otherService->id,
            'booking_date' => $newDate,
            'booking_time' => '14:00:00',
            'customer_name' => 'ผู้จองแก้ไข',
            'phone' => '0899999999',
            'duration_minutes' => 90,
            'price' => 600,
            'slot_key' => $newDate.'_14:00',
        ]);
    }

    public function test_customer_cannot_edit_another_customers_or_confirmed_booking(): void
    {
        $owner = User::factory()->create();
        $booking = $this->makeBooking($this->service(), $owner, '10:00');
        $payload = [
            'service_id' => $booking->service_id,
            'booking_date' => $booking->booking_date->toDateString(),
            'booking_time' => '11:00',
            'customer_name' => $owner->name,
            'phone' => '0812345678',
        ];

        $this->actingAs(User::factory()->create())
            ->get(route('bookings.edit', $booking))
            ->assertNotFound();
        $this->patch(route('bookings.update', $booking), $payload)->assertNotFound();

        $booking->update(['status' => 'confirmed']);
        $this->actingAs($owner)
            ->get(route('bookings.edit', $booking))
            ->assertForbidden();
        $this->patch(route('bookings.update', $booking), $payload)->assertForbidden();
    }

    public function test_admin_can_manage_services_and_delete_is_blocked_when_service_has_history(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);
        $this->get(route('dashboard'))->assertOk();

        $this->post(route('services.store'), [
            'name' => 'นวดน้ำมันอโรม่า',
            'description' => 'น้ำมันหอมระเหยผ่อนคลาย',
            'duration_minutes' => 60,
            'price' => 500,
            'is_active' => 1,
            'image' => UploadedFile::fake()->image('aroma.jpg'),
        ])->assertRedirect(route('services.index'));

        $service = MassageService::firstOrFail();
        Storage::disk('public')->assertExists($service->image_path);
        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee('นวดน้ำมันอโรม่า')
            ->assertSee('เพิ่มบริการใหม่')
            ->assertSee('edit-service-'.$service->id)
            ->assertSee(route('services.update', $service))
            ->assertSee(route('services.destroy', $service));

        $this->put(route('services.update', $service), [
            'name' => 'นวดอโรม่าพรีเมียม',
            'description' => 'น้ำมันหอมระเหยคุณภาพ',
            'duration_minutes' => 90,
            'price' => 650,
            'is_active' => 1,
        ])->assertRedirect(route('services.index'));
        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'นวดอโรม่าพรีเมียม', 'price' => 650]);

        $user = User::factory()->create();
        $booking = $this->makeBooking($service, $user, '09:00');
        $this->delete(route('services.destroy', $service))->assertSessionHasErrors('service');
        $this->assertDatabaseHas('bookings', ['id' => $booking->id]);

        $unusedService = $this->service(['name' => 'บริการทดลองที่ลบได้']);
        $this->delete(route('services.destroy', $unusedService))
            ->assertRedirect(route('services.index'));
        $this->assertDatabaseMissing('services', ['id' => $unusedService->id]);
    }

    public function test_only_admin_can_access_dashboard_and_confirm_bookings(): void
    {
        $service = $this->service(['duration_minutes' => 60]);
        $user = User::factory()->create();
        $booking = $this->makeBooking($service, $user, '09:00');

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertRedirect(route('home'));
        $this->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed'])
            ->assertRedirect(route('home'));

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee($booking->booking_code)
            ->assertSee($booking->customer_name)
            ->assertSee($booking->phone)
            ->assertSee('รายการจองทั้งหมด');

        $this->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed'])
            ->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);
        $this->patch(route('admin.bookings.update', $booking), ['status' => 'completed'])
            ->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'completed', 'slot_key' => null]);
    }

    public function test_service_seeder_creates_the_five_requested_treatments_idempotently(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->assertDatabaseCount('services', 5);
        $this->assertDatabaseHas('services', ['name' => 'นวดแผนไทย', 'duration_minutes' => 60, 'price' => 300]);
        $this->assertDatabaseHas('services', ['name' => 'นวดน้ำมันอโรม่า', 'duration_minutes' => 60, 'price' => 500]);
        $this->assertDatabaseHas('services', ['name' => 'นวดฝ่าเท้า', 'duration_minutes' => 60, 'price' => 250]);
        $this->assertDatabaseHas('services', ['name' => 'นวดคอบ่าไหล่', 'duration_minutes' => 30, 'price' => 200]);
        $this->assertDatabaseHas('services', ['name' => 'นวดประคบสมุนไพร', 'duration_minutes' => 90, 'price' => 600]);
        $this->seed(\Database\Seeders\DatabaseSeeder::class);
        $this->assertDatabaseCount('services', 5);
    }
}
