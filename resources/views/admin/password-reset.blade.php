@extends('layouts.app')

@section('title', 'รีเซ็ตรหัสผ่าน - ' . $user->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('user-view.index') }}">
                            <i class="fas fa-users"></i> จัดการ User ทั้งหมด
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('user-view.show', $user->id) }}">
                            <i class="fas fa-user"></i> {{ $user->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-key"></i> รีเซ็ตรหัสผ่าน
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-key"></i> รีเซ็ตรหัสผ่าน
                </h2>
                <a href="{{ route('user-view.edit', $user->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> กลับ
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-key"></i> รีเซ็ตรหัสผ่านสำหรับ {{ $user->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1"><strong>ผู้ใช้:</strong> {{ $user->name }}</p>
                        <p class="text-muted"><strong>อีเมล:</strong> {{ $user->email }}</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.password-reset.store', $user->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                รหัสผ่านใหม่ <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   class="form-control @error('new_password') is-invalid @enderror"
                                   placeholder="ป้อนรหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)"
                                   required>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label">
                                ยืนยันรหัสผ่านใหม่ <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                   placeholder="ป้อนรหัสผ่านใหม่อีกครั้ง"
                                   required>
                            @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" 
                                    class="btn btn-danger"
                                    onclick="return confirm('คุณแน่ใจหรือไม่ที่จะรีเซ็ตรหัสผ่านสำหรับ {{ $user->name }}?')">
                                <i class="fas fa-key"></i> รีเซ็ตรหัสผ่าน
                            </button>
                            <a href="{{ route('user-view.edit', $user->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- คำเตือน -->
            <div class="card mt-4 border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> คำเตือน
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <h6><i class="fas fa-warning"></i> ข้อควรระวัง</h6>
                        <p class="mb-2">การรีเซ็ตรหัสผ่านจะทำให้:</p>
                        <ul class="mb-0">
                            <li>ผู้ใช้ไม่สามารถเข้าสู่ระบบด้วยรหัสผ่านเดิมได้</li>
                            <li>ผู้ใช้จะต้องใช้รหัสผ่านใหม่ที่คุณตั้งให้</li>
                            <li>การดำเนินการนี้จะถูกบันทึกในระบบ Log</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.breadcrumb-item a {
    text-decoration: none;
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
