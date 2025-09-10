@extends('layouts.app')
@section('title')
    {{ $performance->title }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 fw-light">{{ $performance->title }}</h2>
                </div>
                <div class="card-body">
                    <div class="content-section">
                        <p class="content-text">{{ $performance->content }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="/admin/performance" class="btn btn-outline-dark">
                            <i class="fas fa-arrow-left me-2"></i>กลับไปหน้าผลงาน
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

.content-section {
    margin-bottom: 2rem;
}

.content-text {
    color: #666666;
    font-size: 1rem;
    line-height: 1.7;
    margin: 0;
}

.btn-outline-dark {
    border-color: #000000;
    color: #000000;
    font-weight: 500;
    transition: all 0.2s ease;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
}

.btn-outline-dark:hover {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
    
    .card-header {
        padding: 1rem;
    }
    
    .content-text {
        font-size: 0.95rem;
    }
}
</style>
@endsection
