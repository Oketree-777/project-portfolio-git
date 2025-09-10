@extends('layouts.app')

@section('title', 'เอกสารรอการอนุมัติ')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-clock"></i> เอกสารรอการอนุมัติ
                </h2>
                <div>
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                    <a href="{{ route('documents.approved') }}" class="btn btn-success btn-sm me-2">
                        <i class="fas fa-check"></i> อนุมัติแล้ว
                    </a>
                    <a href="{{ route('documents.rejected') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-times"></i> ไม่อนุมัติ
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
                            <div class="card h-100 border-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">{{ $document->title }}</h5>
                                        <span class="badge bg-warning">รอการอนุมัติ</span>
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
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100 mb-2" role="group">
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
                                    
                                    <div class="btn-group w-100" role="group">
                                        <form action="{{ route('documents.approve', $document) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm w-50">
                                                <i class="fas fa-check"></i> อนุมัติ
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm w-50" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $document->id }}">
                                            <i class="fas fa-times"></i> ไม่อนุมัติ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal สำหรับไม่อนุมัติ -->
                        <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $document->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectModalLabel{{ $document->id }}">ไม่อนุมัติเอกสาร</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('documents.reject', $document) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="rejection_reason" class="form-label">เหตุผลในการไม่อนุมัติ <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="submit" class="btn btn-danger">ไม่อนุมัติ</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่มีเอกสารที่รอการอนุมัติ</h5>
                    <p class="text-muted">ทุกเอกสารได้รับการอนุมัติแล้ว</p>
                    <a href="{{ route('documents.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> กลับไปหน้าหลัก
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
