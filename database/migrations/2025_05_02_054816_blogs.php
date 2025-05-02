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
        Schema::create('blogs' , function(Blueprint $table)
        {
            $table->id();
            $table->string("title");
            $table->string("blog_date");
            $table->string("facebook_link")->nullable();
            $table->string("instagram_link")->nullable();
            $table->string("youtube_link")->nullable();
            $table->string("twitter_link")->nullable();
            $table->string("meta_keyword")->nullable();
            $table->string("meta_title")->nullable();
            $table->string("blog_category");
            $table->integer("blog_sorting");
            $table->tinyInteger('status')->default(1);
            $table->string("created_by");
            $table->string("updated_by")->nullable();
            $table->longText("banner_image");
            $table->longText("content");
            $table->longText("meta_description")->nullable();
            $table->string("slug")->unique();
            $table->longText("blog_images");
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
