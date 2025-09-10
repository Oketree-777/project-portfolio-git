@extends('layouts.app')

@section('title', 'จัดการ User ทั้งหมด')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-users"></i> จัดการ User ทั้งหมด
                </h2>
                <a href="{{ route('personal-info.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> กลับไปจัดการผลงาน
                </a>
            </div>

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

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> รายชื่อ User ทั้งหมด ({{ $users->total() }} คน)
                    </h5>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่อ</th>
                                        <th>อีเมล</th>
                                        <th>วันที่สมัคร</th>
                                        <th>ผลงานทั้งหมด</th>
                                        <th>ผลงานที่อนุมัติ</th>
                                        <th>ผลงานที่รอ</th>
                                        <th>การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $user->total_projects }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $user->total_projects }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $user->pending_projects }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('user-view.show', $user->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="ดูผลงานทั้งหมด">
                                                        <i class="fas fa-eye"></i> ดูผลงาน
                                                    </a>
                                                    <a href="{{ route('user-view.dashboard', $user->id) }}" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="ดู Dashboard">
                                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                                    </a>
                                                    <a href="{{ route('user-view.edit', $user->id) }}" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="แก้ไขข้อมูล">
                                                        <i class="fas fa-edit"></i> แก้ไข
                                                    </a>
                                                    <a href="{{ route('admin.users.password-reset', $user->id) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="รีเซ็ตรหัสผ่าน">
                                                        <i class="fas fa-key"></i> รหัสผ่าน
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="ลบ User"
                                                            onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">ยังไม่มี User ในระบบ</h5>
                            <p class="text-muted">User ที่ลงทะเบียนจะแสดงที่นี่</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.btn-group .btn {
    margin-right: 2px;
}

.badge {
    font-size: 0.8em;
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
