<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Replaced by the complete, upgrade-safe tasks migration.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This legacy migration no longer owns the tasks table.
    }
};
