@extends('layouts.app')

@section('title', 'สร้างผลงาน')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 fw-light text-center">สร้างผลงานใหม่</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/insert">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">ชื่อผลงาน</label>
                            <input type="text" name="title" class="form-control" placeholder="กรอกชื่อผลงาน">
                        </div>
                        @error('title')
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                        
                        <div class="form-group mb-4">
                            <label for="content" class="form-label">เนื้อหา</label>
                            <textarea name="content" cols="30" rows="5" class="form-control" placeholder="กรอกเนื้อหาผลงาน"></textarea>
                        </div>
                        @error('content')
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                            </div>
                        @enderror
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save me-2"></i>บันทึก
                            </button>
                            <a href="/admin/performance" class="btn btn-outline-dark">
                                <i class="fas fa-arrow-left me-2"></i>ย้อนกลับ
                            </a>
                        </div>
                    </form>
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

.form-label {
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control {
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #000000;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.form-control::placeholder {
    color: #999999;
}

.btn {
    font-weight: 500;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s ease;
    border: none;
}

.btn-dark {
    background-color: #000000;
    color: #ffffff;
}

.btn-dark:hover {
    background-color: #333333;
    transform: translateY(-1px);
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
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.alert {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    padding: 1rem 1.25rem;
    margin-bottom: 1rem;
    font-weight: 500;
}

.alert-danger {
    background-color: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.gap-2 {
    gap: 0.5rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem;
    }
    
    .btn {
        padding: 0.625rem 1.25rem;
    }
}
</style>
@endsection
