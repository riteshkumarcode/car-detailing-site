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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->string('booking_number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('service_name');
            $table->enum('vehicle_type', ['hatchback', 'sedan', 'suv', 'other'])->default('hatchback');
            $table->decimal('price', 10, 2)->default(0);
            $table->date('booking_date')->index();
            $table->string('booking_time', 10);
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->unsignedInteger('slots_count')->default(1);
            $table->string('name');
            $table->string('mobile')->index();
            $table->string('email')->nullable();
            $table->string('registration_number')->index();
            $table->string('make_model')->nullable();
            $table->enum('status', [
                'new',
                'confirmed',
                'arrived',
                'inspection',
                'in_service',
                'completed',
                'cancelled',
                'no_show'
            ])->default('new')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['branch_id', 'booking_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
