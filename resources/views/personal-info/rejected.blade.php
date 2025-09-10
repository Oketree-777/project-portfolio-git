@extends('layouts.app')

@section('title', 'ไม่อนุมัติ')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-times text-danger"></i> ไม่อนุมัติ
                    </h4>
                    <div>
                        <a href="{{ route('personal-info.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-list"></i> ทั้งหมด
                        </a>
                        <a href="{{ route('personal-info.pending') }}" class="btn btn-warning me-2">
                            <i class="fas fa-clock"></i> รอการอนุมัติ
                        </a>
                        <a href="{{ route('personal-info.approved') }}" class="btn btn-success me-2">
                            <i class="fas fa-check"></i> อนุมัติแล้ว
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

                    @if($rejectedInfos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-danger">
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ชื่อ-นามสกุล</th>
                                        <th>อายุ</th>
                                        <th>เพศ</th>
                                        <th>คณะ</th>
                                        <th>สาขา</th>
                                        <th>วันที่ไม่อนุมัติ</th>
                                        <th>ผู้ไม่อนุมัติ</th>
                                        <th>เหตุผล</th>
                                        <th>การดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedInfos as $index => $personalInfo)
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
                                            <td>{{ $personalInfo->rejected_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $personalInfo->rejectedBy->name ?? 'ไม่ทราบ' }}</td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      title="{{ $personalInfo->rejection_reason }}">
                                                    {{ $personalInfo->rejection_reason }}
                                                </span>
                                            </td>
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
                            {{ $rejectedInfos->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-times fa-3x text-danger mb-3"></i>
                            <h5 class="text-muted">ไม่มีรายการไม่อนุมัติ</h5>
                            <p class="text-muted">ยังไม่มีผลงานที่ไม่อนุมัติ</p>
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
