@extends('layouts.app')

@section('title', 'ค้นหาผลงาน Portfolio')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">
                            <i class="fas fa-home"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('portfolios.index') }}">
                            <i class="fas fa-folder"></i> ผลงานทั้งหมด
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-search"></i> ค้นหา
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-search"></i> ค้นหาผลงาน Portfolio
                </h2>
                <a href="{{ route('portfolios.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> กลับ
                </a>
            </div>

            <!-- ฟอร์มค้นหา -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> ตัวกรองการค้นหา
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('portfolios.search') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="q" class="form-label">คำค้นหา</label>
                                    <input type="text" class="form-control" id="q" name="q" 
                                           value="{{ $query }}" placeholder="ชื่อ, นามสกุล, คณะ, สาขา">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="faculty" class="form-label">คณะ</label>
                                    <select class="form-select" id="faculty" name="faculty">
                                        <option value="">ทุกคณะ</option>
                                        @foreach($faculties as $facultyOption)
                                            <option value="{{ $facultyOption }}" {{ $faculty == $facultyOption ? 'selected' : '' }}>
                                                {{ $facultyOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="major" class="form-label">สาขา</label>
                                    <select class="form-select" id="major" name="major">
                                        <option value="">ทุกสาขา</option>
                                        @if($faculty && isset($facultyMajors[$faculty]))
                                            @foreach($facultyMajors[$faculty] as $majorOption)
                                                <option value="{{ $majorOption }}" {{ $major == $majorOption ? 'selected' : '' }}>
                                                    {{ $majorOption }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="rating" class="form-label">คะแนนเฉลี่ย</label>
                                    <select class="form-select" id="rating" name="rating">
                                        <option value="">ทุกคะแนน</option>
                                        <option value="5" {{ $rating == '5' ? 'selected' : '' }}>5 ดาว (90-100%)</option>
                                        <option value="4" {{ $rating == '4' ? 'selected' : '' }}>4 ดาวขึ้นไป (70-100%)</option>
                                        <option value="3" {{ $rating == '3' ? 'selected' : '' }}>3 ดาวขึ้นไป (50-100%)</option>
                                        <option value="2" {{ $rating == '2' ? 'selected' : '' }}>2 ดาวขึ้นไป (30-100%)</option>
                                        <option value="1" {{ $rating == '1' ? 'selected' : '' }}>1 ดาวขึ้นไป (10-100%)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> ค้นหา
                            </button>
                            <a href="{{ route('portfolios.search') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> ล้างตัวกรอง
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <script>
            // Dynamic Dropdown สำหรับคณะและสาขา
            document.addEventListener('DOMContentLoaded', function() {
                const facultySelect = document.getElementById('faculty');
                const majorSelect = document.getElementById('major');
                
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
                        'การบัญชี', 'การสื่อสารมวลชน', 'การท่องเที่ยวและการโรงแรม', 'เศรษฐศาสตร์',
                        'การเงินและการธนาคาร', 'การจัดการ', 'การตลาด', 'การบริหารทรัพยากรมนุษย์',
                        'คอมพิวเตอร์ธุรกิจ'
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
                        'หลักสูตรและการจัดการเรียนรู้', 'การบริหารการศึกษา', 'วิจัยและประเมินผล',
                        'ดนตรีศึกษา'
                    ]
                };
                
                if (facultySelect && majorSelect) {
                    facultySelect.addEventListener('change', function() {
                        const selectedFaculty = this.value;
                        
                        // รีเซ็ต dropdown สาขา
                        majorSelect.innerHTML = '<option value="">ทุกสาขา</option>';
                        
                        if (selectedFaculty && facultyMajors[selectedFaculty]) {
                            // เพิ่มสาขาตามคณะที่เลือก
                            facultyMajors[selectedFaculty].forEach(major => {
                                const option = document.createElement('option');
                                option.value = major;
                                option.textContent = major;
                                majorSelect.appendChild(option);
                            });
                        }
                    });
                }
            });
            </script>

            <!-- ผลการค้นหา -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> ผลการค้นหา
                        @if($query || $faculty || $major || $rating)
                            <span class="badge bg-primary ms-2">{{ $portfolios->total() }} รายการ</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($query || $faculty || $major || $rating)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            ค้นหา: 
                            @if($query) <strong>"{{ $query }}"</strong> @endif
                            @if($faculty) <strong>คณะ: {{ $faculty }}</strong> @endif
                            @if($major) <strong>สาขา: {{ $major }}</strong> @endif
                            @if($rating) <strong>คะแนน: {{ $rating }} ดาวขึ้นไป</strong> @endif
                        </div>
                    @endif

                    @if($portfolios->count() > 0)
                        <div class="row">
                            @foreach($portfolios as $portfolio)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        @if($portfolio->portfolio_cover)
                                            <img src="{{ asset('storage/' . $portfolio->portfolio_cover) }}" 
                                                 class="card-img-top" alt="ผลงาน Portfolio" 
                                                 style="height: 200px; object-fit: cover;">
                                        @else
                                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                {{ $portfolio->title }} {{ $portfolio->first_name }} {{ $portfolio->last_name }}
                                            </h6>
                                            <p class="card-text text-muted small">
                                                <i class="fas fa-graduation-cap"></i> 
                                                {{ $portfolio->faculty }} - {{ $portfolio->major }}
                                            </p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt"></i> 
                                                    อนุมัติเมื่อ: {{ $portfolio->approved_at ? $portfolio->approved_at->format('d/m/Y H:i') : 'N/A' }}
                                                </small>
                                            </p>
                                        </div>
                                        
                                        <div class="card-footer bg-transparent">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> อนุมัติแล้ว
                                                </span>
                                                <a href="{{ route('personal-info.show', $portfolio->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> ดูรายละเอียด
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $portfolios->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">
                                @if($query || $faculty || $major)
                                    ไม่พบผลงานที่ตรงกับเงื่อนไขการค้นหา
                                @else
                                    ยังไม่มีผลงาน
                                @endif
                            </h5>
                            <p class="text-muted">
                                @if($query || $faculty || $major)
                                    ลองเปลี่ยนคำค้นหาหรือเงื่อนไขการกรอง
                                @else
                                    ผลงานที่ได้รับการอนุมัติจะแสดงที่นี่
                                @endif
                            </p>
                            @if($query || $faculty || $major)
                                <a href="{{ route('portfolios.search') }}" class="btn btn-primary">
                                    <i class="fas fa-times"></i> ล้างตัวกรอง
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}
</style>
@endsection
