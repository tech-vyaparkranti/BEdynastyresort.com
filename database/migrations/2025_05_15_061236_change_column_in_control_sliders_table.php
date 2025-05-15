<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('control_sliders', function (Blueprint $table) {
            $table->string("heading_top")->nullable(true)->change();
            $table->string("heading_bottom")->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('control_sliders', function (Blueprint $table) {
            $table->string("heading_top");
            $table->string("heading_bottom");
        });
    }
};
