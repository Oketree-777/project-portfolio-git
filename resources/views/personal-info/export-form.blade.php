@extends('layouts.app')

@section('title', 'ส่งออกข้อมูล PDF')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-file-pdf text-danger"></i> ส่งออกข้อมูล PDF
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('personal-info.export-pdf') }}" method="GET">
                        <!-- ช่วงเวลา -->
                        <div class="mb-3">
                            <label for="date_range" class="form-label">
                                <i class="fas fa-calendar"></i> ช่วงเวลา
                            </label>
                            <select class="form-select" id="date_range" name="date_range">
                                <option value="all" {{ request('date_range') == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>วันนี้</option>
                                <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>7 วันล่าสุด</option>
                                <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>30 วันล่าสุด</option>
                                <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>3 เดือนล่าสุด</option>
                                <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>1 ปีล่าสุด</option>
                                <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>กำหนดเอง</option>
                            </select>
                        </div>

                        <!-- วันที่กำหนดเอง -->
                        <div class="row mb-3" id="custom_date_range" style="display: none;">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>

                        <!-- คณะ -->
                        <div class="mb-3">
                            <label for="faculty" class="form-label">
                                <i class="fas fa-university"></i> คณะ
                            </label>
                            <select class="form-select" id="faculty" name="faculty">
                                <option value="">ทุกคณะ</option>
                                <option value="คณะครุศาสตร์" {{ request('faculty') == 'คณะครุศาสตร์' ? 'selected' : '' }}>คณะครุศาสตร์</option>
                                <option value="คณะวิทยาศาสตร์" {{ request('faculty') == 'คณะวิทยาศาสตร์' ? 'selected' : '' }}>คณะวิทยาศาสตร์</option>
                                <option value="คณะมนุษยศาสตร์และสังคมศาสตร์" {{ request('faculty') == 'คณะมนุษยศาสตร์และสังคมศาสตร์' ? 'selected' : '' }}>คณะมนุษยศาสตร์และสังคมศาสตร์</option>
                                <option value="คณะวิทยาการจัดการ" {{ request('faculty') == 'คณะวิทยาการจัดการ' ? 'selected' : '' }}>คณะวิทยาการจัดการ</option>
                                <option value="คณะเทคโนโลยีอุตสาหกรรม" {{ request('faculty') == 'คณะเทคโนโลยีอุตสาหกรรม' ? 'selected' : '' }}>คณะเทคโนโลยีอุตสาหกรรม</option>
                                <option value="คณะเทคโนโลยีการเกษตร" {{ request('faculty') == 'คณะเทคโนโลยีการเกษตร' ? 'selected' : '' }}>คณะเทคโนโลยีการเกษตร</option>
                                <option value="คณะพยาบาลศาสตร์" {{ request('faculty') == 'คณะพยาบาลศาสตร์' ? 'selected' : '' }}>คณะพยาบาลศาสตร์</option>
                                <option value="บัณฑิตวิทยาลัย" {{ request('faculty') == 'บัณฑิตวิทยาลัย' ? 'selected' : '' }}>บัณฑิตวิทยาลัย</option>
                            </select>
                        </div>

                        <!-- สาขา -->
                        <div class="mb-3">
                            <label for="major" class="form-label">
                                <i class="fas fa-graduation-cap"></i> สาขา
                            </label>
                            <select class="form-select" id="major" name="major">
                                <option value="">ทุกสาขา</option>
                                <!-- จะถูกเติมด้วย JavaScript -->
                            </select>
                        </div>

                        <!-- สถานะ -->
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-check-circle"></i> สถานะ
                            </label>
                            <select class="form-select" id="status" name="status">
                                <option value="">ทุกสถานะ</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>รอการอนุมัติ</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                            </select>
                        </div>

                        <!-- จำนวนรายการต่อหน้า -->
                        <div class="mb-3">
                            <label for="per_page" class="form-label">
                                <i class="fas fa-list"></i> จำนวนรายการต่อหน้า
                            </label>
                            <select class="form-select" id="per_page" name="per_page">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 รายการ</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 รายการ</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 รายการ</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100 รายการ</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                            </select>
                        </div>

                        <!-- ปุ่มดำเนินการ -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('personal-info.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> กลับ
                            </a>
                            <div>
                                <button type="button" class="btn btn-info me-2" onclick="previewData()">
                                    <i class="fas fa-eye"></i> ดูตัวอย่าง
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-file-pdf"></i> ส่งออก PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- แสดงตัวอย่างข้อมูล -->
            <div class="card mt-4" id="preview_card" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-eye"></i> ตัวอย่างข้อมูล
                    </h5>
                </div>
                <div class="card-body">
                    <div id="preview_content">
                        <!-- ข้อมูลตัวอย่างจะถูกแสดงที่นี่ -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ข้อมูลคณะและสาขา
const facultyMajors = {
    'คณะครุศาสตร์': [
        'นาฏศิลป์', 'คณิตศาสตร์', 'การศึกษาปฐมวัย', 'เทคโนโลยีและคอมพิวเตอร์เพื่อการศึกษา',
        'สังคมศึกษา', 'ภาษาอังกฤษ', 'วิทยาศาสตร์ทั่วไป', 'ภาษาไทย', 'ศิลปศึกษา',
        'ดนตรีศึกษา', 'พลศึกษา', 'ฟิสิกส์'
    ],
    'คณะวิทยาศาสตร์': [
        'ภูมิสารสนเทศ', 'เคมี', 'วิทยาศาสตร์สิ่งแวดล้อม', 'สาธารณสุขศาสตร์',
        'สถิติประยุกต์และวิทยาการสารสนเทศ', 'ชีววิทยา', 'เทคโนโลยีสารสนเทศ',
        'วิทยาการคอมพิวเตอร์', 'คณิตศาสตร์', 'วิทยาศาสตร์การกีฬา',
        'การออกแบบแฟชั่นและธุรกิจสิ่งทอ'
    ],
    'คณะมนุษยศาสตร์และสังคมศาสตร์': [
        'การพัฒนาสังคม', 'ภาษาไทย', 'บรรณารักษ์ศาสตร์และสารสนเทศศาสตร์',
        'ภาษาอังกฤษ', 'ภาษาอังกฤษธุรกิจ', 'ดนตรีสากล', 'ศิลปะดิจิทัล',
        'รัฐประศาสนศาสตร์', 'นิติศาสตร์'
    ],
    'คณะวิทยาการจัดการ': [
        'การบัญชี', 'การสื่อสารมวลชน', 'การท่องเที่ยวและการโรงแรม',
        'เศรษฐศาสตร์', 'การเงินและการธนาคาร', 'การจัดการ', 'การตลาด',
        'การบริหารทรัพยากรมนุษย์', 'คอมพิวเตอร์ธุรกิจ'
    ],
    'คณะเทคโนโลยีอุตสาหกรรม': [
        'ศิลปะและการออกแบบ', 'เทคโนโลยีสถาปัตยกรรม', 'วิศวกรรมการจัดการอุตสาหกรรม',
        'เทคโนโลยีวิศวกรรมโยธา', 'เทคโนโลยีวิศวกรรมไฟฟ้า', 'เทคโนโลยีเซรามิกส์และการออกแบบ',
        'เทคโนโลยีไฟฟ้าและอิเล็กทรอนิกส์'
    ],
    'คณะเทคโนโลยีการเกษตร': [
        'เกษตรศาสตร์ กลุ่มวิชานวัตกรรมการผลิตพืช', 'เกษตรศาสตร์ กลุ่มวิชาเทคโนโลยีการเพาะเลี้ยงสัตว์น้ำ',
        'นวัตกรรมอาหารและแปรรูป', 'สัตวศาสตร์'
    ],
    'คณะพยาบาลศาสตร์': [
        'พยาบาลศาสตร์'
    ],
    'บัณฑิตวิทยาลัย': [
        'หลักสูตรและการจัดการเรียนรู้', 'การบริหารการศึกษา', 'วิจัยและประเมินผล', 'ดนตรีศึกษา'
    ]
};

// อัพเดทสาขาตามคณะที่เลือก
document.getElementById('faculty').addEventListener('change', function() {
    const faculty = this.value;
    const majorSelect = document.getElementById('major');
    
    // ล้างตัวเลือกเดิม
    majorSelect.innerHTML = '<option value="">ทุกสาขา</option>';
    
    if (faculty && facultyMajors[faculty]) {
        facultyMajors[faculty].forEach(major => {
            const option = document.createElement('option');
            option.value = major;
            option.textContent = major;
            majorSelect.appendChild(option);
        });
    }
});

// แสดง/ซ่อนวันที่กำหนดเอง
document.getElementById('date_range').addEventListener('change', function() {
    const customDateRange = document.getElementById('custom_date_range');
    if (this.value === 'custom') {
        customDateRange.style.display = 'block';
    } else {
        customDateRange.style.display = 'none';
    }
});

// ดูตัวอย่างข้อมูล
function previewData() {
    // เก็บค่าจากฟอร์ม
    const formData = {
        date_range: document.getElementById('date_range').value,
        start_date: document.getElementById('start_date').value,
        end_date: document.getElementById('end_date').value,
        faculty: document.getElementById('faculty').value,
        major: document.getElementById('major').value,
        status: document.getElementById('status').value,
        per_page: document.getElementById('per_page').value
    };
    
    // สร้าง query string
    const params = new URLSearchParams();
    Object.keys(formData).forEach(key => {
        if (formData[key]) {
            params.append(key, formData[key]);
        }
    });
    
    // แสดง loading
    document.getElementById('preview_content').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> กำลังโหลดข้อมูล...</div>';
    document.getElementById('preview_card').style.display = 'block';
    
    // Debug: แสดงค่าที่ส่ง
    console.log('Sending parameters:', params.toString());
    
    // ส่ง request เพื่อดูตัวอย่าง
    fetch(`{{ route('personal-info.preview-export') }}?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                document.getElementById('preview_content').innerHTML = data.html;
            } else {
                document.getElementById('preview_content').innerHTML = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('preview_content').innerHTML = '<div class="alert alert-danger">เกิดข้อผิดพลาดในการโหลดข้อมูล</div>';
        });
}

// ตั้งค่าเริ่มต้น
document.addEventListener('DOMContentLoaded', function() {
    // ตรวจสอบวันที่กำหนดเอง
    if (document.getElementById('date_range').value === 'custom') {
        document.getElementById('custom_date_range').style.display = 'block';
    }
    
    // ตั้งค่าสาขาตามคณะที่เลือก
    const faculty = document.getElementById('faculty').value;
    if (faculty) {
        const event = new Event('change');
        document.getElementById('faculty').dispatchEvent(event);
    }
});
</script>

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-label {
    font-weight: 500;
}

.btn {
    border-radius: 0.375rem;
}

#preview_card {
    border-left: 4px solid #0d6efd;
}
</style>
@endsection
