@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> แก้ไขผลงาน Portfolio
                    </h4>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('personal-info.update', $personalInfo->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- ข้อมูลส่วนตัว -->
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">คำนำหน้า <span class="text-danger">*</span></label>
                            <select name="title" id="title" class="form-control @error('title') is-invalid @enderror" required>
                                <option value="">เลือกคำนำหน้า</option>
                                @foreach($titles as $title)
                                    <option value="{{ $title }}" {{ old('title', $personalInfo->title) == $title ? 'selected' : '' }}>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                           value="{{ old('first_name', $personalInfo->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name" class="form-label">นามสกุล <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                           value="{{ old('last_name', $personalInfo->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">คำนำหน้า (ภาษาอังกฤษ)</label>
                                    <select name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror">
                                        <option value="">เลือกคำนำหน้า</option>
                                        <option value="MR." {{ old('title_en', $personalInfo->title_en) == 'MR.' ? 'selected' : '' }}>MR.</option>
                                        <option value="Miss" {{ old('title_en', $personalInfo->title_en) == 'Miss' ? 'selected' : '' }}>Miss</option>
                                    </select>
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="first_name_en" class="form-label">ชื่อ (ภาษาอังกฤษ)</label>
                                    <input type="text" name="first_name_en" id="first_name_en" class="form-control @error('first_name_en') is-invalid @enderror" 
                                           value="{{ old('first_name_en', $personalInfo->first_name_en) }}">
                                    @error('first_name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="last_name_en" class="form-label">นามสกุล (ภาษาอังกฤษ)</label>
                                    <input type="text" name="last_name_en" id="last_name_en" class="form-control @error('last_name_en') is-invalid @enderror" 
                                           value="{{ old('last_name_en', $personalInfo->last_name_en) }}">
                                    @error('last_name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="age" class="form-label">อายุ <span class="text-danger">*</span></label>
                                    <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror" 
                                           value="{{ old('age', $personalInfo->age) }}" required min="1" max="120">
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">เพศ <span class="text-danger">*</span></label>
                                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                        <option value="">เลือกเพศ</option>
                                        @foreach($genders as $gender)
                                            <option value="{{ $gender }}" {{ old('gender', $personalInfo->gender) == $gender ? 'selected' : '' }}>
                                                {{ $gender }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">เลขบัตรประจำตัวประชาชน</label>
                                    <div class="national-id-container">
                                        @for($i = 0; $i < 13; $i++)
                                            <input type="text" class="national-id-input" name="national_id[]" 
                                                   maxlength="1" pattern="[0-9]" value="{{ old('national_id.'.$i, $personalInfo->national_id[$i] ?? '') }}"
                                                   placeholder="0">
                                        @endfor
                                    </div>
                                    @error('national_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ข้อมูลการศึกษา -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="faculty" class="form-label">คณะ <span class="text-danger">*</span></label>
                                    <select name="faculty" id="faculty" class="form-control @error('faculty') is-invalid @enderror" required>
                                        <option value="">เลือกคณะ</option>
                                        @foreach($faculties as $faculty)
                                            <option value="{{ $faculty }}" {{ old('faculty', $personalInfo->faculty) == $faculty ? 'selected' : '' }}>
                                                {{ $faculty }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('faculty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="major" class="form-label">สาขา <span class="text-danger">*</span></label>
                                    <select name="major" id="major" class="form-control @error('major') is-invalid @enderror" required>
                                        <option value="">เลือกสาขา</option>
                                        @if(old('faculty', $personalInfo->faculty) && isset($facultyMajors[old('faculty', $personalInfo->faculty)]))
                                            @foreach($facultyMajors[old('faculty', $personalInfo->faculty)] as $major)
                                                <option value="{{ $major }}" {{ old('major', $personalInfo->major) == $major ? 'selected' : '' }}>
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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="education_level" class="form-label">ระดับการศึกษา</label>
                                    <select name="education_level" id="education_level" class="form-control @error('education_level') is-invalid @enderror">
                                        <option value="">เลือกระดับการศึกษา</option>
                                        @foreach($educationLevels as $level)
                                            <option value="{{ $level }}" {{ old('education_level', $personalInfo->education_level) == $level ? 'selected' : '' }}>
                                                {{ $level }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('education_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="study_plan" class="form-label">แผนการเรียน</label>
                                    <select name="study_plan" id="study_plan" class="form-control @error('study_plan') is-invalid @enderror">
                                        <option value="">เลือกแผนการเรียน</option>
                                        @foreach($studyPlans as $plan)
                                            <option value="{{ $plan }}" {{ old('study_plan', $personalInfo->study_plan) == $plan ? 'selected' : '' }}>
                                                {{ $plan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('study_plan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="institution" class="form-label">สถาบันการศึกษา</label>
                                    <input type="text" name="institution" id="institution" class="form-control @error('institution') is-invalid @enderror" 
                                           value="{{ old('institution', $personalInfo->institution) }}">
                                    @error('institution')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="province" class="form-label">จังหวัด</label>
                                    <select name="province" id="province" class="form-control @error('province') is-invalid @enderror">
                                        <option value="">เลือกจังหวัด</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province }}" {{ old('province', $personalInfo->province) == $province ? 'selected' : '' }}>
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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gpa" class="form-label">ผลการเรียนเฉลี่ยตลอดหลักสูตร</label>
                                    <input type="number" step="0.01" min="0" max="4" name="gpa" id="gpa" class="form-control @error('gpa') is-invalid @enderror" 
                                           value="{{ old('gpa', $personalInfo->gpa) }}" placeholder="0.00">
                                    @error('gpa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- กลุ่มสาระการเรียนรู้ -->
                        <div class="form-group mb-3">
                            <label class="form-label">กลุ่มสาระการเรียนรู้</label>
                            <div class="row">
                                @foreach($subjectGroups as $group)
                                    <div class="col-md-6 mb-2">
                                        <div class="subject-group-card">
                                            <div class="form-check">
                                                <input class="form-check-input subject-group-checkbox" type="checkbox" name="subject_groups[]" 
                                                       value="{{ $group }}" id="subject_{{ $loop->index }}" 
                                                       {{ in_array($group, old('subject_groups', $personalInfo->subject_groups ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="subject_{{ $loop->index }}">{{ $group }}</label>
                                            </div>
                                            <div class="gpa-dropdown mt-2" id="gpa_dropdown_{{ $loop->index }}" style="display: none;">
                                                <label for="gpa_{{ $loop->index }}" class="form-label">GPA สำหรับ {{ $group }}</label>
                                                <select class="form-control" name="subject_gpa[{{ $group }}]" id="gpa_{{ $loop->index }}">
                                                    <option value="">เลือก GPA</option>
                                                    @for($gpa = 0.50; $gpa <= 4.00; $gpa += 0.50)
                                                        <option value="{{ number_format($gpa, 2) }}" 
                                                                {{ old('subject_gpa.'.$group, $personalInfo->subject_gpa[$group] ?? '') == number_format($gpa, 2) ? 'selected' : '' }}>
                                                            {{ number_format($gpa, 2) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ข้อมูลติดต่อ -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="text" maxlength="10" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $personalInfo->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="house_number" class="form-label">บ้านเลขที่</label>
                                    <input type="text" name="house_number" id="house_number" class="form-control @error('house_number') is-invalid @enderror" 
                                           value="{{ old('house_number', $personalInfo->house_number) }}">
                                    @error('house_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="village_no" class="form-label">หมู่ที่</label>
                                    <input type="text" name="village_no" id="village_no" class="form-control @error('village_no') is-invalid @enderror" 
                                           value="{{ old('village_no', $personalInfo->village_no) }}">
                                    @error('village_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="road" class="form-label">ถนน</label>
                                    <input type="text" name="road" id="road" class="form-control @error('road') is-invalid @enderror" 
                                           value="{{ old('road', $personalInfo->road) }}">
                                    @error('road')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="sub_district" class="form-label">ตำบล/แขวง</label>
                                    <input type="text" name="sub_district" id="sub_district" class="form-control @error('sub_district') is-invalid @enderror" 
                                           value="{{ old('sub_district', $personalInfo->sub_district) }}">
                                    @error('sub_district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="district" class="form-label">อำเภอ/เขต</label>
                                    <input type="text" name="district" id="district" class="form-control @error('district') is-invalid @enderror" 
                                           value="{{ old('district', $personalInfo->district) }}">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="postal_code" class="form-label">รหัสไปรษณีย์</label>
                                    <input type="text" maxlength="5" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" 
                                           value="{{ old('postal_code', $personalInfo->postal_code) }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Portfolio Files -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="portfolio_cover" class="form-label">หน้าปก Portfolio <span class="text-danger">*</span></label>
                                    <input type="file" name="portfolio_cover" id="portfolio_cover" class="form-control @error('portfolio_cover') is-invalid @enderror" accept="image/*">
                                    @if($personalInfo->portfolio_cover)
                                        <small class="form-text text-muted">ไฟล์ปัจจุบัน: {{ basename($personalInfo->portfolio_cover) }}</small>
                                    @endif
                                    @error('portfolio_cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="portfolio_file" class="form-label">ไฟล์ Portfolio (PDF)</label>
                                    <input type="file" name="portfolio_file" id="portfolio_file" class="form-control @error('portfolio_file') is-invalid @enderror" accept=".pdf">
                                    @if($personalInfo->portfolio_file)
                                        <small class="form-text text-muted">ไฟล์ปัจจุบัน: {{ basename($personalInfo->portfolio_file) }}</small>
                                    @endif
                                    @error('portfolio_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                            </button>
                            <a href="{{ route('personal-info.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ย้อนกลับ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* National ID Inputs */
.national-id-container {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
}

.national-id-input {
    width: 40px;
    height: 45px;
    text-align: center;
    font-size: 1.1rem;
    font-weight: 600;
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.national-id-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
}

.national-id-input.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.national-id-input::placeholder {
    color: #adb5bd;
    font-size: 14px;
}

/* Subject Group Cards */
.subject-group-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1rem;
    transition: all 0.3s ease;
}

.subject-group-card:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

/* Responsive Design */
@media (max-width: 768px) {
    .national-id-container {
        gap: 2px;
    }
    
    .national-id-input {
        width: 30px;
        height: 35px;
        font-size: 14px;
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

    // ฟังก์ชันสำหรับแสดง/ซ่อน dropdown GPA
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

    // Dynamic Dropdown สำหรับคณะและสาขา
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
</script>
@endsection
