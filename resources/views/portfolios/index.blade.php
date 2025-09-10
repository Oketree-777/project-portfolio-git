@extends('layouts.app')

@section('title', 'ผลงาน Portfolio ทั้งหมด')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title">
                            <i class="fas fa-folder text-primary"></i> ผลงาน Portfolio ทั้งหมด
                        </h2>
                        <p class="page-subtitle text-muted">ดูผลงาน Portfolio ที่ได้รับการอนุมัติและจัดหมวดหมู่</p>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('portfolios.search') }}" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> ค้นหา
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">
                            <i class="fas fa-home"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-folder"></i> ผลงานทั้งหมด
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <!-- Navigation Tabs -->
            <div class="content-section mb-4">
                <div class="section-header">
                    <h4 class="section-title">
                        <i class="fas fa-list text-primary"></i> หมวดหมู่การแสดงผล
                    </h4>
                    <p class="section-description">เลือกดูผลงานตามหมวดหมู่ที่ต้องการ</p>
                </div>
                
                <ul class="nav nav-tabs custom-tabs" id="portfolioTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved"
                                type="button" role="tab" aria-controls="approved" aria-selected="true">
                            <i class="fas fa-check-circle"></i> ผลงานที่ได้รับอนุมัติ
                            <span class="badge bg-success ms-2">{{ $approvedPortfolios->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories"
                                type="button" role="tab" aria-controls="categories" aria-selected="false">
                            <i class="fas fa-tags"></i> หมวดหมู่
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="portfolioTabsContent">
                <!-- Approved Portfolios Tab -->
                <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                    <div class="content-section">
                        <div class="section-header">
                            <h4 class="section-title">
                                <i class="fas fa-check-circle text-success"></i> ผลงานที่ได้รับอนุมัติ
                            </h4>
                            <p class="section-description">ผลงาน Portfolio ที่ผ่านการอนุมัติแล้ว</p>
                        </div>
                        
                        @if($approvedPortfolios->count() > 0)
                            <div class="row">
                                @foreach($approvedPortfolios as $portfolio)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="portfolio-card">
                                            <div class="card-image">
                                                @if($portfolio->portfolio_cover)
                                                    <img src="{{ asset('storage/' . $portfolio->portfolio_cover) }}" 
                                                         class="img-fluid" alt="ผลงาน Portfolio">
                                                @else
                                                    <div class="placeholder-image">
                                                        <i class="fas fa-image"></i>
                                                        <p>ไม่มีรูปภาพ</p>
                                                    </div>
                                                @endif
                                                <div class="card-overlay">
                                                    <a href="{{ route('personal-info.show', $portfolio->id) }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i> ดูรายละเอียด
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <div class="card-content">
                                                <h5 class="card-title">
                                                    {{ $portfolio->title }} {{ $portfolio->first_name }} {{ $portfolio->last_name }}
                                                </h5>
                                                
                                                <div class="card-info">
                                                    <div class="info-item">
                                                        <i class="fas fa-graduation-cap text-primary"></i>
                                                        <span>{{ $portfolio->faculty }} - {{ $portfolio->major }}</span>
                                                    </div>
                                                    <div class="info-item">
                                                        <i class="fas fa-calendar-alt text-muted"></i>
                                                        <span>อนุมัติเมื่อ: {{ $portfolio->approved_at ? $portfolio->approved_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="card-status">
                                                    <span class="status-badge approved">
                                                        <i class="fas fa-check-circle"></i> อนุมัติแล้ว
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                                <h5>ยังไม่มีผลงาน</h5>
                                <p>ผลงานที่ได้รับการอนุมัติจะแสดงที่นี่</p>
                                <a href="{{ route('personal-info.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> เพิ่มผลงานใหม่
                                </a>
                            </div>
                        @endif
                        
                        <!-- Pagination -->
                        @if($approvedPortfolios->hasPages())
                            <div class="pagination-wrapper mt-4">
                                {{ $approvedPortfolios->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Categories Tab -->
                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    <!-- Faculty Categories -->
                    <div class="content-section mb-4">
                        <div class="section-header">
                            <h4 class="section-title">
                                <i class="fas fa-university text-primary"></i> หมวดหมู่คณะ
                            </h4>
                            <p class="section-description">ดูผลงานตามคณะต่างๆ</p>
                        </div>
                        
                        @if($faculties->count() > 0)
                            <div class="row">
                                @foreach($faculties as $faculty)
                                    @php
                                        $count = \App\Models\PersonalInfo::where('status', 'approved')
                                            ->where('faculty', $faculty)
                                            ->count();
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="category-card faculty-card">
                                            <div class="category-icon">
                                                <i class="fas fa-university"></i>
                                            </div>
                                            <div class="category-content">
                                                <h5 class="category-title">{{ $faculty }}</h5>
                                                <div class="category-count">
                                                    <span class="count-badge">{{ $count }}</span>
                                                    <span class="count-text">ผลงาน</span>
                                                </div>
                                                <a href="{{ route('portfolios.by-faculty', $faculty) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> ดูผลงาน
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <h5>ยังไม่มีหมวดหมู่คณะ</h5>
                                <p>หมวดหมู่คณะจะแสดงที่นี่เมื่อมีข้อมูล</p>
                            </div>
                        @endif
                    </div>

                    <!-- Major Categories -->
                    <div class="content-section">
                        <div class="section-header">
                            <h4 class="section-title">
                                <i class="fas fa-graduation-cap text-primary"></i> หมวดหมู่สาขา
                            </h4>
                            <p class="section-description">ดูผลงานตามสาขาวิชาต่างๆ</p>
                        </div>
                        
                        @if($majors->count() > 0)
                            <div class="row">
                                @foreach($majors as $major)
                                    @php
                                        $count = \App\Models\PersonalInfo::where('status', 'approved')
                                            ->where('major', $major)
                                            ->count();
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="category-card major-card">
                                            <div class="category-icon">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="category-content">
                                                <h5 class="category-title">{{ $major }}</h5>
                                                <div class="category-count">
                                                    <span class="count-badge">{{ $count }}</span>
                                                    <span class="count-text">ผลงาน</span>
                                                </div>
                                                <a href="{{ route('portfolios.by-major', $major) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-eye"></i> ดูผลงาน
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h5>ยังไม่มีหมวดหมู่สาขา</h5>
                                <p>หมวดหมู่สาขาจะแสดงที่นี่เมื่อมีข้อมูล</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

/* Breadcrumb */
.breadcrumb {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 0;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

.breadcrumb-item.active {
    color: #495057;
    font-weight: 600;
}

/* Content Sections */
.content-section {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.section-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.section-description {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Custom Tabs */
.custom-tabs {
    border-bottom: 2px solid #e9ecef;
    margin-bottom: 0;
}

.custom-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 1rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    background: transparent;
}

.custom-tabs .nav-link:hover {
    color: #0d6efd;
    border-bottom-color: #dee2e6;
    background: #f8f9fa;
}

.custom-tabs .nav-link.active {
    color: #0d6efd;
    background: transparent;
    border-bottom-color: #0d6efd;
}

.custom-tabs .badge {
    font-size: 0.75rem;
}

/* Portfolio Cards */
.portfolio-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.portfolio-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.portfolio-card:hover .card-image img {
    transform: scale(1.05);
}

.placeholder-image {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    color: #6c757d;
}

.placeholder-image i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.placeholder-image p {
    margin: 0;
    font-size: 0.9rem;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.portfolio-card:hover .card-overlay {
    opacity: 1;
}

.card-content {
    padding: 1.5rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.card-info {
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.info-item i {
    width: 20px;
    margin-right: 0.5rem;
}

.info-item span {
    color: #6c757d;
}

.card-status {
    display: flex;
    justify-content: flex-start;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.approved {
    background: #d1e7dd;
    color: #0f5132;
}

/* Category Cards */
.category-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 1rem;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.faculty-card:hover {
    border-color: #0d6efd;
}

.major-card:hover {
    border-color: #198754;
}

.category-icon {
    margin-bottom: 1rem;
}

.category-icon i {
    font-size: 2.5rem;
    color: #6c757d;
}

.faculty-card .category-icon i {
    color: #0d6efd;
}

.major-card .category-icon i {
    color: #198754;
}

.category-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
}

.category-count {
    margin-bottom: 1rem;
}

.count-badge {
    background: #0d6efd;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-weight: 600;
    font-size: 1.1rem;
}

.major-card .count-badge {
    background: #198754;
}

.count-text {
    display: block;
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    margin-bottom: 1.5rem;
}

.empty-icon i {
    font-size: 4rem;
    color: #dee2e6;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .page-header {
        padding: 1.5rem;
    }
    
    .content-section {
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.1rem;
    }
    
    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .custom-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 1rem;
    }
    
    .content-section {
        padding: 1rem;
    }
    
    .card-content {
        padding: 1rem;
    }
    
    .category-card {
        padding: 1rem;
    }
    
    .custom-tabs .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
}
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ตรวจสอบ URL hash เพื่อเปิดแท็บที่เหมาะสม
        const hash = window.location.hash;
        if (hash === '#categories') {
            // เปิดแท็บหมวดหมู่
            const categoriesTab = new bootstrap.Tab(document.getElementById('categories-tab'));
            categoriesTab.show();
        }
        
        // เพิ่ม event listener สำหรับการเปลี่ยนแท็บ
        const tabs = document.querySelectorAll('[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                // อัพเดท URL hash เมื่อเปลี่ยนแท็บ
                if (event.target.id === 'categories-tab') {
                    window.location.hash = '#categories';
                } else if (event.target.id === 'approved-tab') {
                    window.location.hash = '';
                }
            });
        });
    });
    </script>
    @endsection
