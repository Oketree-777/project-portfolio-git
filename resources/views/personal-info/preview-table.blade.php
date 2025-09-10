@if($personalInfos->count() > 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> พบข้อมูล {{ $personalInfos->count() }} รายการ
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-primary">
                <tr>
                    <th class="text-center" style="width: 15%;">ลำดับที่</th>
                    <th class="text-center" style="width: 45%;">ชื่อ - นามสกุล</th>
                    <th class="text-center" style="width: 20%;">คณะ</th>
                    <th class="text-center" style="width: 20%;">สาขา</th>
                </tr>
            </thead>
            <tbody>
                @foreach($personalInfos as $index => $personalInfo)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $personalInfo->title }} {{ $personalInfo->first_name }} {{ $personalInfo->last_name }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $personalInfo->faculty }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">
                                {{ $personalInfo->major }}
                            </small>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($personalInfos instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-3">
            {{ $personalInfos->links() }}
        </div>
    @endif

    <div class="alert alert-success mt-3">
        <i class="fas fa-check-circle"></i> ข้อมูลนี้จะถูกส่งออกเป็นไฟล์ PDF เมื่อคุณคลิกปุ่ม "ส่งออก PDF"
    </div>
@else
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> ไม่พบข้อมูลที่ตรงกับเงื่อนไขที่เลือก
    </div>
    <div class="text-center py-4">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">ไม่พบข้อมูล</h5>
        <p class="text-muted">ลองเปลี่ยนเงื่อนไขการค้นหา</p>
    </div>
@endif
