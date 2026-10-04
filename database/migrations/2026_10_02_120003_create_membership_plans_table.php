<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2);
            $table->string('billing_frequency')->default('yearly');
            $table->unsignedInteger('duration_days')->default(365);
            $table->json('included_services')->nullable();
            $table->json('category_discounts')->nullable();
            $table->json('features')->nullable();
            $table->text('benefits_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
