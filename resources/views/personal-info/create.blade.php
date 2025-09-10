@extends('layouts.app')

@section('title', 'เพิ่มข้อมูลส่วนตัว')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header text-center">
                <h2 class="page-title">
                    <i class="fas fa-user-plus text-primary"></i> เพิ่มข้อมูลส่วนตัวใหม่
                </h2>
                <p class="page-subtitle text-muted">กรอกข้อมูลส่วนตัวเพื่อสร้าง Portfolio ของคุณ</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('personal-info.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf

        <!-- ข้อมูลส่วนตัว -->
        <div class="form-section mb-4">
            <div class="section-header">
                <h4 class="section-title">
                    <i class="fas fa-user text-primary"></i> ข้อมูลส่วนตัว
                </h4>
                <p class="section-description">ข้อมูลพื้นฐานของตัวคุณ</p>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="title" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                        <select id="title" class="form-select @error('title') is-invalid @enderror" name="title" required>
                            <option value="">เลือกคำนำหน้า</option>
                            @foreach($titles as $title)
                                <option value="{{ $title }}" {{ old('title') == $title ? 'selected' : '' }}>
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="first_name" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" 
                               name="first_name" value="{{ old('first_name') }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-5 mb-3">
                    <div class="form-group">
                        <label for="last_name" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" 
                               name="last_name" value="{{ old('last_name') }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <div class="form-group">
                        <label for="age" class="form-label">อายุ <span class="text-danger">*</span></label>
                        <input id="age" type="number" class="form-control @error('age') is-invalid @enderror" 
                               name="age" value="{{ old('age') }}" required min="1" max="120">
                        @error('age')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="gender" class="form-label">เพศ <span class="text-danger">*</span></label>
                        <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender" required>
                            <option value="">เลือกเพศ</option>
                            @foreach($genders as $gender)
                                <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                                    {{ $gender }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-7 mb-3">
                    <div class="form-group">
                        <label class="form-label">เลขบัตรประจำตัวประชาชน</label>
                        <div class="national-id-container">
                            @for($i = 0; $i < 13; $i++)
                                <input type="text" class="national-id-input" name="national_id[]" 
                                       maxlength="1" pattern="[0-9]" value="{{ old('national_id.'.$i) }}"
                                       placeholder="0">
                            @endfor
                        </div>
                        @error('national_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ข้อมูลภาษาอังกฤษ -->
        <div class="form-section mb-4">
            <div class="section-header">
                <h4 class="section-title">
                    <i class="fas fa-globe text-primary"></i> ข้อมูลภาษาอังกฤษ
                </h4>
                <p class="section-description">ข้อมูลส่วนตัวเป็นภาษาอังกฤษ (ไม่บังคับ)</p>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="title_en" class="form-label">คำนำหน้า (ภาษาอังกฤษ)</label>
                        <select id="title_en" class="form-select @error('title_en') is-invalid @enderror" name="title_en">
                            <option value="">เลือกคำนำหน้า</option>
                            <option value="MR." {{ old('title_en') == 'MR.' ? 'selected' : '' }}>MR.</option>
                            <option value="Miss" {{ old('title_en') == 'Miss' ? 'selected' : '' }}>Miss</option>
                        </select>
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="first_name_en" class="form-label">ชื่อ (ภาษาอังกฤษ)</label>
                        <input id="first_name_en" type="text" class="form-control @error('first_name_en') is-invalid @enderror" 
                               name="first_name_en" value="{{ old('first_name_en') }}">
                        @error('first_name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-5 mb-3">
                    <div class="form-group">
                        <label for="last_name_en" class="form-label">นามสกุล (ภาษาอังกฤษ)</label>
                        <input id="last_name_en" type="text" class="form-control @error('last_name_en') is-invalid @enderror" 
                               name="last_name_en" value="{{ old('last_name_en') }}">
                        @error('last_name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ข้อมูลการศึกษา -->
        <div class="form-section mb-4">
            <div class="section-header">
                <h4 class="section-title">
                    <i class="fas fa-graduation-cap text-primary"></i> ข้อมูลการศึกษา
                </h4>
                <p class="section-description">ข้อมูลการศึกษาและสถาบัน</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="faculty" class="form-label">คณะ <span class="text-danger">*</span></label>
                        <select id="faculty" class="form-select @error('faculty') is-invalid @enderror" name="faculty" required>
                            <option value="">เลือกคณะ</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty }}" {{ old('faculty') == $faculty ? 'selected' : '' }}>
                                    {{ $faculty }}
                                </option>
                            @endforeach
                        </select>
                        @error('faculty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="major" class="form-label">สาขา <span class="text-danger">*</span></label>
                        <select id="major" class="form-select @error('major') is-invalid @enderror" name="major" required>
                            <option value="">เลือกสาขา</option>
                            @if(old('faculty') && isset($facultyMajors[old('faculty')]))
                                @foreach($facultyMajors[old('faculty')] as $major)
                                    <option value="{{ $major }}" {{ old('major') == $major ? 'selected' : '' }}>
                                        {{ $major }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('major')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="education_level" class="form-label">ระดับการศึกษา</label>
                        <select id="education_level" class="form-select @error('education_level') is-invalid @enderror" name="education_level">
                            <option value="">เลือกระดับการศึกษา</option>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level }}" {{ old('education_level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('education_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="study_plan" class="form-label">แผนการเรียน</label>
                        <select id="study_plan" class="form-select @error('study_plan') is-invalid @enderror" name="study_plan">
                            <option value="">เลือกแผนการเรียน</option>
                            @foreach($studyPlans as $plan)
                                <option value="{{ $plan }}" {{ old('study_plan') == $plan ? 'selected' : '' }}>
                                    {{ $plan }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_plan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="province" class="form-label">จังหวัด</label>
                        <select id="province" class="form-select @error('province') is-invalid @enderror" name="province">
                            <option value="">เลือกจังหวัด</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="institution" class="form-label">สถาบันการศึกษา</label>
                        <input id="institution" type="text" class="form-control @error('institution') is-invalid @enderror" 
                               name="institution" value="{{ old('institution') }}">
                        @error('institution')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="gpa" class="form-label">ผลการเรียนเฉลี่ยตลอดหลักสูตร</label>
                        <input id="gpa" type="number" step="0.01" min="0" max="4" class="form-control @error('gpa') is-invalid @enderror" 
                               name="gpa" value="{{ old('gpa') }}" placeholder="0.00">
                        @error('gpa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- กลุ่มสาระการเรียนรู้ -->
        <div class="form-section mb-4">
            <div class="section-header">
                <h4 class="section-title">
                    <i class="fas fa-book text-primary"></i> กลุ่มสาระการเรียนรู้
                </h4>
                <p class="section-description">เลือกกลุ่มสาระการเรียนรู้ที่เกี่ยวข้อง</p>
            </div>
            
            <div class="row">
                @foreach($subjectGroups as $group)
                    <div class="col-md-6 mb-3">
                        <div class="subject-group-card">
                            <div class="form-check">
                                <input class="form-check-input subject-group-checkbox" type="checkbox" name="subject_groups[]" 
                                       value="{{ $group }}" id="subject_{{ $loop->index }}" 
                                       {{ in_array($group, old('subject_groups', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="subject_{{ $loop->index }}">{{ $group }}</label>
                            </div>
                            <div class="gpa-dropdown mt-2" id="gpa_dropdown_{{ $loop->index }}" style="display: none;">
                                <label for="gpa_{{ $loop->index }}" class="form-label">GPA สำหรับ {{ $group }}</label>
                                <select class="form-select" name="subject_gpa[{{ $group }}]" id="gpa_{{ $loop->index }}">
                                    <option value="">เลือก GPA</option>
                                    @for($gpa = 0.50; $gpa <= 4.00; $gpa += 0.50)
                                        <option value="{{ number_format($gpa, 2) }}" 
                                                {{ old('subject_gpa.'.$group) == number_format($gpa, 2) ? 'selected' : '' }}>
                                            {{ number_format($gpa, 2) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('subject_groups')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- ที่อยู่ -->
        <div class="form-section mb-4">
            <div class="section-header">
                <h4 class="section-title">
                    <i class="fas fa-map-marker-alt text-primary"></i> ที่อยู่
                </h4>
                <p class="section-description">ข้อมูลที่อยู่ปัจจุบัน</p>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="house_number" class="form-label">บ้านเลขที่</label>
                        <input id="house_number" type="text" class="form-control @error('house_number') is-invalid @enderror" 
                               name="house_number" value="{{ old('house_number') }}">
                        @error('house_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="form-group">
                        <label for="village_no" class="form-label">หมู่</label>
                        <input id="village_no" type="text" class="form-control @error('village_no') is-invalid @enderror" 
                               name="village_no" value="{{ old('village_no') }}">
                        @error('village_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="road" class="form-label">ถนน</label>
                        <input id="road" type="text" class="form-control @error('road') is-invalid @enderror" 
                               name="road" value="{{ old('road') }}">
                        @error('road')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="sub_district" class="form-label">ตำบล</label>
                        <input id="sub_district" type="text" class="form-control @error('sub_district') is-invalid @enderror" 
                               name="sub_district" value="{{ old('sub_district') }}">
                        @error('sub_district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="district" class="form-label">อำเภอ</label>
                        <input id="district" type="text" class="form-control @error('district') is-invalid @enderror" 
                               name="district" value="{{ old('district') }}">
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="province_address" class="form-label">จังหวัด (ที่อยู่)</label>
                        <select id="province_address" class="form-select @error('province_address') is-invalid @enderror" name="province_address">
                            <option value="">เลือกจังหวัด</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}" {{ old('province_address') == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="postal_code" class="form-label">รหัสไปรษณีย์</label>
                        <input id="postal_code" type="text" maxlength="5" class="form-control @error('postal_code') is-invalid @enderror" 
                               name="postal_code" value="{{ old('postal_code') }}">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                        <input id="phone" type="text" maxlength="10" class="form-control @error('phone') is-invalid @enderror" 
                               name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="major_code" class="form-label">รหัสสาขาวิชา</label>
                        <input id="major_code" type="text" class="form-control @error('major_code') is-invalid @enderror" 
                               name="major_code" value="{{ old('major_code') }}">
                        @error('major_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="major_name" class="form-label">ชื่อสาขาวิชา</label>
                        <input id="major_name" type="text" class="form-control @error('major_name') is-invalid @enderror" 
                               name="major_name" value="{{ old('major_name') }}">
                        @error('major_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="program" class="form-label">หลักสูตร</label>
                        <input id="program" type="text" class="form-control @error('program') is-invalid @enderror" 
                               name="program" value="{{ old('program') }}">
                        @error('program')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- ไฟล์ Portfolio -->
        <div class="form-section mb-4">
            <div class="section-header">
                <h4 class="section-title">
                    <i class="fas fa-file-upload text-primary"></i> ไฟล์ Portfolio
                </h4>
                <p class="section-description">อัพโหลดไฟล์สำหรับ Portfolio ของคุณ</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="portfolio_cover" class="form-label">หน้าปก Portfolio</label>
                        <input id="portfolio_cover" type="file" class="form-control @error('portfolio_cover') is-invalid @enderror" 
                               name="portfolio_cover" accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> รองรับไฟล์รูปภาพ JPG, JPEG, PNG, GIF ขนาดไม่เกิน 2MB
                        </div>
                        @error('portfolio_cover')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="portfolio_file" class="form-label">ไฟล์ PDF ประกอบ Portfolio</label>
                        <input id="portfolio_file" type="file" class="form-control @error('portfolio_file') is-invalid @enderror" 
                               name="portfolio_file" accept=".pdf">
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> รองรับไฟล์ PDF เท่านั้น ขนาดไม่เกิน 10MB
                        </div>
                        @error('portfolio_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="form-actions text-center">
            <button type="submit" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-save"></i> บันทึกข้อมูล
            </button>
            <a href="{{ route('personal-info.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> ย้อนกลับ
            </a>
        </div>
    </form>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    margin: 0;
}

/* Form Sections */
.form-section {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.section-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.section-description {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Form Controls */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Subject Group Cards */
.subject-group-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.subject-group-card:hover {
    background: #e9ecef;
    border-color: #0d6efd;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-label {
    font-weight: 500;
    color: #495057;
}

/* National ID Input Styling */
.national-id-container {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.national-id-input {
    width: 40px;
    height: 45px;
    text-align: center;
    font-size: 18px;
    font-weight: 600;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    background: #fff;
    transition: all 0.3s ease;
    outline: none;
}

.national-id-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    transform: scale(1.05);
}

.national-id-input::placeholder {
    color: #adb5bd;
    font-size: 14px;
}

/* Form Actions */
.form-actions {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.1rem;
    }
    
    .national-id-container {
        gap: 4px;
    }
    
    .national-id-input {
        width: 35px;
        height: 40px;
        font-size: 16px;
    }
    
    .btn-lg {
        padding: 0.5rem 1.5rem;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 1.5rem;
    }
    
    .form-section {
        padding: 1rem;
    }
    
    .national-id-container {
        gap: 2px;
    }
    
    .national-id-input {
        width: 30px;
        height: 35px;
        font-size: 14px;
    }
    
    .form-actions {
        padding: 1.5rem;
    }
    
    .btn-lg {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Auto-focus next input for national ID
document.querySelectorAll('input[name="national_id[]"]').forEach((input, index) => {
    input.addEventListener('input', function() {
        if (this.value.length === 1 && index < 12) {
            document.querySelectorAll('input[name="national_id[]"]')[index + 1].focus();
        }
    });
    
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
            document.querySelectorAll('input[name="national_id[]"]')[index - 1].focus();
        }
    });
});

// Auto-focus เลขบัตรประชาชน
document.addEventListener('DOMContentLoaded', function() {
    const firstNationalIdInput = document.querySelector('input[name="national_id[]"]');
    if (firstNationalIdInput) {
        firstNationalIdInput.focus();
    }
});

// ฟังก์ชันสำหรับแสดง/ซ่อน dropdown GPA
document.addEventListener('DOMContentLoaded', function() {
    function toggleGpaDropdown(checkbox, index) {
        const dropdown = document.getElementById('gpa_dropdown_' + index);
        if (checkbox.checked) {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
            // รีเซ็ตค่า GPA เมื่อยกเลิกการเลือก
            const gpaSelect = document.getElementById('gpa_' + index);
            gpaSelect.value = '';
        }
    }

    // เพิ่ม event listener สำหรับทุก checkbox
    const checkboxes = document.querySelectorAll('.subject-group-checkbox');
    checkboxes.forEach(function(checkbox, index) {
        // ตรวจสอบสถานะเริ่มต้น
        toggleGpaDropdown(checkbox, index);
        
        // เพิ่ม event listener สำหรับการเปลี่ยนแปลง
        checkbox.addEventListener('change', function() {
            toggleGpaDropdown(this, index);
        });
    });
});

// Dynamic Dropdown สำหรับคณะและสาขา
document.addEventListener('DOMContentLoaded', function() {
    const facultySelect = document.getElementById('faculty');
    const majorSelect = document.getElementById('major');
    
    if (facultySelect && majorSelect) {
        facultySelect.addEventListener('change', function() {
            const selectedFaculty = this.value;
            
            // รีเซ็ต dropdown สาขา
            majorSelect.innerHTML = '<option value="">เลือกสาขา</option>';
            
            if (selectedFaculty) {
                // เรียก API เพื่อดึงข้อมูลสาขาตามคณะ
                fetch(`/api/majors-by-faculty?faculty=${encodeURIComponent(selectedFaculty)}`)
                    .then(response => response.json())
                    .then(majors => {
                        majors.forEach(major => {
                            const option = document.createElement('option');
                            option.value = major;
                            option.textContent = major;
                            majorSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching majors:', error);
                    });
            }
        });
    }
});

// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
@endsection
