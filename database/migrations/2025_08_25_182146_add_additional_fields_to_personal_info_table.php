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
            // ข้อมูลส่วนตัวเพิ่มเติม
            $table->string('first_name_en')->nullable(); // ชื่อภาษาอังกฤษ
            $table->string('last_name_en')->nullable(); // นามสกุลภาษาอังกฤษ
            
            // ข้อมูลการศึกษา
            $table->string('education_level')->nullable(); // ระดับการศึกษา
            $table->string('study_plan')->nullable(); // แผนการเรียน
            $table->string('institution')->nullable(); // สถาบันการศึกษา
            $table->string('province')->nullable(); // จังหวัด
            $table->decimal('gpa', 3, 2)->nullable(); // ผลการเรียนเฉลี่ย
            $table->json('subject_groups')->nullable(); // กลุ่มสาระการเรียนรู้
            
            // ข้อมูลบัตรประชาชน
            $table->string('national_id', 13)->nullable(); // เลขบัตรประจำตัวประชาชน
            
            // ที่อยู่
            $table->string('house_number')->nullable(); // บ้านเลขที่
            $table->string('village_no')->nullable(); // หมู่
            $table->string('road')->nullable(); // ถนน
            $table->string('sub_district')->nullable(); // ตำบล
            $table->string('district')->nullable(); // อำเภอ
            $table->string('province_address')->nullable(); // จังหวัด (ที่อยู่)
            $table->string('postal_code', 5)->nullable(); // รหัสไปรษณีย์
            $table->string('phone', 10)->nullable(); // เบอร์โทรศัพท์
            
            // ข้อมูลการสมัคร
            $table->string('major_code')->nullable(); // รหัสสาขาวิชา
            $table->string('major_name')->nullable(); // ชื่อสาขาวิชา
            $table->string('program')->nullable(); // หลักสูตร
            
            // ข้อมูลการชำระเงิน
            $table->string('receipt_book_no')->nullable(); // เล่มที่
            $table->string('receipt_no')->nullable(); // เลขที่
            $table->decimal('amount', 10, 2)->nullable(); // จำนวนเงิน
            
            // ข้อมูลการตรวจสอบ
            $table->json('documents')->nullable(); // หลักฐานการสมัคร
            $table->string('verifier_name')->nullable(); // ชื่อผู้ตรวจหลักฐาน
            $table->string('recipient_name')->nullable(); // ชื่อผู้รับเงิน
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_info', function (Blueprint $table) {
            $table->dropColumn([
                'first_name_en', 'last_name_en', 'education_level', 'study_plan',
                'institution', 'province', 'gpa', 'subject_groups', 'national_id',
                'house_number', 'village_no', 'road', 'sub_district', 'district',
                'province_address', 'postal_code', 'phone', 'major_code', 'major_name',
                'program', 'receipt_book_no', 'receipt_no', 'amount', 'documents',
                'verifier_name', 'recipient_name'
            ]);
        });
    }
};
