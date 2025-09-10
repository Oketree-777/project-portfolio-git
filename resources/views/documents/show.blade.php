@extends('layouts.app')

@section('title', $document->title)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-alt"></i> {{ $document->title }}
                        </h4>
                        <div>
                            @if(auth()->user()->isAdmin())
                                @if($document->isPending())
                                    <div class="btn-group me-2" role="group">
                                        <form action="{{ route('documents.approve', $document) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> อนุมัติ
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            <i class="fas fa-times"></i> ไม่อนุมัติ
                                        </button>
                                    </div>
                                @elseif($document->isApproved())
                                    <form action="{{ route('documents.cancel-approval', $document) }}" method="POST" class="d-inline me-2">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกการอนุมัติ?')">
                                            <i class="fas fa-undo"></i> ยกเลิกการอนุมัติ
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fas fa-edit"></i> แก้ไข
                                </a>
                            @endif
                            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> กลับ
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- สถานะการอนุมัติ -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            @if($document->isPending())
                                <span class="badge bg-warning fs-6 me-2">รอการอนุมัติ</span>
                            @elseif($document->isApproved())
                                <span class="badge bg-success fs-6 me-2">อนุมัติแล้ว</span>
                            @elseif($document->isRejected())
                                <span class="badge bg-danger fs-6 me-2">ไม่อนุมัติ</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>เนื้อหา</h5>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($document->content)) !!}
                        </div>
                    </div>

                    @if($document->file_path)
                        <div class="mb-4">
                            <h5>ไฟล์แนบ</h5>
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-paperclip fa-2x text-primary me-3"></i>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $document->original_filename }}</h6>
                                        <small class="text-muted">
                                            อัปโหลดเมื่อ {{ $document->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <a href="{{ route('documents.download', $document) }}" class="btn btn-success">
                                        <i class="fas fa-download"></i> ดาวน์โหลด
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($document->isRejected() && $document->rejection_reason)
                        <div class="mb-4">
                            <h5>เหตุผลในการไม่อนุมัติ</h5>
                            <div class="border rounded p-3 bg-light border-danger">
                                <p class="text-danger mb-0">{{ $document->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="border-top pt-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> สร้างโดย: {{ $document->user->name }}
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> สร้างเมื่อ: {{ $document->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                        
                        @if($document->isApproved() && $document->approver)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> อนุมัติโดย: {{ $document->approver->name }}
                                        เมื่อ {{ $document->approved_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif

                        @if($document->isRejected() && $document->rejecter)
                            <div class="row mt-2">
                                <div class="col-12">
                                    <small class="text-danger">
                                        <i class="fas fa-times-circle"></i> ไม่อนุมัติโดย: {{ $document->rejecter->name }}
                                        เมื่อ {{ $document->rejected_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif

                        @if($document->updated_at != $document->created_at)
                            <div class="row mt-1">
                                <div class="col-12 text-md-end">
                                    <small class="text-muted">
                                        <i class="fas fa-edit"></i> แก้ไขล่าสุด: {{ $document->updated_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับไม่อนุมัติ -->
@if(auth()->user()->isAdmin() && $document->isPending())
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">ไม่อนุมัติเอกสาร</h5>
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
@endif
@endsection
