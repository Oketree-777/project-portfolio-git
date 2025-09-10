@extends('layouts.app')

@section('title', 'Dashboard ของ ' . $user->name)

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
                    <li class="breadcrumb-item">
                        <a href="{{ route('user-view.show', $user->id) }}">
                            <i class="fas fa-user"></i> {{ $user->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-tachometer-alt"></i> Dashboard ของ {{ $user->name }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-user"></i> {{ $user->email }} | 
                        <i class="fas fa-calendar"></i> สมัครเมื่อ: {{ $user->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('user-view.show', $user->id) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-list"></i> ดูผลงานทั้งหมด
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
                            <h3>{{ $totalProjects }}</h3>
                            <p class="mb-0">ผลงานทั้งหมด</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h3>{{ $approvedProjects }}</h3>
                            <p class="mb-0">อนุมัติแล้ว</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h3>{{ $pendingProjects }}</h3>
                            <p class="mb-0">รออนุมัติ</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <i class="fas fa-times-circle fa-2x mb-2"></i>
                            <h3>{{ $rejectedProjects }}</h3>
                            <p class="mb-0">ถูกปฏิเสธ</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ปุ่มดำเนินการ -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-cogs"></i> การดำเนินการ
                            </h5>
                            <div class="btn-group" role="group">
                                <a href="{{ route('create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> สร้างผลงานใหม่
                                </a>
                                <a href="{{ route('user-view.show', $user->id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> ดูผลงานทั้งหมด
                                </a>
                                <a href="{{ route('doc') }}" class="btn btn-info">
                                    <i class="fas fa-book"></i> คู่มือการใช้งาน
                                </a>
                                <a href="{{ route('stat') }}" class="btn btn-warning me-2">
                                    <i class="fas fa-chart-bar"></i> สถิติ
                                </a>
                                <a href="{{ route('personal-info.export-form') }}" class="btn btn-secondary">
                                    <i class="fas fa-file-pdf"></i> ส่งออก PDF
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ผลงานล่าสุด -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-clock"></i> ผลงานล่าสุด
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($userProjects->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ชื่อ-นามสกุล</th>
                                                <th>คณะ-สาขา</th>
                                                <th>สถานะ</th>
                                                <th>วันที่สร้าง</th>
                                                <th>การดำเนินการ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userProjects->take(5) as $project)
                                                <tr>
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
                                                            <i class="fas fa-eye"></i> ดู
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($userProjects->count() > 5)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('user-view.show', $user->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-list"></i> ดูผลงานทั้งหมด
                                        </a>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-4">
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

.btn-group .btn {
    margin-right: 5px;
}
</style>
@endsection
