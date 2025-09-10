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
            // เพิ่ม portfolio_cover สำหรับหน้าปก Portfolio
            if (!Schema::hasColumn('personal_info', 'portfolio_cover')) {
                $table->string('portfolio_cover')->nullable()->after('photo');
            }
            // เพิ่มเฉพาะ portfolio_filename เพราะ portfolio_file มีอยู่แล้ว
            if (!Schema::hasColumn('personal_info', 'portfolio_filename')) {
                $table->string('portfolio_filename')->nullable()->after('portfolio_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_info', function (Blueprint $table) {
            if (Schema::hasColumn('personal_info', 'portfolio_cover')) {
                $table->dropColumn('portfolio_cover');
            }
            if (Schema::hasColumn('personal_info', 'portfolio_filename')) {
                $table->dropColumn('portfolio_filename');
            }
        });
    }
};
