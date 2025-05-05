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
        Schema::create('control_sliders', function (Blueprint $table) {
            $table->id();
            $table->string("image");
            $table->string("heading_top");
            $table->string("heading_bottom");
            $table->string("slide_status");
            $table->string("slide_sorting");
            $table->string("created_by");
            $table->string("updated_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_sliders');
    }
};
