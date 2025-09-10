<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📁</text></svg>">
    
    <!-- Additional Favicon formats for better browser support -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:300,400,500,600,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <i class="fas fa-briefcase me-2"></i>PORTFOLIO ONLINE
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Navigation Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="fas fa-home me-1"></i>หน้าหลัก
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('portfolios.index') }}">
                                <i class="fas fa-folder me-1"></i>ผลงานทั้งหมด
                            </a>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('create') }}">
                                    <i class="fas fa-plus me-1"></i>เพิ่มโปรเจค
                                </a>
                            </li>
                        @endauth
                        
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-dark me-2" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i>เข้าสู่ระบบ
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-dark" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i>ลงทะเบียน
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->isAdmin())
                                        <h6 class="dropdown-header">
                                            <i class="fas fa-cog me-1"></i>Admin Panel
                                        </h6>
                                        <a class="dropdown-item" href="{{ route('personal-info.index') }}">
                                            <i class="fas fa-users me-2"></i>ข้อมูลส่วนตัว
                                        </a>
                                        <a class="dropdown-item" href="{{ route('user-view.index') }}">
                                            <i class="fas fa-eye me-2"></i>ดูหน้าตา User ทั้งหมด
                                        </a>
                                        <a class="dropdown-item" href="{{ route('stat') }}">
                                            <i class="fas fa-chart-bar me-2"></i>สถิติ
                                        </a>


                                        <div class="dropdown-divider"></div>
                                    @endif
                                    
                                    <h6 class="dropdown-header">
                                        <i class="fas fa-tools me-1"></i>Features
                                    </h6>
                                    <a class="dropdown-item" href="{{ url('/') }}">
                                        <i class="fas fa-home me-2"></i>หน้าหลัก
                                    </a>
                                    <a class="dropdown-item" href="{{ route('create') }}">
                                        <i class="fas fa-plus me-2"></i>เพิ่มโปรเจค
                                    </a>

                                    
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">
                                        <i class="fas fa-user-cog me-1"></i>Account
                                    </h6>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('doc') }}">
                                        <i class="fas fa-book me-2"></i>คู่มือการใช้งาน
                                    </a>
                                    <a class="dropdown-item" href="{{ route('stat') }}">
                                        <i class="fas fa-chart-bar me-2"></i>สถิติ
                                    </a>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-cog me-2"></i>การตั้งค่าแอคเคาท์
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>ออกจากระบบ
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sidebar Navigation -->
        <div class="sidebar-wrapper">
            <div class="sidebar d-flex flex-column">
                <div class="sidebar-header">
                    <h5 class="sidebar-title">
                        <i class="fas fa-bars me-2"></i>เมนูหลัก
                    </h5>
                </div>
                
                <div class="sidebar-menu">
                    <ul class="sidebar-nav">
                        <!-- เมนูสำหรับทุกคน -->
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                                <i class="fas fa-home"></i>
                                <span>หน้าหลัก</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeIs('portfolios.index') ? 'active' : '' }}" href="{{ route('portfolios.index') }}">
                                <i class="fas fa-folder"></i>
                                <span>ดูผลงานทั้งหมด</span>
                            </a>
                        </li>
                        
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeIs('portfolios.search') ? 'active' : '' }}" href="{{ route('portfolios.search') }}">
                                <i class="fas fa-search"></i>
                                <span>ค้นหาผลงาน</span>
                            </a>
                        </li>
                        

                        
                        <!-- เมนูสำหรับผู้ใช้ที่เข้าสู่ระบบแล้ว -->
                        @auth
                            <li class="sidebar-item">
                                <a class="sidebar-link {{ request()->routeIs('create') ? 'active' : '' }}" href="{{ route('create') }}">
                                    <i class="fas fa-plus"></i>
                                    <span>เพิ่มผลงาน</span>
                                </a>
                            </li>
                            
                            @if(Auth::user()->isAdmin())
                                <li class="sidebar-divider">
                                    <span>จัดการระบบ</span>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->routeIs('personal-info.pending') ? 'active' : '' }}" href="{{ route('personal-info.pending') }}">
                                        <i class="fas fa-clock"></i>
                                        <span>รอการอนุมัติ</span>
                                        @php
                                            $pendingCount = \App\Models\PersonalInfo::where('status', 'pending')->count();
                                        @endphp
                                        @if($pendingCount > 0)
                                            <span class="badge bg-warning ms-auto">{{ $pendingCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->routeIs('personal-info.*') && !request()->routeIs('personal-info.pending') ? 'active' : '' }}" href="{{ route('personal-info.index') }}">
                                        <i class="fas fa-users"></i>
                                        <span>จัดการข้อมูล</span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link {{ request()->routeIs('user-view.*') ? 'active' : '' }}" href="{{ route('user-view.index') }}">
                                        <i class="fas fa-eye"></i>
                                        <span>ดูผู้ใช้ทั้งหมด</span>
                                    </a>
                                </li>
                                
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('personal-info.export-form') }}">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>ส่งออก PDF</span>
                                    </a>
                                </li>
                            @endif
                        @endauth
                        
                        <!-- เมนูช่วยเหลือสำหรับทุกคน -->

                        
                        @auth
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell"></i>
                                <span>การแจ้งเตือน</span>
                                <span id="notification-badge" class="badge bg-danger ms-auto" style="display: none;">0</span>
                            </a>
                        </li>
                        @endauth
                        
                        @auth
                            <li class="sidebar-divider">
                                <span>บัญชีผู้ใช้</span>
                            </li>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>แดชบอร์ด</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('doc') }}">
                                    <i class="fas fa-book"></i>
                                    <span>คู่มือการใช้งาน</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('stat') }}">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>สถิติ</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-cog"></i>
                                    <span>ตั้งค่าบัญชี</span>
                                </a>
                            </li>
                            
                            <li class="sidebar-item">
                                <a class="sidebar-link text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>ออกจากระบบ</span>
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>



        <!-- Main Content Area -->
        <div class="main-content">
            <main class="py-4">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

            @yield('content')
                </div>
            </main>
        </div>
    </div>

    <style>
    /* Global Styles */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        color: #1a1a1a;
        background-color: #ffffff;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Top Navigation */
    .navbar {
        z-index: 1030;
        background: #ffffff !important;
        border-bottom: 1px solid #e5e5e5;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand {
        font-size: 1.25rem;
        color: #000000 !important;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .navbar-nav .nav-link {
        font-weight: 500;
        color: #666666 !important;
        transition: all 0.2s ease;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        margin: 0 0.25rem;
    }

    .navbar-nav .nav-link:hover {
        color: #000000 !important;
        background-color: #f8f9fa;
    }

    .btn-outline-dark {
        border-color: #000000;
        color: #000000;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-outline-dark:hover {
        background-color: #000000;
        border-color: #000000;
        color: #ffffff;
    }

    .btn-dark {
        background-color: #000000;
        border-color: #000000;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .btn-dark:hover {
        background-color: #333333;
        border-color: #333333;
    }

    .dropdown-header {
        font-weight: 600;
        color: #666666;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.5rem 1rem;
    }

    .dropdown-item {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #1a1a1a;
        transition: all 0.2s ease;
        border-radius: 4px;
        margin: 0.125rem 0.5rem;
        width: calc(100% - 1rem);
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #000000;
    }

    .dropdown-item.text-danger:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }

    /* Sidebar Navigation */
    .sidebar-wrapper {
        position: fixed;
        top: 56px;
        left: 0;
        height: calc(100vh - 56px);
        width: 280px;
        background: #ffffff;
        border-right: 1px solid #e5e5e5;
        z-index: 1020;
        overflow-y: auto;
        box-shadow: 1px 0 3px rgba(0, 0, 0, 0.05);
    }

    .sidebar {
        padding: 0;
    }

    .sidebar-header {
        background: #000000;
        color: white;
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #e5e5e5;
    }

    .sidebar-title {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        letter-spacing: -0.25px;
    }

    .sidebar-menu {
        padding: 1rem 0;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-item {
        margin: 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        color: #666666;
        text-decoration: none;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
        font-weight: 500;
    }

    .sidebar-link:hover {
        background: #f8f9fa;
        color: #000000;
        text-decoration: none;
        border-left: 3px solid #000000;
        padding-left: 1.125rem;
    }

    .sidebar-link.active {
        background: #f8f9fa;
        color: #000000;
        border-left: 3px solid #000000;
        font-weight: 600;
        padding-left: 1.125rem;
    }

    .sidebar-link.active:hover {
        background: #f0f0f0;
        color: #000000;
    }

    .sidebar-link i {
        width: 20px;
        margin-right: 0.875rem;
        text-align: center;
        font-size: 0.875rem;
    }

    .sidebar-link span {
        font-weight: 500;
        font-size: 0.875rem;
    }

    .sidebar-divider {
        padding: 0.75rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #999999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8f9fa;
        border-bottom: 1px solid #e5e5e5;
    }

    .sidebar-divider span {
        margin: 0;
    }

    /* Main Content Area */
    .main-content {
        margin-left: 280px;
        margin-top: 56px;
        min-height: calc(100vh - 56px);
        background: #ffffff;
        padding: 2rem;
    }

    /* Alerts */
    .alert {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-success {
        background-color: #f0fdf4;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .alert-danger {
        background-color: #fef2f2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }

    .alert-warning {
        background-color: #fffbeb;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .alert-info {
        background-color: #f0f9ff;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    /* Cards and Components */
    .card {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        background: #ffffff;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e5e5e5;
        font-weight: 600;
        color: #1a1a1a;
    }

    /* Tables */
    .table {
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e5e5e5;
        font-weight: 600;
        color: #1a1a1a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }

    .table td {
        border-bottom: 1px solid #f0f0f0;
        color: #666666;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Buttons */
    .btn {
        font-weight: 500;
        border-radius: 6px;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: #000000;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #333333;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-secondary {
        background-color: #6b7280;
        color: #ffffff;
    }

    .btn-secondary:hover {
        background-color: #4b5563;
    }

    /* Forms */
    .form-control {
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #000000;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    /* Badges */
    .badge {
        font-weight: 500;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar-wrapper {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar-wrapper.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            padding: 1rem;
        }

        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand {
            font-size: 1.1rem;
        }
    }

    @media (min-width: 769px) {
        .sidebar-wrapper {
            transform: translateX(0);
        }
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>

    <script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const navbarToggler = document.querySelector('.navbar-toggler');
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        
        if (navbarToggler && sidebarWrapper) {
            navbarToggler.addEventListener('click', function() {
                sidebarWrapper.classList.toggle('show');
            });
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!sidebarWrapper.contains(event.target) && !navbarToggler.contains(event.target)) {
                    sidebarWrapper.classList.remove('show');
                }
            }
        });

        // Load notification count
        loadNotificationCount();
        
        // Update notification count every 30 seconds
        setInterval(loadNotificationCount, 30000);
    });

    // Function to load notification count
    function loadNotificationCount() {
        @auth
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notification-badge');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'inline';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
        @endauth
    }
    </script>
</body>
</html>
