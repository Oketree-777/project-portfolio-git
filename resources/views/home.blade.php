@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0 fw-light">{{ __('Dashboard') }} - ยินดีต้อนรับ {{ Auth::user()->name }}</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- สถิติของ User -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-dark text-white">
                                <div class="card-body text-center">
                                    <h5 class="fw-light">ผลงานทั้งหมด</h5>
                                    <h3 class="fw-bold">{{ $totalProjects ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="fw-light">อนุมัติแล้ว</h5>
                                    <h3 class="fw-bold">{{ $approvedProjects ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5 class="fw-light">รอการอนุมัติ</h5>
                                    <h3 class="fw-bold">{{ $pendingProjects ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5 class="fw-light">ไม่อนุมัติ</h5>
                                    <h3 class="fw-bold">{{ $rejectedProjects ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ปุ่มดำเนินการ -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="{{ route('personal-info.create') }}" class="btn btn-dark btn-lg w-100">
                                <i class="fas fa-plus"></i> สร้างผลงานใหม่
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ url('/') }}" class="btn btn-outline-dark btn-lg w-100">
                                <i class="fas fa-eye"></i> ดูผลงานทั้งหมด
                            </a>
                        </div>
                    </div>

                    <!-- รายการผลงานของ User -->
                    @if(isset($userProjects) && $userProjects->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 fw-light">ผลงานของฉัน</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>ชื่อผลงาน</th>
                                                <th>คณะ/สาขา</th>
                                                <th>สถานะ</th>
                                                <th>วันที่สร้าง</th>
                                                <th>การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userProjects as $project)
                                                <tr>
                                                    <td>{{ $project->title }} {{ $project->first_name }} {{ $project->last_name }}</td>
                                                    <td>{{ $project->faculty }} - {{ $project->major }}</td>
                                                    <td>{!! $project->status_badge !!}</td>
                                                    <td>{{ $project->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>
                                                        @if(Auth::user()->isAdmin())
                                                            <a href="{{ route('personal-info.show', $project->id) }}" class="btn btn-sm btn-outline-dark">
                                                                <i class="fas fa-eye"></i> ดู
                                                            </a>
                                                        @else
                                                            <a href="{{ route('personal-info.edit-my', $project->id) }}" class="btn btn-sm btn-outline-warning">
                                                                <i class="fas fa-edit"></i> แก้ไข
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $userProjects->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted fw-light">ยังไม่มีผลงาน</h5>
                            <p class="text-muted">เริ่มต้นสร้างผลงานแรกของคุณได้เลย</p>
                            <a href="{{ route('personal-info.create') }}" class="btn btn-dark">
                                <i class="fas fa-plus"></i> สร้างผลงานใหม่
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card .card-body {
    padding: 1.5rem;
}

.stat-card h5 {
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.stat-card h3 {
    font-size: 2rem;
    margin: 0;
}

.btn-lg {
    padding: 1rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-outline-dark {
    border-color: #000000;
    color: #000000;
}

.btn-outline-dark:hover {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
}

.btn-outline-warning {
    border-color: #f59e0b;
    color: #f59e0b;
}

.btn-outline-warning:hover {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: #ffffff;
}

.table th {
    font-weight: 600;
    color: #1a1a1a;
    border-bottom: 2px solid #e5e5e5;
    padding: 1rem;
}

.table td {
    padding: 1rem;
    color: #666666;
    border-bottom: 1px solid #f0f0f0;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e5e5e5;
    padding: 1.25rem 1.5rem;
}

.card-header h4, .card-header h5 {
    color: #1a1a1a;
    margin: 0;
}

.text-center.py-5 {
    background: #f8f9fa;
    border-radius: 12px;
    margin: 2rem 0;
}

.text-center.py-5 i {
    color: #cccccc;
}

.text-center.py-5 h5, .text-center.py-5 p {
    color: #666666;
}
</style>
@endsection
