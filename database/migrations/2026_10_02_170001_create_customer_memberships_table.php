<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('membership_plan_id')->nullable()->constrained('membership_plans')->nullOnDelete();
            $table->string('plan_name');
            $table->decimal('price_paid', 10, 2)->default(0.00);
            $table->string('payment_method')->default('cash');
            $table->dateTime('starts_at');
            $table->dateTime('expires_at');
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->json('remaining_services')->nullable();
            $table->json('category_discounts')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'status']);
            $table->index(['vehicle_id', 'status']);
            $table->index('expires_at');
        });

        Schema::create('membership_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('customer_membership_id')->constrained('customer_memberships')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('service_name');
            $table->integer('units_redeemed')->default(1);
            $table->foreignId('performed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('redeemed_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_membership_id']);
            $table->index(['vehicle_id']);
            $table->index('redeemed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_redemptions');
        Schema::dropIfExists('customer_memberships');
    }
};
