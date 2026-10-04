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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('registration_number')->index();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('variant')->nullable();
            $table->enum('vehicle_type', ['hatchback', 'sedan', 'suv', 'other'])->default('hatchback');
            $table->string('colour')->nullable();
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'registration_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
