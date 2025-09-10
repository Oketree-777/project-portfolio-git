@extends('layouts.app')

@section('title', 'เอกสารที่อนุมัติแล้ว')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-check-circle"></i> เอกสารที่อนุมัติแล้ว
                </h2>
                <div>
                    <a href="{{ route('documents.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                    <a href="{{ route('documents.pending') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-clock"></i> รอการอนุมัติ
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
                            <div class="card h-100 border-success">
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
                                    
                                    <form action="{{ route('documents.cancel-approval', $document) }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm w-100" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกการอนุมัติ?')">
                                            <i class="fas fa-undo"></i> ยกเลิกการอนุมัติ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">ไม่มีเอกสารที่อนุมัติแล้ว</h5>
                    <p class="text-muted">ยังไม่มีเอกสารที่ได้รับการอนุมัติ</p>
                    <a href="{{ route('documents.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> กลับไปหน้าหลัก
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
