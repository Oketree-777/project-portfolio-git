@extends('layouts.app')

@section('title', '419 - Page Expired')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-danger">419 - Page Expired</h3>
                </div>
                <div class="card-body text-center">
                    <h4>หน้าเว็บหมดอายุ</h4>
                    <p class="text-muted">CSRF token หมดอายุหรือไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary">กลับไปหน้า Login</a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">กลับหน้าหลัก</a>
                    </div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            หากปัญหายังคงเกิดขึ้น กรุณาลอง:
                            <ul class="list-unstyled mt-2">
                                <li>• ล้าง cache ของเบราว์เซอร์</li>
                                <li>• รีเฟรชหน้าเว็บ</li>
                                <li>• ลองใช้เบราว์เซอร์อื่น</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
