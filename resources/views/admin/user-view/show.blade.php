@extends('layouts.app')

@section('title', 'ผลงานของ ' . $user->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('user-view.index') }}">
                            <i class="fas fa-users"></i> จัดการ User ทั้งหมด
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user"></i> {{ $user->name }}
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-user"></i> ผลงานของ {{ $user->name }}
                    </h2>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('user-view.dashboard', $user->id) }}" 
                       class="btn btn-success">
                        <i class="fas fa-tachometer-alt"></i> ดู Dashboard
                    </a>
                    <a href="{{ route('user-view.edit', $user->id) }}" 
                       class="btn btn-warning">
                        <i class="fas fa-edit"></i> แก้ไขข้อมูล
                    </a>
                    <a href="{{ route('user-view.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <!-- สถิติ -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-folder fa-2x mb-2"></i>
                            <h4>{{ $totalProjects }}</h4>
                            <p class="mb-0">ผลงานทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h4>{{ $approvedProjects }}</h4>
                            <p class="mb-0">อนุมัติแล้ว</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h4>{{ $pendingProjects }}</h4>
                            <p class="mb-0">รออนุมัติ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h4>{{ $rejectedProjects }}</h4>
                            <p class="mb-0">ถูกปฏิเสธ</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- รายการผลงาน -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> รายการผลงานทั้งหมด ({{ $userProjects->total() }} รายการ)
                    </h5>
                </div>
                <div class="card-body">
                    @if($userProjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>คณะ-สาขา</th>
                                        <th>สถานะ</th>
                                        <th>วันที่สร้าง</th>
                                        <th>การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userProjects as $project)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $project->title }} {{ $project->first_name }} {{ $project->last_name }}</strong>
                                            </td>
                                            <td>{{ $project->faculty }} - {{ $project->major }}</td>
                                            <td>
                                                @if($project->status == 'approved')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> อนุมัติแล้ว
                                                    </span>
                                                @elseif($project->status == 'pending')
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock"></i> รออนุมัติ
                                                    </span>
                                                @elseif($project->status == 'rejected')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i> ถูกปฏิเสธ
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $project->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('personal-info.show', $project->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> ดูรายละเอียด
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $userProjects->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ $user->name }} ยังไม่มีผลงาน</h5>
                            <p class="text-muted">ผลงานที่สร้างจะแสดงที่นี่</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
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
