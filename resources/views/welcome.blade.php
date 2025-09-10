@extends('layouts.app')

@section('title', 'หน้าแรก - Portfolio Online')

@section('content')
<!-- Quick Actions -->
<div class="row mb-5">
    <div class="col-12">
        <h3 class="section-title mb-4 fw-light">
            <i class="fas fa-bolt"></i> การดำเนินการด่วน
        </h3>
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="quick-action-card text-center">
                    <div class="quick-action-icon bg-dark">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <h5 class="mt-3">เพิ่มผลงาน</h5>
                    <p class="text-muted">สร้าง Portfolio ใหม่</p>
                    <a href="{{ route('create') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-arrow-right"></i> เริ่มต้น
                    </a>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="quick-action-card text-center">
                    <div class="quick-action-icon bg-success">
                        <i class="fas fa-search text-white"></i>
                    </div>
                    <h5 class="mt-3">ค้นหาผลงาน</h5>
                    <p class="text-muted">ค้นหาผลงานที่ต้องการ</p>
                    <a href="{{ route('portfolios.index') }}" class="btn btn-outline-dark btn-sm">
                        <i class="fas fa-arrow-right"></i> ค้นหา
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Main Content Tabs -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="portfolioTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved"
                                type="button" role="tab" aria-controls="approved" aria-selected="true">
                            <i class="fas fa-check-circle"></i> ผลงานที่ได้รับอนุมัติ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{ route('portfolios.index') }}" role="tab">
                            <i class="fas fa-folder"></i> ดูผลงานทั้งหมด
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" 
                                type="button" role="tab" aria-controls="categories" aria-selected="false">
                            <i class="fas fa-tags"></i> หมวดหมู่
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" 
                                type="button" role="tab" aria-controls="recent" aria-selected="false">
                            <i class="fas fa-clock"></i> เพิ่งอัพเดท
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body">
                <div class="tab-content" id="portfolioTabsContent">
                    <!-- Tab 1: Approved Portfolios -->
                    <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                        <div class="row">
                            @if($approvedPortfolios->count() > 0)
                                @foreach ($approvedPortfolios as $portfolio)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="portfolio-card h-100">
                                            <div class="portfolio-image">
                                                @if($portfolio->portfolio_cover)
                                                    <img src="{{ asset('storage/' . $portfolio->portfolio_cover) }}" 
                                                         class="img-fluid" alt="ผลงาน Portfolio">
                                                @else
                                                    <div class="placeholder-image">
                                                        <i class="fas fa-image fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="portfolio-overlay">
                                                    <a href="{{ route('personal-info.show', $portfolio->id) }}" 
                                                       class="btn btn-light btn-sm">
                                                        <i class="fas fa-eye"></i> ดูรายละเอียด
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <div class="portfolio-content">
                                                <h5 class="portfolio-title">
                                                    {{ $portfolio->title }} {{ $portfolio->first_name }} {{ $portfolio->last_name }}
                                                </h5>
                                                <p class="portfolio-info">
                                                    <i class="fas fa-graduation-cap"></i> 
                                                    {{ $portfolio->faculty }} - {{ $portfolio->major }}
                                                </p>
                                                <p class="portfolio-date">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt"></i> 
                                                        อนุมัติเมื่อ: {{ $portfolio->approved_at ? $portfolio->approved_at->format('d/m/Y H:i') : 'N/A' }}
                                                    </small>
                                                </p>
                                                <p class="portfolio-views">
                                                    <small class="text-primary">
                                                        <i class="fas fa-eye"></i> 
                                                        เข้าชม: {{ number_format($portfolio->views ?? 0) }} ครั้ง
                                                    </small>
                                                </p>
                                                @if($portfolio->total_ratings > 0)
                                                <p class="portfolio-rating">
                                                    <small class="text-warning">
                                                        <i class="fas fa-star"></i> 
                                                        คะแนน: {{ $portfolio->average_rating }}/5.0 ({{ $portfolio->average_rating_percentage }}%)
                                                    </small>
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted fw-light">ยังไม่มีผลงานที่ได้รับอนุมัติ</h5>
                                        <p class="text-muted">ผลงานที่ได้รับการอนุมัติจะแสดงที่นี่</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Pagination for Approved Portfolios -->
                        @if($approvedPortfolios->count() > 0)
                            <div class="d-flex justify-content-center mt-4">
                                {{ $approvedPortfolios->links() }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Tab 2: Categories -->
                    <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                        <div class="row">
                            @foreach($facultyStats as $faculty)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="category-card">
                                        <div class="category-icon bg-light">
                                            <i class="fas fa-university text-dark"></i>
                                        </div>
                                        <div class="category-content">
                                            <h6 class="category-title">{{ $faculty->faculty }}</h6>
                                            <p class="category-count">{{ $faculty->count }} ผลงาน</p>
                                            <a href="{{ route('faculty', $faculty->faculty) }}" class="btn btn-outline-dark btn-sm">
                                                <i class="fas fa-eye"></i> ดูผลงาน
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tab 3: Recent Updates -->
                    <div class="tab-pane fade" id="recent" role="tabpanel" aria-labelledby="recent-tab">
                        <div class="row">
                            @if($recentPortfolios->count() > 0)
                                @foreach ($recentPortfolios as $portfolio)
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="portfolio-card h-100">
                                            <div class="portfolio-image">
                                                @if($portfolio->portfolio_cover)
                                                    <img src="{{ asset('storage/' . $portfolio->portfolio_cover) }}" 
                                                         class="img-fluid" alt="ผลงาน Portfolio">
                                                @else
                                                    <div class="placeholder-image">
                                                        <i class="fas fa-image fa-3x text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="portfolio-overlay">
                                                    <a href="{{ route('personal-info.show', $portfolio->id) }}" 
                                                       class="btn btn-light btn-sm">
                                                        <i class="fas fa-eye"></i> ดูรายละเอียด
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <div class="portfolio-content">
                                                <h5 class="portfolio-title">
                                                    {{ $portfolio->title }} {{ $portfolio->first_name }} {{ $portfolio->last_name }}
                                                </h5>
                                                <p class="portfolio-info">
                                                    <i class="fas fa-graduation-cap"></i> 
                                                    {{ $portfolio->faculty }} - {{ $portfolio->major }}
                                                </p>
                                                <p class="portfolio-date">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt"></i> 
                                                        อัพเดทเมื่อ: {{ $portfolio->updated_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </p>
                                                <p class="portfolio-views">
                                                    <small class="text-primary">
                                                        <i class="fas fa-eye"></i> 
                                                        เข้าชม: {{ number_format($portfolio->views ?? 0) }} ครั้ง
                                                    </small>
                                                </p>
                                                @if($portfolio->total_ratings > 0)
                                                <p class="portfolio-rating">
                                                    <small class="text-warning">
                                                        <i class="fas fa-star"></i> 
                                                        คะแนน: {{ $portfolio->average_rating }}/5.0 ({{ $portfolio->average_rating_percentage }}%)
                                                    </small>
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted fw-light">ยังไม่มีผลงานที่อัพเดทล่าสุด</h5>
                                        <p class="text-muted">ผลงานที่อัพเดทล่าสุดจะแสดงที่นี่</p>
                                    </div>
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
/* Quick Actions */
.section-title {
    color: #1a1a1a;
    font-weight: 300;
    letter-spacing: -0.25px;
}

.quick-action-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.quick-action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.quick-action-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.5rem;
}

.quick-action-card h5 {
    color: #1a1a1a;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.quick-action-card p {
    color: #666666;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

/* Portfolio Cards */
.portfolio-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.portfolio-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.portfolio-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f8f9fa;
}

.portfolio-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.portfolio-card:hover .portfolio-image img {
    transform: scale(1.05);
}

.placeholder-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #cccccc;
}

.portfolio-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.portfolio-card:hover .portfolio-overlay {
    opacity: 1;
}

.portfolio-content {
    padding: 1.5rem;
}

.portfolio-title {
    color: #1a1a1a;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.portfolio-info {
    color: #666666;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.portfolio-date {
    margin-bottom: 0;
}

/* Category Cards */
.category-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.category-icon {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.25rem;
}

.category-title {
    color: #1a1a1a;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.category-count {
    color: #666666;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

/* Navigation Tabs */
.nav-tabs {
    border-bottom: 1px solid #e5e5e5;
}

.nav-tabs .nav-link {
    border: none;
    color: #666666;
    font-weight: 500;
    padding: 1rem 1.25rem;
    transition: all 0.2s ease;
    border-radius: 0;
}

.nav-tabs .nav-link:hover {
    color: #000000;
    background-color: #f8f9fa;
    border-color: transparent;
}

.nav-tabs .nav-link.active {
    color: #000000;
    background-color: transparent;
    border-bottom: 2px solid #000000;
    font-weight: 600;
    margin-bottom: -2px;
    position: relative;
    z-index: 1;
}

/* Buttons */
.btn-dark {
    background-color: #000000;
    border-color: #000000;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-dark:hover {
    background-color: #333333;
    border-color: #333333;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-outline-dark {
    border-color: #000000;
    color: #000000;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-dark:hover {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-light {
    background-color: #ffffff;
    border-color: #ffffff;
    color: #000000;
    font-weight: 500;
}

.btn-light:hover {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
    color: #000000;
}

/* Card Styling */
.card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    background: #ffffff;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e5e5e5;
    padding: 0;
    overflow: hidden;
}

.card-body {
    padding: 1.5rem;
}

/* Text Colors */
.text-muted {
    color: #666666 !important;
}

.text-dark {
    color: #1a1a1a !important;
}

/* Background Colors */
.bg-light {
    background-color: #f8f9fa !important;
}

.bg-dark {
    background-color: #000000 !important;
}

.bg-success {
    background-color: #22c55e !important;
}

/* Responsive */
@media (max-width: 768px) {
    .quick-action-card {
        padding: 1.5rem 1rem;
    }
    
    .portfolio-content {
        padding: 1rem;
    }
    
    .category-card {
        padding: 1rem;
    }
}
</style>
@endsection
