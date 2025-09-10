@extends('layouts.app')

@section('title', 'สถิติระบบ Portfolio Online')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-light">สถิติระบบ Portfolio Online</h2>
                    <p class="text-muted mb-0">ติดตามประสิทธิภาพและพฤติกรรมผู้ใช้งาน</p>
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
        <div class="col-lg-3 col-md-6 mb-3">
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
                            <p class="text-muted mb-0 fw-light">ผลงานทั้งหมด</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
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
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card h-100 stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stats-icon bg-warning">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-1 fw-bold">{{ number_format($stats['pending_portfolios']) }}</h4>
                            <p class="text-muted mb-0 fw-light">รอการอนุมัติ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
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

    <!-- Charts -->
    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-light">แนวโน้มการสร้างผลงาน ({{ $period }} วันล่าสุด)</h6>
                </div>
                <div class="card-body">
                    <canvas id="portfolioChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0 fw-light">สถานะผลงาน</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
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

// Status Pie Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['อนุมัติแล้ว', 'รอการอนุมัติ', 'ไม่อนุมัติ'],
        datasets: [{
            data: [
                {{ $stats['approved_portfolios'] }},
                {{ $stats['pending_portfolios'] }},
                {{ $stats['rejected_portfolios'] ?? 0 }}
            ],
            backgroundColor: [
                '#22c55e',
                '#f59e0b',
                '#ef4444'
            ],
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
    window.location.href = '{{ route("stat") }}?period=' + days;
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
    width: 50px;
    height: 50px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stat-card {
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    overflow: hidden;
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
    padding: 1rem 1.25rem;
}

.card-header h6 {
    margin: 0;
    font-weight: 600;
    color: #1a1a1a;
}

.table-sm td, .table-sm th {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
}

.table-sm th {
    font-weight: 600;
    color: #1a1a1a;
    background-color: #f8f9fa;
}

.table-sm td {
    color: #666666;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
}

.bg-light {
    background-color: #f8f9fa !important;
    color: #1a1a1a !important;
}

.btn-group .btn {
    border-radius: 0;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
}

.btn-group .btn:first-child {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.btn-group .btn:last-child {
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
}

.btn-outline-dark {
    border-color: #000000;
    color: #000000;
}

.btn-outline-dark:hover {
    background-color: #000000;
    border-color: #000000;
    color: #ffffff;
}

.btn-dark {
    background-color: #000000;
    border-color: #000000;
}

.btn-dark:hover {
    background-color: #333333;
    border-color: #333333;
}

@media (max-width: 768px) {
    .btn-group {
        width: 100%;
    }
    
    .btn-group .btn {
        flex: 1;
    }
    
    h2 {
        font-size: 1.5rem;
    }
}
</style>
@endsection