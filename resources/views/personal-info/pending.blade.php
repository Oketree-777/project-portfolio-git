@extends('layouts.app')

@section('title', 'รอการอนุมัติ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-clock text-warning"></i> รอการอนุมัติ
                    </h4>
                    <div>
                        <a href="{{ route('personal-info.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-list"></i> ทั้งหมด
                        </a>
                        <a href="{{ route('personal-info.approved') }}" class="btn btn-success me-2">
                            <i class="fas fa-check"></i> อนุมัติแล้ว
                        </a>
                        <a href="{{ route('personal-info.rejected') }}" class="btn btn-danger me-2">
                            <i class="fas fa-times"></i> ไม่อนุมัติ
                        </a>
                        <a href="{{ route('personal-info.export-form') }}" class="btn btn-secondary">
                            <i class="fas fa-file-pdf"></i> ส่งออก PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($pendingInfos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-warning">
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>คณะ</th>
                                        <th>สาขา</th>
                                        <th>วันที่สร้าง</th>
                                        <th>การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingInfos as $index => $personalInfo)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $personalInfo->full_name }}</strong>
                                            </td>
                                            <td>{{ $personalInfo->faculty }}</td>
                                            <td>{{ $personalInfo->major }}</td>
                                            <td>{{ $personalInfo->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('personal-info.show', $personalInfo->id) }}" 
                                                       class="btn btn-sm btn-info" title="ดูข้อมูล">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            title="อนุมัติ" 
                                                            onclick="approvePortfolio({{ $personalInfo->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            title="ไม่อนุมัติ" 
                                                            onclick="rejectPortfolio({{ $personalInfo->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $pendingInfos->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-3x text-warning mb-3"></i>
                            <h5 class="text-muted">ไม่มีรายการรอการอนุมัติ</h5>
                            <p class="text-muted">ทุกผลงานได้รับการอนุมัติแล้ว</p>
                            <a href="{{ route('personal-info.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> กลับไปหน้าหลัก
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">ไม่อนุมัติผลงาน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">เหตุผลในการไม่อนุมัติ <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required 
                                  placeholder="กรุณาระบุเหตุผลในการไม่อนุมัติผลงานนี้"></textarea>
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

<script>
function approvePortfolio(portfolioId) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะอนุมัติผลงานนี้?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/personal-info/${portfolioId}/approve`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectPortfolio(portfolioId) {
    document.getElementById('rejectionForm').action = `/personal-info/${portfolioId}/reject`;
    const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
    modal.show();
}

document.getElementById('rejectionForm').addEventListener('submit', function(e) {
    const reason = document.getElementById('rejection_reason').value.trim();
    if (!reason) {
        e.preventDefault();
        alert('กรุณาระบุเหตุผลในการไม่อนุมัติ');
        return false;
    }
});
</script>
@endsection
