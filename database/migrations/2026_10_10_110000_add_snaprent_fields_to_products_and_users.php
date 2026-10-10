<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('name');
            $table->string('type')->nullable()->after('category');
            $table->json('colors')->nullable();
            $table->json('accessories')->nullable();
            $table->json('gallery_images')->nullable();
            $table->decimal('deposit', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'type', 'colors', 'accessories', 'gallery_images', 'deposit', 'shipping_fee']);
        });
    }
};
