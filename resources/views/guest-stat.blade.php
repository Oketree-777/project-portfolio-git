@extends('layouts.app')

@section('title', 'สถิติสาธารณะ - Portfolio Online')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-light">สถิติสาธารณะ Portfolio Online</h2>
                    <p class="text-muted mb-0">สถิติผลงานที่ได้รับการอนุมัติแล้ว</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn {{ $period == 7 ? 'btn-dark' : 'btn-outline-dark' }}" onclick="updatePeriod(7)" data-period="7">7 วัน</button>
                    <button type="button" class="btn {{ $period == 30 ? 'btn-dark' : 'btn-outline-dark' }}" onclick="updatePeriod(30)" data-period="30">30 วัน</button>
                    <button type="button" class="btn {{ $period == 90 ? 'btn-dark' : 'btn-outline-dark' }}" onclick="updatePeriod(90)" data-period="90">90 วัน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-dark">
                                <i class="fas fa-folder text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['total_portfolios']) }}</h4>
                            <p class="text-muted mb-0 fw-light">ผลงานที่อนุมัติแล้ว</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-success">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['approved_portfolios']) }}</h4>
                            <p class="text-muted mb-0 fw-light">ผลงานที่อนุมัติ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-info">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['total_users']) }}</h4>
                            <p class="text-muted mb-0 fw-light">ผู้ใช้ทั้งหมด</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-light">แนวโน้มผลงานใหม่</h6>
                </div>
                <div class="card-body">
                    <canvas id="portfolioChart" height="100"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-light">สถานะผลงาน</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Faculty and Major Stats -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-light">สถิติตามคณะ</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>คณะ</th>
                                    <th>จำนวนผลงาน</th>
                                    <th>เปอร์เซ็นต์</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facultyStats as $faculty)
                                    <tr>
                                        <td>{{ $faculty->faculty }}</td>
                                        <td>{{ $faculty->count }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ number_format(($faculty->count / $stats['total_portfolios']) * 100, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-light">สถิติตามสาขา</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>สาขา</th>
                                    <th>จำนวนผลงาน</th>
                                    <th>เปอร์เซ็นต์</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($majorStats as $major)
                                    <tr>
                                        <td>{{ $major->major }}</td>
                                        <td>{{ $major->count }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ number_format(($major->count / $stats['total_portfolios']) * 100, 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="mb-3">ต้องการดูผลงานทั้งหมด?</h5>
                    <p class="text-muted mb-3">เข้าสู่ระบบเพื่อดูผลงานที่รอการอนุมัติและสถิติเพิ่มเติม</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-dark">
                            <i class="fas fa-sign-in-alt me-2"></i>เข้าสู่ระบบ
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-dark">
                            <i class="fas fa-user-plus me-2"></i>สมัครสมาชิก
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Portfolio Trend Chart
const portfolioCtx = document.getElementById('portfolioChart').getContext('2d');
const portfolioChart = new Chart(portfolioCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'ผลงานใหม่',
            data: {!! json_encode($chartData['data']) !!},
            borderColor: '#000000',
            backgroundColor: 'rgba(0, 0, 0, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#f0f0f0'
                }
            },
            x: {
                grid: {
                    color: '#f0f0f0'
                }
            }
        }
    }
});

// Status Pie Chart (only approved portfolios for guests)
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['อนุมัติแล้ว'],
        datasets: [{
            data: [{{ $stats['approved_portfolios'] }}],
            backgroundColor: ['#22c55e'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function updatePeriod(days) {
    // Reset all buttons to outline style first
    document.querySelectorAll('[data-period]').forEach(btn => {
        btn.classList.remove('btn-dark');
        btn.classList.add('btn-outline-dark');
    });
    
    // Make the clicked button active
    const clickedButton = document.querySelector(`[data-period="${days}"]`);
    if (clickedButton) {
        clickedButton.classList.remove('btn-outline-dark');
        clickedButton.classList.add('btn-dark');
    }
    
    // Redirect to new period
    window.location.href = '{{ route("guest.stat") }}?period=' + days;
}

// Initialize active period button
document.addEventListener('DOMContentLoaded', function() {
    const currentPeriod = {{ $period }};
    const activeBtn = document.querySelector(`[data-period="${currentPeriod}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('btn-outline-dark');
        activeBtn.classList.add('btn-dark');
    }
});
</script>

<style>
.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #000000;
}

.card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    background: #ffffff;
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e5e5e5;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.btn-dark {
    background-color: #000000;
    border-color: #000000;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-dark:hover {
    background-color: #333333;
    border-color: #333333;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-outline-dark {
    border-color: #000000;
    color: #000000;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-dark:hover {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.text-muted {
    color: #666666 !important;
}

.text-dark {
    color: #1a1a1a !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.bg-dark {
    background-color: #000000 !important;
}

.bg-success {
    background-color: #22c55e !important;
}

.bg-info {
    background-color: #3b82f6 !important;
}

.bg-warning {
    background-color: #f59e0b !important;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
}
</style>
@endsection
