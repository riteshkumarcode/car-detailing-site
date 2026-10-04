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
        Schema::create('health_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Inspector staff

            $table->string('check_number')->unique();
            $table->date('check_date')->index();
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->enum('protection_type', ['none', 'wax', 'sealant', 'ceramic', 'unknown'])->default('unknown');

            $table->json('category_scores');
            $table->json('weights_snapshot');
            $table->json('checklist_data');
            $table->json('recommended_today')->nullable();
            $table->json('recommended_later')->nullable();

            $table->text('technician_notes')->nullable();
            $table->string('share_token', 64)->unique()->index();

            $table->timestamps();

            $table->index(['branch_id', 'check_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_checks');
    }
};
