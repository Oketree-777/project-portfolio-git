@extends('layouts.app')

@section('title', 'ยินดีต้อนรับ - Portfolio Online')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center">
                <i class="fas fa-briefcase fa-5x text-dark mb-4"></i>
                <h1 class="display-4 mb-3 fw-light">ยินดีต้อนรับสู่ PORTFOLIO ONLINE</h1>
                <p class="lead mb-4 text-secondary">ระบบจัดการผลงานออนไลน์สำหรับนักศึกษาและผู้สนใจ</p>
            </div>
        </div>
    </div>

    <!-- Featured Portfolios -->
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4 fw-light">
                <i class="fas fa-star text-warning"></i> 
                ผลงานแนะนำ
            </h3>
            
            @php
                $featuredPortfolios = \App\Models\PersonalInfo::where('status', 'approved')
                    ->orderBy('created_at', 'desc')
                    ->limit(6)
                    ->get();
            @endphp

            @if($featuredPortfolios->count() > 0)
                <div class="row">
                    @foreach($featuredPortfolios as $portfolio)
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
                                           class="btn btn-dark btn-sm">
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
                                            <i class="fas fa-graduation-cap text-dark"></i>
                                            <span>{{ $portfolio->faculty }} - {{ $portfolio->major }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar-alt text-muted"></i>
                                            <span>อนุมัติเมื่อ: {{ $portfolio->approved_at ? $portfolio->approved_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-eye text-primary"></i>
                                            <span>เข้าชม: {{ number_format($portfolio->views ?? 0) }} ครั้ง</span>
                                        </div>
                                        @if($portfolio->total_ratings > 0)
                                        <div class="info-item">
                                            <i class="fas fa-star text-warning"></i>
                                            <span>คะแนน: {{ $portfolio->average_rating }}/5.0 ({{ $portfolio->average_rating_percentage }}%)</span>
                                        </div>
                                        @endif
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
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted fw-light">ยังไม่มีผลงานที่แนะนำ</h5>
                    <p class="text-muted">ผลงานที่ได้รับการอนุมัติแล้วจะแสดงที่นี่</p>
                </div>
            @endif
        </div>
    </div>

</div>

<style>
/* Portfolio Cards */
.portfolio-card {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.portfolio-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f8f9fa;
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
    color: #999999;
}

.placeholder-image i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    color: #cccccc;
}

.placeholder-image p {
    margin: 0;
    font-size: 0.875rem;
    color: #999999;
}

.card-overlay {
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

.portfolio-card:hover .card-overlay {
    opacity: 1;
}

.card-content {
    padding: 1.5rem;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1a1a1a;
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
    font-size: 0.875rem;
}

.info-item i {
    width: 20px;
    margin-right: 0.5rem;
    color: #666666;
}

.info-item span {
    color: #666666;
}

.card-status {
    display: flex;
    justify-content: flex-start;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.approved {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
}

/* Hero Section */
.display-4 {
    font-weight: 300;
    letter-spacing: -1px;
    color: #000000;
}

.lead {
    font-weight: 400;
    color: #666666;
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-content {
        padding: 1rem;
    }
    
    .card-title {
        font-size: 1rem;
    }
    
    .info-item {
        font-size: 0.8rem;
    }
    
    .display-4 {
        font-size: 2.5rem;
    }
}
</style>
@endsection
