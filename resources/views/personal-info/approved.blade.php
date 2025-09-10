@extends('layouts.app')

@section('title', 'อนุมัติแล้ว')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-check text-success"></i> อนุมัติแล้ว
                    </h4>
                    <div>
                        <a href="{{ route('personal-info.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-list"></i> ทั้งหมด
                        </a>
                        <a href="{{ route('personal-info.pending') }}" class="btn btn-warning me-2">
                            <i class="fas fa-clock"></i> รอการอนุมัติ
                        </a>
                        <a href="{{ route('personal-info.rejected') }}" class="btn btn-danger me-2">
                            <i class="fas fa-times"></i> ไม่อนุมัติ
                        </a>
                        <a href="{{ route('personal-info.export-form') }}" class="btn btn-secondary">
                            <i class="fas fa-file-pdf"></i> ส่งออก PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($approvedInfos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>อายุ</th>
                                        <th>เพศ</th>
                                        <th>คณะ</th>
                                        <th>สาขา</th>
                                        <th>วันที่อนุมัติ</th>
                                        <th>ผู้อนุมัติ</th>
                                        <th>การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedInfos as $index => $personalInfo)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $personalInfo->full_name }}</strong>
                                            </td>
                                            <td>{{ $personalInfo->age }} ปี</td>
                                            <td>
                                                <span class="badge bg-{{ $personalInfo->gender == 'ชาย' ? 'primary' : 'danger' }}">
                                                    {{ $personalInfo->gender }}
                                                </span>
                                            </td>
                                            <td>{{ $personalInfo->faculty }}</td>
                                            <td>{{ $personalInfo->major }}</td>
                                            <td>{{ $personalInfo->approved_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $personalInfo->approvedBy->name ?? 'ไม่ทราบ' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('personal-info.show', $personalInfo->id) }}" 
                                                       class="btn btn-sm btn-info" title="ดูข้อมูล">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('personal-info.edit', $personalInfo->id) }}" 
                                                       class="btn btn-sm btn-warning" title="แก้ไข">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('personal-info.destroy', $personalInfo->id) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="ลบ">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $approvedInfos->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check fa-3x text-success mb-3"></i>
                            <h5 class="text-muted">ไม่มีรายการอนุมัติแล้ว</h5>
                            <p class="text-muted">ยังไม่มีผลงานที่ได้รับการอนุมัติ</p>
                            <a href="{{ route('personal-info.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> กลับไปหน้าหลัก
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
