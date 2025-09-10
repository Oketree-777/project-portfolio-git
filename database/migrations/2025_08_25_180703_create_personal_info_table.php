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
        Schema::create('personal_info', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // คำนำหน้า (นาย/นางสาว)
            $table->string('first_name'); // ชื่อ
            $table->string('last_name'); // นามสกุล
            $table->integer('age'); // อายุ
            $table->enum('gender', ['ชาย', 'หญิง']); // เพศ
            $table->string('faculty'); // คณะ
            $table->string('major'); // สาขา
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_info');
    }
};
