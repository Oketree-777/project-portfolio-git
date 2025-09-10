@extends('layouts.app')

@section('title', 'คู่มือการใช้งานเว็บไซต์')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <i class="fas fa-book fa-4x text-dark mb-4"></i>
                <h2 class="mb-4 fw-light">คู่มือการใช้งานเว็บไซต์</h2>
                <p class="lead mb-4 text-secondary">ระบบจัดการเอกสารและคู่มือการใช้งาน</p>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('documents.index') }}" class="btn btn-dark btn-lg">
                        <i class="fas fa-file-alt"></i> เข้าสู่ระบบจัดการเอกสาร
                    </a>
                @endif
            </div>

            <!-- แสดงเอกสารที่อนุมัติแล้ว -->
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4 fw-light">
                        <i class="fas fa-check-circle text-success"></i> เอกสารที่อนุมัติแล้ว
                    </h3>
                    
                    @php
                        $approvedDocuments = \App\Models\Document::with(['user', 'approver'])
                            ->where('status', 'approved')
                            ->orderBy('approved_at', 'desc')
                            ->get();
                    @endphp

                    @if($approvedDocuments->count() > 0)
                        <div class="row">
                            @foreach($approvedDocuments as $document)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 doc-card">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="card-title">{{ $document->title }}</h5>
                                                <span class="badge bg-success">อนุมัติแล้ว</span>
                                            </div>
                                            
                                            <p class="card-text text-muted">
                                                {{ Str::limit($document->content, 100) }}
                                            </p>
                                            
                                            @if($document->file_path)
                                                <div class="mb-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-paperclip"></i> 
                                                        {{ $document->original_filename }}
                                                    </small>
                                                </div>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-user"></i> {{ $document->user->name }}
                                                </small>
                                                <small class="text-muted">
                                                    {{ $document->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>

                                            @if($document->approver)
                                                <div class="mt-2">
                                                    <small class="text-success">
                                                        <i class="fas fa-check-circle"></i> 
                                                        อนุมัติโดย: {{ $document->approver->name }}
                                                        ({{ $document->approved_at->format('d/m/Y H:i') }})
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-footer">
                                            <div class="btn-group w-100" role="group">
                                                <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-dark btn-sm">
                                                    <i class="fas fa-eye"></i> ดู
                                                </a>
                                                @if($document->file_path)
                                                    <a href="{{ route('documents.download', $document) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-download"></i> ดาวน์โหลด
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted fw-light">ยังไม่มีเอกสารที่อนุมัติแล้ว</h5>
                            <p class="text-muted">เอกสารที่อนุมัติแล้วจะแสดงที่นี่</p>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('documents.index') }}" class="btn btn-dark">
                                    <i class="fas fa-file-alt"></i> จัดการเอกสาร
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.doc-card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
}

.doc-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.doc-card .card-body {
    padding: 1.5rem;
}

.doc-card .card-title {
    color: #1a1a1a;
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
}

.doc-card .card-text {
    color: #666666;
    font-size: 0.875rem;
    line-height: 1.5;
}

.doc-card .card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e5e5e5;
    padding: 1rem 1.5rem;
}

.btn-group .btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

.btn-outline-success {
    border-color: #22c55e;
    color: #22c55e;
}

.btn-outline-success:hover {
    background-color: #22c55e;
    border-color: #22c55e;
    color: #ffffff;
}

.badge {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.75rem;
}

.bg-success {
    background-color: #22c55e !important;
}

.text-success {
    color: #166534 !important;
}

.text-center.mb-5 {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem !important;
}

.text-center.mb-5 h2 {
    color: #1a1a1a;
    font-weight: 300;
    letter-spacing: -0.5px;
}

.text-center.mb-5 .lead {
    color: #666666;
    font-weight: 400;
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

h3 {
    color: #1a1a1a;
    font-weight: 400;
    letter-spacing: -0.25px;
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
</style>
@endsection