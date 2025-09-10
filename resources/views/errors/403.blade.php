@extends('layouts.app')

@section('title', '403 - Access Denied')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-danger">403 - Access Denied</h3>
                </div>
                <div class="card-body text-center">
                    <h4>คุณไม่มีสิทธิ์เข้าถึงหน้านี้</h4>
                    <p class="text-muted">{{ $exception->getMessage() ?? 'ไม่สามารถเข้าถึงได้' }}</p>
                    
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">กลับหน้าหลัก</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-secondary">เข้าสู่ระบบ</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
