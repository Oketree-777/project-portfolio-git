@extends('layouts.app')

@section('title', 'แก้ไขข้อมูล ' . $user->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
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
                        <i class="fas fa-edit"></i> แก้ไขข้อมูล
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-edit"></i> แก้ไขข้อมูล User
                </h2>
                <div class="btn-group" role="group">
                    <a href="{{ route('user-view.show', $user->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> กลับ
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit"></i> แก้ไขข้อมูล {{ $user->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('user-view.update', $user->id) }}" method="POST">
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
                            <label for="role" class="form-label">บทบาท <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">เลือกบทบาท</option>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ข้อมูลเพิ่มเติม</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>วันที่สมัคร:</strong></p>
                                    <p class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>อัปเดตล่าสุด:</strong></p>
                                    <p class="text-muted">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                                </button>
                                <a href="{{ route('admin.users.password-reset', $user->id) }}" class="btn btn-warning">
                                    <i class="fas fa-key"></i> รีเซ็ตรหัสผ่าน
                                </a>
                            </div>
                            <a href="{{ route('user-view.show', $user->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ส่วนลบ User -->
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> โซนอันตราย
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-warning"></i> คำเตือน</h6>
                        <p class="mb-2">การลบ User จะทำให้:</p>
                        <ul class="mb-3">
                            <li>ข้อมูล User ถูกลบออกจากระบบอย่างถาวร</li>
                            <li>ผลงานทั้งหมดของ User คนนี้จะถูกลบออก</li>
                            <li>ไม่สามารถกู้คืนข้อมูลได้</li>
                        </ul>
                        <button type="button" 
                                class="btn btn-danger"
                                onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                            <i class="fas fa-trash"></i> ลบ User
                        </button>
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

<script>
function confirmDelete(userId, userName) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบ User "' + userName + '"?\n\nการดำเนินการนี้ไม่สามารถยกเลิกได้ และจะลบข้อมูลทั้งหมดของ User คนนี้')) {
        // สร้าง form สำหรับส่ง DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/user-view/' + userId;
        
        // เพิ่ม CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // เพิ่ม method field
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        // เพิ่ม form ลงใน body และ submit
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
