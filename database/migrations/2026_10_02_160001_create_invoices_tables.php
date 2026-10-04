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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('financial_year', 10)->index();
            $table->unsignedInteger('sequence_number')->index();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('issued_by_user_id')->constrained('users')->cascadeOnDelete();
            
            $table->enum('status', ['issued', 'cancelled'])->default('issued');
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('reissued_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            
            $table->enum('payment_method', ['cash', 'upi', 'card', 'other'])->default('cash');
            $table->string('payment_reference')->nullable();
            $table->text('staff_notes')->nullable();
            $table->json('assigned_staff_ids')->nullable(); // JSON array of user IDs
            
            // Financial calculations
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->enum('discount_type', ['none', 'fixed', 'percentage'])->default('none');
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->string('discount_reason')->nullable();
            
            // GST snapshot
            $table->boolean('is_gst_enabled')->default(false);
            $table->string('gstin')->nullable();
            $table->decimal('cgst_rate', 5, 2)->default(9.00);
            $table->decimal('sgst_rate', 5, 2)->default(9.00);
            $table->decimal('cgst_amount', 10, 2)->default(0.00);
            $table->decimal('sgst_amount', 10, 2)->default(0.00);
            $table->decimal('total_tax', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            
            $table->string('share_token', 64)->unique();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('item_name');
            $table->enum('item_type', ['service', 'add_on', 'custom'])->default('service');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('invoice_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('financial_year', 10);
            $table->unsignedInteger('last_sequence')->default(0);
            $table->timestamps();

            $table->unique(['branch_id', 'financial_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_sequences');
    }
};
