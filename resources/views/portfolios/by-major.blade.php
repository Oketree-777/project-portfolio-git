@extends('layouts.app')

@section('title', 'ผลงานสาขา ' . $major)

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
                        <i class="fas fa-graduation-cap"></i> {{ $major }}
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>
                        <i class="fas fa-graduation-cap text-success"></i> ผลงานสาขา {{ $major }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-folder"></i> {{ $totalInMajor }} ผลงาน
                    </p>
                </div>
                <div class="btn-group" role="group">
                    <a href="{{ route('portfolios.search') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> ค้นหา
                    </a>
                    <a href="{{ route('portfolios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <!-- คณะที่มีสาขานี้ -->
            @if($facultiesWithMajor->count() > 0)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-university"></i> คณะที่มีสาขา {{ $major }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($facultiesWithMajor as $faculty)
                                        @php
                                            $count = \App\Models\PersonalInfo::where('status', 'approved')
                                                ->where('faculty', $faculty)
                                                ->where('major', $major)
                                                ->count();
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card border-primary h-100">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-university fa-2x text-primary mb-2"></i>
                                                    <h6 class="card-title">{{ $faculty }}</h6>
                                                    <p class="card-text">
                                                        <span class="badge bg-primary fs-6">{{ $count }} ผลงาน</span>
                                                    </p>
                                                    <a href="{{ route('portfolios.by-faculty', $faculty) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i> ดูผลงาน
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- รายการผลงาน -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> รายการผลงานสาขา {{ $major }} ({{ $portfolios->total() }} รายการ)
                    </h5>
                </div>
                <div class="card-body">
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
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">ยังไม่มีผลงานในสาขา {{ $major }}</h5>
                            <p class="text-muted">ผลงานที่ได้รับการอนุมัติจะแสดงที่นี่</p>
                            <a href="{{ route('portfolios.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> กลับไปดูผลงานทั้งหมด
                            </a>
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
