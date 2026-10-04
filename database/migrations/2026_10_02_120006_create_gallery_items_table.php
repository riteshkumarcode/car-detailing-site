<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->string('title');
            $table->enum('area', ['exterior', 'interior', 'wheels', 'glass', 'paint'])->default('exterior');
            $table->string('problem_description');
            $table->string('service_name');
            $table->string('car_model');
            $table->string('before_image_path')->nullable();
            $table->string('after_image_path')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
