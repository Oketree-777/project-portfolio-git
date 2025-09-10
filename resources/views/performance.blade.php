@extends('layouts.app')

@section('title', 'ผลงานทั้งหมด')

@section('content')
<div class="container">
    @if (count($performance) > 0)
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0 fw-light text-center">ผลงานทั้งหมด</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th scope="col">ลำดับ</th>
                                <th scope="col">ชื่อ</th>
                                <th scope="col">เนื้อหา</th>
                                <th scope="col">สถานะ</th>
                                <th scope="col">แก้ไข</th>
                                <th scope="col">ลบ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($performance as $item)
                                <tr>
                                    <th scope="row">{{ $item->id }}</th>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->content, 30) }}</td>
                                    <td>
                                        @if ($item->status == true)
                                            <a href="{{ route('change', $item->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-check-circle me-1"></i>อนุมัติแล้ว
                                            </a>
                                        @else
                                            <a href="{{ route('change', $item->id) }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-clock me-1"></i>รออนุมัติ
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('edit', $item->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit me-1"></i>แก้ไข
                                        </a>
                                    </td>
                                    <td>
                                        <a 
                                            href="{{ route('delete', $item->id) }}" 
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('คุณต้องการลบผลงาน {{ $item->title }} หรือไม่?')"
                                            >
                                            <i class="fas fa-trash me-1"></i>ลบ
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $performance->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h2 class="text-muted fw-light">ไม่พบผลงาน</h2>
                <p class="text-muted">ยังไม่มีผลงานในระบบ</p>
            </div>
        </div>
    @endif
</div>

<style>
.card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    background: #ffffff;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e5e5e5;
    padding: 1.5rem;
}

.card-header h2 {
    color: #1a1a1a;
    font-weight: 300;
    letter-spacing: -0.5px;
    margin: 0;
}

.card-body {
    padding: 2rem;
}

.table {
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #e5e5e5;
    font-weight: 600;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
    padding: 1rem;
}

.table td {
    border-bottom: 1px solid #f0f0f0;
    color: #666666;
    vertical-align: middle;
    padding: 1rem;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn {
    font-weight: 500;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    border: none;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
}

.btn-success {
    background-color: #22c55e;
    color: #ffffff;
}

.btn-success:hover {
    background-color: #16a34a;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
}

.btn-outline-secondary {
    border-color: #6b7280;
    color: #6b7280;
}

.btn-outline-secondary:hover {
    background-color: #6b7280;
    border-color: #6b7280;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
}

.btn-outline-warning {
    border-color: #f59e0b;
    color: #f59e0b;
}

.btn-outline-warning:hover {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.btn-outline-danger {
    border-color: #ef4444;
    color: #ef4444;
}

.btn-outline-danger:hover {
    background-color: #ef4444;
    border-color: #ef4444;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.text-center.py-5 {
    background: #f8f9fa;
    border-radius: 12px;
    margin: 2rem 0;
}

.text-center.py-5 i {
    color: #cccccc;
}

.text-center.py-5 h2, .text-center.py-5 p {
    color: #666666;
}

/* Pagination Styling */
.pagination {
    margin: 0;
}

.page-link {
    color: #000000;
    border-color: #e5e5e5;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
}

.page-link:hover {
    color: #000000;
    background-color: #f8f9fa;
    border-color: #000000;
}

.page-item.active .page-link {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
}

.page-item.disabled .page-link {
    color: #999999;
    border-color: #e5e5e5;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .table th, .table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection
