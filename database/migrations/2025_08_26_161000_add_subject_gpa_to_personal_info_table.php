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
            $table->json('subject_gpa')->nullable()->after('subject_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_info', function (Blueprint $table) {
            $table->dropColumn('subject_gpa');
        });
    }
};
