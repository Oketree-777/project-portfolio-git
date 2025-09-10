@extends('layouts.app')

@section('title', 'ผลงานคณะ' . $faculty)

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
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-university"></i> {{ $faculty }}
                    </li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-light">
                    <i class="fas fa-university text-dark"></i> ผลงานคณะ {{ $faculty }}
                </h2>
                <span class="badge bg-dark fs-6">
                    <i class="fas fa-folder"></i> {{ $portfolios->total() }} ผลงาน
                </span>
            </div>
            
            @if($portfolios->count() > 0)
                <div class="row">
                    @foreach ($portfolios as $portfolio)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 faculty-card">
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
                                    <h5 class="card-title">
                                        {{ $portfolio->title }} {{ $portfolio->first_name }} {{ $portfolio->last_name }}
                                    </h5>
                                    <p class="card-text text-muted">
                                        <i class="fas fa-graduation-cap"></i> 
                                        {{ $portfolio->faculty }} - {{ $portfolio->major }}
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt"></i> 
                                            อนุมัติเมื่อ: {{ $portfolio->approved_at ? $portfolio->approved_at->format('d/m/Y H:i') : 'N/A' }}
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-primary">
                                            <i class="fas fa-eye"></i> 
                                            เข้าชม: {{ number_format($portfolio->views ?? 0) }} ครั้ง
                                        </small>
                                    </p>
                                    @if($portfolio->total_ratings > 0)
                                    <p class="card-text">
                                        <small class="text-warning">
                                            <i class="fas fa-star"></i> 
                                            คะแนน: {{ $portfolio->average_rating }}/5.0 ({{ $portfolio->average_rating_percentage }}%)
                                        </small>
                                    </p>
                                    @endif
                                </div>
                                
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> อนุมัติแล้ว
                                        </span>
                                        <a href="{{ route('personal-info.show', $portfolio->id) }}" 
                                           class="btn btn-sm btn-outline-dark">
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
                    <h5 class="text-muted fw-light">ยังไม่มีผลงานในคณะ {{ $faculty }}</h5>
                    <p class="text-muted">ผลงานที่ได้รับการอนุมัติจะแสดงที่นี่</p>
                    <a href="{{ url('/') }}" class="btn btn-dark">
                        <i class="fas fa-arrow-left"></i> กลับไปหน้าแรก
                    </a>
                </div>
            @endif
            
            <!-- Pagination -->
            @if($portfolios->hasPages())
                <div class="pagination-wrapper mt-4">
                    {{ $portfolios->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.faculty-card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
}

.faculty-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.faculty-card .card-body {
    padding: 1.5rem;
}

.faculty-card .card-title {
    color: #1a1a1a;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.faculty-card .card-text {
    color: #666666;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.faculty-card .card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e5e5e5;
    padding: 1rem 1.5rem;
}

.breadcrumb {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    margin-bottom: 2rem;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #666666;
    font-weight: 500;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #000000;
}

.breadcrumb-item.active {
    color: #1a1a1a;
    font-weight: 600;
}

h2 {
    color: #1a1a1a;
    font-weight: 300;
    letter-spacing: -0.5px;
}

.badge {
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.875rem;
}

.bg-dark {
    background-color: #000000 !important;
}

.bg-success {
    background-color: #22c55e !important;
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

.card-img-top {
    background-color: #f8f9fa;
}

.card-img-top i {
    color: #cccccc;
}
</style>
@endsection
