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
        Schema::create('communication_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['call', 'whatsapp', 'note', 'sms'])->default('note');
            $table->string('subject')->nullable();
            $table->text('content');
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->index(['branch_id', 'customer_id']);
        });

        // Add tags and indexes if not existing
        if (!Schema::hasColumn('customers', 'tags')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->json('tags')->nullable()->after('referral_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_logs');

        if (Schema::hasColumn('customers', 'tags')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('tags');
            });
        }
    }
};
