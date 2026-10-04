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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->string('name');
            $table->string('mobile')->index();
            $table->string('email')->nullable();
            $table->string('area')->nullable();
            $table->string('referral_source')->nullable();
            $table->string('referral_code')->nullable();
            $table->date('date_joined')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'mobile']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
