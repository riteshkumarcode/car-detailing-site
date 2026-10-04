<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->foreignId('service_category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description');
            $table->longText('full_description')->nullable();
            $table->string('target_problem')->nullable();
            $table->string('who_its_for')->nullable();
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->decimal('price_hatchback', 10, 2)->nullable();
            $table->decimal('price_sedan', 10, 2)->nullable();
            $table->decimal('price_suv', 10, 2)->nullable();
            $table->decimal('price_other', 10, 2)->nullable();
            $table->boolean('is_price_on_inspection')->default(false);
            $table->json('whats_included')->nullable();
            $table->json('process_steps')->nullable();
            $table->json('faqs')->nullable();
            $table->json('add_ons')->nullable();
            $table->string('image_path')->nullable();
            $table->string('before_image_path')->nullable();
            $table->string('after_image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_home')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'service_category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
