@extends('layouts.app')

@section('title', 'รายละเอียดข้อมูลส่วนตัว')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">รายละเอียดข้อมูลส่วนตัว</h4>
                    <div>
                        @auth
                            @if(auth()->user()->isAdmin())
                                @if($personalInfo->isPending())
                                    <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                                        <i class="fas fa-check"></i> อนุมัติ
                                    </button>
                                    <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="fas fa-times"></i> ไม่อนุมัติ
                                    </button>
                                @endif
                                @if($personalInfo->isApproved())
                                    <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#cancelApprovalModal">
                                        <i class="fas fa-undo"></i> ยกเลิกการอนุมัติ
                                    </button>
                                @endif
                                <a href="{{ route('personal-info.edit', $personalInfo->id) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit"></i> แก้ไข
                                </a>
                                <a href="{{ route('personal-info.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                                </a>
                            @else
                                <a href="{{ url('/') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                                </a>
                            @endif
                        @else
                            <a href="{{ url('/') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> ย้อนกลับ
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">ข้อมูลส่วนตัว</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ชื่อ-นามสกุล:</strong></td>
                                    <td>{{ $personalInfo->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>อายุ:</strong></td>
                                    <td>{{ $personalInfo->age }} ปี</td>
                                </tr>
                                <tr>
                                    <td><strong>เพศ:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $personalInfo->gender == 'ชาย' ? 'primary' : 'danger' }}">
                                            {{ $personalInfo->gender }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>สถานะ:</strong></td>
                                    <td>{!! $personalInfo->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>จำนวนการเข้าชม:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-eye"></i> <span id="rt-views">{{ number_format($personalInfo->views ?? 0) }}</span> ครั้ง
                                        </span>
                                    </td>
                                </tr>
                                @if($personalInfo->total_ratings > 0)
                                <tr>
                                    <td><strong>คะแนนเฉลี่ย:</strong></td>
                                    <td>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-star"></i> <span id="rt-avg">{{ $personalInfo->average_rating }}</span>/5.0 (<span id="rt-perc">{{ $personalInfo->average_rating_percentage }}</span>%)
                                        </span>
                                        <small class="text-muted ms-2">(<span id="rt-count">{{ $personalInfo->total_ratings }}</span> ครั้ง)</small>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary">ข้อมูลการศึกษา</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>คณะ:</strong></td>
                                    <td>{{ $personalInfo->faculty }}</td>
                                </tr>
                                <tr>
                                    <td><strong>สาขา:</strong></td>
                                    <td>{{ $personalInfo->major }}</td>
                                </tr>
                                <tr>
                                    <td><strong>การศึกษา:</strong></td>
                                    <td>{{ $personalInfo->education }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- ส่วนแสดงผลงาน Portfolio -->
                    @if($personalInfo->portfolio_cover || $personalInfo->portfolio_file)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="text-primary">ผลงาน Portfolio</h5>
                            
                            <!-- หน้าปก Portfolio -->
                            @if($personalInfo->portfolio_cover)
                            <div class="mb-4">
                                <h6 class="text-secondary">หน้าปก Portfolio</h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $personalInfo->portfolio_cover) }}" 
                                         alt="หน้าปก Portfolio" 
                                         class="img-fluid rounded shadow" 
                                         style="max-width: 400px; max-height: 400px;">
                                    <div class="mt-2">
                                        <small class="text-muted">ไฟล์: {{ basename($personalInfo->portfolio_cover) }}</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- ไฟล์ PDF ประกอบ Portfolio -->
                            @if($personalInfo->portfolio_file)
                            <div class="mb-4">
                                <h6 class="text-secondary">ไฟล์ PDF ประกอบ Portfolio</h6>
                                <div class="text-center">
                                    <div class="alert alert-info">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                        <h6>{{ $personalInfo->portfolio_filename ?? basename($personalInfo->portfolio_file) }}</h6>
                                        <p class="mb-2">ไฟล์ PDF ประกอบ Portfolio</p>
                                        <a href="{{ route('personal-info.download', $personalInfo) }}" 
                                           class="btn btn-primary">
                                            <i class="fas fa-download"></i> ดาวน์โหลด PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="text-primary">ผลงาน Portfolio</h5>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> ยังไม่มีผลงาน Portfolio ที่อัปโหลด
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-primary">ข้อมูลระบบ</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>วันที่สร้าง:</strong></td>
                                    <td>{{ $personalInfo->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่อัพเดทล่าสุด:</strong></td>
                                    <td>{{ $personalInfo->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @if($personalInfo->isApproved())
                                <tr>
                                    <td><strong>อนุมัติโดย:</strong></td>
                                    <td>{{ $personalInfo->approvedBy->name ?? 'ไม่ทราบ' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่อนุมัติ:</strong></td>
                                    <td>{{ $personalInfo->approved_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @endif
                                @if($personalInfo->isRejected())
                                <tr>
                                    <td><strong>ไม่อนุมัติโดย:</strong></td>
                                    <td>{{ $personalInfo->rejectedBy->name ?? 'ไม่ทราบ' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>วันที่ไม่อนุมัติ:</strong></td>
                                    <td>{{ $personalInfo->rejected_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>เหตุผล:</strong></td>
                                    <td>{{ $personalInfo->rejection_reason }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Section -->
    @auth
    @if($personalInfo->isApproved())
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-star"></i> ให้คะแนนผลงาน
                    </h5>
                </div>
                <div class="card-body">
                    <div id="rating-section" class="text-center">
                        <div class="rating-stars mb-3">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star star-rating" data-rating="{{ $i }}" style="font-size: 2.2rem; color: #ddd; cursor: pointer; margin: 0 6px;"></i>
                            @endfor
                        </div>
                        <div class="d-inline-flex align-items-center gap-2">
                            <button type="button" class="btn btn-primary px-4" id="submit-rating">
                                <i class="fas fa-star"></i> ให้คะแนน
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="delete-rating" style="display: none;">
                                <i class="fas fa-trash"></i> ลบคะแนน
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endauth
</div>

<!-- Modal สำหรับการอนุมัติ -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">ยืนยันการอนุมัติ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจหรือไม่ที่จะอนุมัติผลงาน Portfolio ของ <strong>{{ $personalInfo->full_name }}</strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form action="{{ route('personal-info.approve', $personalInfo->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">อนุมัติ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับการไม่อนุมัติ -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">ไม่อนุมัติผลงาน Portfolio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('personal-info.reject', $personalInfo->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>คุณแน่ใจหรือไม่ที่จะไม่อนุมัติผลงาน Portfolio ของ <strong>{{ $personalInfo->full_name }}</strong>?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">เหตุผลในการไม่อนุมัติ <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required 
                                  placeholder="กรุณาระบุเหตุผลในการไม่อนุมัติ"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-danger">ไม่อนุมัติ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal สำหรับการยกเลิกการอนุมัติ -->
<div class="modal fade" id="cancelApprovalModal" tabindex="-1" aria-labelledby="cancelApprovalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelApprovalModalLabel">ยืนยันการยกเลิกการอนุมัติ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจหรือไม่ที่จะยกเลิกการอนุมัติผลงาน Portfolio ของ <strong>{{ $personalInfo->full_name }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>คำเตือน:</strong> การยกเลิกการอนุมัติจะทำให้ผลงานกลับไปอยู่ในสถานะ "รอการอนุมัติ"
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <form action="{{ route('personal-info.cancel-approval', $personalInfo->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">ยกเลิกการอนุมัติ</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
// Rating System JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    const submitBtn = document.getElementById('submit-rating');
    const deleteBtn = document.getElementById('delete-rating');
    const commentTextarea = null;
    let selectedRating = 0;
    let currentUserRating = null;

    // Load user's existing rating
    loadUserRating();

    // Star click events
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            updateStars(selectedRating);
        });

        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            updateStars(rating);
        });
    });

    // Reset stars on mouse leave
    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        updateStars(selectedRating);
    });

    // Submit rating
    submitBtn.addEventListener('click', function() {
        if (selectedRating === 0) {
            alert('กรุณาเลือกคะแนน');
            return;
        }

        const comment = null;
        
        fetch(`{{ route('ratings.store', $personalInfo->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                rating: selectedRating,
                comment: comment
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Reload to show updated rating
            } else {
                alert(data.message || 'เกิดข้อผิดพลาดในการให้คะแนน');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาดในการให้คะแนน: ' + error.message);
        });
    });

    // Delete rating
    deleteBtn.addEventListener('click', function() {
        if (confirm('คุณแน่ใจหรือไม่ที่จะลบคะแนนนี้?')) {
            fetch(`{{ route('ratings.destroy', $personalInfo->id) }}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Reload to show updated rating
                } else {
                    alert(data.message || 'เกิดข้อผิดพลาดในการลบคะแนน');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('เกิดข้อผิดพลาดในการลบคะแนน: ' + error.message);
            });
        }
    });

    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    }

    function loadUserRating() {
        fetch(`{{ route('ratings.user', $personalInfo->id) }}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.rating) {
                currentUserRating = data.rating;
                selectedRating = data.rating.rating;
                commentTextarea.value = data.rating.comment || '';
                updateStars(selectedRating);
                deleteBtn.style.display = 'inline-block';
                submitBtn.textContent = 'อัปเดตคะแนน';
            }
        })
        .catch(error => {
            console.error('Error loading user rating:', error);
        });
    }

    // Realtime updater
    function pollRealtimeStats() {
        fetch(`{{ route('ratings.realtime', $personalInfo->id) }}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const viewsEl = document.getElementById('rt-views');
                const avgEl = document.getElementById('rt-avg');
                const percEl = document.getElementById('rt-perc');
                const countEl = document.getElementById('rt-count');
                if (viewsEl) viewsEl.textContent = new Intl.NumberFormat().format(data.views || 0);
                if (avgEl) avgEl.textContent = (data.average_rating ?? 0).toFixed(1);
                if (percEl) percEl.textContent = (data.average_percentage ?? 0).toFixed(1);
                if (countEl) countEl.textContent = data.total_ratings ?? 0;
            }
        })
        .catch(() => {})
        .finally(() => {
            setTimeout(pollRealtimeStats, 5000); // update every 5s
        });
    }

    // start polling
    pollRealtimeStats();
});
</script>
@endsection
