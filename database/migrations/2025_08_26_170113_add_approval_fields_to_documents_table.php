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
        Schema::table('documents', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('user_id');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('rejection_reason');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['status', 'rejection_reason', 'approved_by', 'approved_at', 'rejected_by', 'rejected_at']);
        });
    }
};
