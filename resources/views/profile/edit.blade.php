@extends('layouts.app')

@section('title', 'การตั้งค่าแอคเคาท์')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-cog"></i> การตั้งค่าแอคเคาท์
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- ข้อมูลโปรไฟล์ -->
                    <div class="mb-5">
                        <h5 class="mb-3">
                            <i class="fas fa-user"></i> ข้อมูลโปรไฟล์
                        </h5>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">ชื่อ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">อีเมล <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">บทบาท</label>
                                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" readonly>
                                <div class="form-text">บทบาทไม่สามารถเปลี่ยนแปลงได้</div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> บันทึกข้อมูลโปรไฟล์
                            </button>
                        </form>
                    </div>

                    <hr>

                    <!-- เปลี่ยนรหัสผ่าน -->
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-key"></i> เปลี่ยนรหัสผ่าน
                        </h5>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">รหัสผ่านใหม่ <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร</div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> เปลี่ยนรหัสผ่าน
                            </button>
                        </form>
                    </div>

                    <hr>

                    <!-- ข้อมูลเพิ่มเติม -->
                    <div class="mb-3">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle"></i> ข้อมูลเพิ่มเติม
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>วันที่สมัคร:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>อัปเดตล่าสุด:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
