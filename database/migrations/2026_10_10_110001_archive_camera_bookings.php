<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bookings') && ! Schema::hasTable('camera_rental_bookings')) {
            Schema::rename('bookings', 'camera_rental_bookings');
        }

        if (! Schema::hasTable('services')) {
            Schema::create('services', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description');
                $table->unsignedSmallInteger('duration_minutes');
                $table->decimal('price', 8, 2);
                $table->string('image_path')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        $bookingsTableExists = Schema::hasTable('bookings');
        if (! $bookingsTableExists) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_code')->unique();
                $table->foreignId('user_id')->nullable();
                $table->foreignId('service_id');
                $table->date('booking_date');
                $table->time('booking_time');
                $table->string('customer_name');
                $table->string('phone', 30);
                $table->text('notes')->nullable();
                $table->unsignedSmallInteger('duration_minutes');
                $table->decimal('price', 8, 2);
                $table->string('status')->default('pending');
                $table->string('slot_key')->nullable()->unique();
                $table->timestamps();
                $table->index(['booking_date', 'status']);
                $table->index(['user_id', 'booking_date']);
                $table->foreign('user_id', 'massage_bookings_user_id_foreign')
                    ->references('id')->on('users')->nullOnDelete();
                $table->foreign('service_id', 'massage_bookings_service_id_foreign')
                    ->references('id')->on('services')->restrictOnDelete();
            });
        } else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('user_id', 'massage_bookings_user_id_foreign')
                    ->references('id')->on('users')->nullOnDelete();
                $table->foreign('service_id', 'massage_bookings_service_id_foreign')
                    ->references('id')->on('services')->restrictOnDelete();
            });
        }

        if (! Schema::hasTable('booking_days')) {
            Schema::create('booking_days', function (Blueprint $table) {
                $table->date('booking_date')->primary();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('booking_days');
        Schema::dropIfExists('services');

        if (Schema::hasTable('camera_rental_bookings') && ! Schema::hasTable('bookings')) {
            Schema::rename('camera_rental_bookings', 'bookings');
        }
    }
};
