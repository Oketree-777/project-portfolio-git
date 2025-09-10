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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // page_view, portfolio_view, download, search, etc.
            $table->string('event_name'); // ชื่อเหตุการณ์
            $table->text('event_data')->nullable(); // ข้อมูลเพิ่มเติม (JSON)
            $table->string('user_agent')->nullable(); // Browser/Device info
            $table->string('ip_address')->nullable(); // IP Address
            $table->string('session_id')->nullable(); // Session ID
            $table->unsignedBigInteger('user_id')->nullable(); // User ID (ถ้ามี)
            $table->unsignedBigInteger('portfolio_id')->nullable(); // Portfolio ID (ถ้าเกี่ยวข้อง)
            $table->string('page_url')->nullable(); // URL ที่เข้าชม
            $table->string('referrer')->nullable(); // มาจากหน้าไหน
            $table->timestamps();
            
            // Indexes
            $table->index(['event_type', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['portfolio_id', 'created_at']);
            $table->index('created_at');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('portfolio_id')->references('id')->on('personal_info')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
