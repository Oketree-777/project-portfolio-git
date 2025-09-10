@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                ตั้งรหัสผ่านใหม่
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                กรอกรหัสที่ได้รับจากอีเมลและรหัสผ่านใหม่
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="code" value="{{ $code }}">

            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">
                    รหัสยืนยัน
                </label>
                <div class="mt-1">
                    <input id="code" name="code_display" type="text" readonly
                           class="form-input bg-gray-100"
                           value="{{ $code }}"
                           maxlength="6">
                </div>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    รหัสผ่านใหม่
                </label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required 
                           class="form-input @error('password') border-red-500 @enderror"
                           placeholder="รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)">
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    ยืนยันรหัสผ่านใหม่
                </label>
                <div class="mt-1">
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="form-input"
                           placeholder="ยืนยันรหัสผ่านใหม่">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="btn btn-primary w-full">
                    ตั้งรหัสผ่านใหม่
                </button>
            </div>

            <div class="text-center space-y-2">
                <button type="button" id="resend-code" 
                        class="text-sm text-primary-600 hover:text-primary-500">
                    ส่งรหัสใหม่
                </button>
                <br>
                <a href="{{ route('password.request') }}" 
                   class="text-sm text-gray-600 hover:text-gray-500">
                    กลับไปหน้าขอรีเซ็ตรหัสผ่าน
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('resend-code').addEventListener('click', function() {
    const email = '{{ $email }}';
    const button = this;
    
    // Disable button
    button.disabled = true;
    button.textContent = 'กำลังส่ง...';
    
    fetch('{{ route("password.resend") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('ส่งรหัสใหม่แล้ว กรุณาตรวจสอบอีเมล');
        } else {
            alert('ไม่สามารถส่งรหัสได้: ' + data.message);
        }
    })
    .catch(error => {
        alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.textContent = 'ส่งรหัสใหม่';
    });
});
</script>
@endsection
