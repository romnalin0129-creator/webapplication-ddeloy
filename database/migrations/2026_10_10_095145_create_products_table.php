<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'image_path')) {
                    $table->string('image_path')->nullable();
                }
                if (! Schema::hasColumn('products', 'is_bestseller')) {
                    $table->boolean('is_bestseller')->default(false);
                }
            });

            return;
        }

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('name');
            $table->string('category')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_bestseller')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};