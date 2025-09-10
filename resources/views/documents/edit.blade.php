@extends('layouts.app')

@section('title', 'แก้ไขเอกสาร')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> แก้ไขเอกสาร
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">หัวข้อ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $document->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">เนื้อหา <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" required>{{ old('content', $document->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label">ไฟล์แนบ (ไม่บังคับ)</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file">
                            <div class="form-text">รองรับไฟล์ขนาดไม่เกิน 10MB</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($document->file_path)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-paperclip"></i> ไฟล์ปัจจุบัน: {{ $document->original_filename }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        หากอัปโหลดไฟล์ใหม่ ไฟล์เดิมจะถูกลบออก
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> กลับ
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
