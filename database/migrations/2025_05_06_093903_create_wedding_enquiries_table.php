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
        Schema::create('wedding_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string("your_name");
            $table->string("partner_name");
            $table->string("email");
            $table->string("phone");
            $table->string("guest_count");
            $table->string("wed_date");
            $table->longText("add_detail");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wedding_enquiries');
    }
};
