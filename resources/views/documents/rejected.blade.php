@extends('layouts.app')

@section('title', 'เอกสารที่ไม่อนุมัติ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-times-circle"></i> เอกสารที่ไม่อนุมัติ
                </h2>
                <div>
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                    <a href="{{ route('documents.pending') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-clock"></i> รอการอนุมัติ
                    </a>
                    <a href="{{ route('documents.approved') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-check"></i> อนุมัติแล้ว
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($documents->count() > 0)
                <div class="row">
                    @foreach($documents as $document)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">{{ $document->title }}</h5>
                                        <span class="badge bg-danger">ไม่อนุมัติ</span>
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

                                    @if($document->rejection_reason)
                                        <div class="mb-3">
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                เหตุผล: {{ Str::limit($document->rejection_reason, 50) }}
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

                                    @if($document->rejecter)
                                        <div class="mt-2">
                                            <small class="text-danger">
                                                <i class="fas fa-times-circle"></i> 
                                                ไม่อนุมัติโดย: {{ $document->rejecter->name }}
                                                ({{ $document->rejected_at->format('d/m/Y H:i') }})
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <a href="{{ route('documents.show', $document) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i> ดู
                                        </a>
                                        @if($document->file_path)
                                            <a href="{{ route('documents.download', $document) }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-download"></i> ดาวน์โหลด
                                            </a>
                                        @endif
                                        <a href="{{ route('documents.edit', $document) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-edit"></i> แก้ไข
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-times-circle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่มีเอกสารที่ไม่อนุมัติ</h5>
                    <p class="text-muted">ยังไม่มีเอกสารที่ถูกไม่อนุมัติ</p>
                    <a href="{{ route('documents.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> กลับไปหน้าหลัก
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
