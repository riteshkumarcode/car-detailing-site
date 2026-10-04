<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('vehicle_model');
            $table->string('service_availed')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('review_text');
            $table->string('location')->default('Jammu');
            $table->string('photo_path')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
