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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('personal_info_id')->constrained('personal_info')->onDelete('cascade');
            $table->integer('rating')->unsigned(); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Ensure one rating per user per portfolio
            $table->unique(['user_id', 'personal_info_id']);
            
            // Indexes for performance
            $table->index(['personal_info_id', 'rating']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
