<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->enum('source', ['health_check_form', 'contact_form', 'drive_club', 'manual'])->default('contact_form');
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('make_model')->nullable();
            $table->enum('vehicle_type', ['hatchback', 'sedan', 'suv', 'other'])->nullable();
            $table->json('main_concerns')->nullable();
            $table->date('preferred_date')->nullable();
            $table->enum('preferred_time', ['morning', 'afternoon', 'evening'])->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('plan_interest')->nullable();
            $table->enum('status', ['new', 'contacted', 'converted', 'lost'])->default('new');
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['branch_id', 'source', 'status']);
            $table->index('mobile');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
