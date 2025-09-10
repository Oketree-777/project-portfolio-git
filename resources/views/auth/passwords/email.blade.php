@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                รีเซ็ตรหัสผ่าน
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                กรอกอีเมลของคุณเพื่อรับรหัสการรีเซ็ตรหัสผ่าน
            </p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    อีเมล
                </label>
                <div class="mt-1">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="form-input @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}"
                           placeholder="กรอกอีเมลของคุณ">
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                        class="btn btn-primary w-full">
                    ส่งรหัสรีเซ็ตรหัสผ่าน
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="text-sm text-primary-600 hover:text-primary-500">
                    กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
