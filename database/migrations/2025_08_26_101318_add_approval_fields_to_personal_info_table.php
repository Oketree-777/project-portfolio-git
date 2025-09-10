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
        Schema::table('personal_info', function (Blueprint $table) {
            // ฟิลด์การอนุมัติ
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('photo');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('rejection_reason');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            
            // Foreign key constraints
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_info', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'status',
                'rejection_reason', 
                'approved_by',
                'approved_at',
                'rejected_by',
                'rejected_at'
            ]);
        });
    }
};
