@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    /* Card Animations */
    .stat-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }
    
    /* Gradients */
    /* Original 4 */
    .bg-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .bg-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-gradient-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
    
    /* New Gradients for added cards */
    .bg-gradient-5 { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); } /* Orange/Peach */
    .bg-gradient-6 { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); } /* Purple/Pink */
    .bg-gradient-7 { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); } /* Teal/Deep Purple */

    /* Icon Circle */
    .icon-circle {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
            <div>
                <h2 class="font-weight-bold text-dark m-0">Welcome back, {{ auth()->user()->username ?? 'Admin' }}! 👋</h2>
                <p class="text-muted m-0">Here's what's happening in your academy today.</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge badge-success px-3 py-2" style="font-size: 0.9rem;">
                    <i class="fas fa-check-circle mr-1"></i> System Online
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- 1. Total Students --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-1 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $totalUsers ?? 0 }}</h3>
                        <p class="mb-0" style="opacity: 0.9;">Total Students</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.index') }}" class="text-white text-decoration-none small font-weight-bold">
                        View All Users <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Present Today --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-2 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $presentToday ?? 0 }}</h3>
                        <p class="mb-0" style="opacity: 0.9;">Present Today</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.attendance.index') }}" class="text-white text-decoration-none small font-weight-bold">
                        View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Pending Leaves --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-3 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $pendingLeaves ?? 0 }}</h3>
                        <p class="mb-0" style="opacity: 0.9;">Pending Leaves</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-envelope-open-text fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.leaves.index') }}" class="text-white text-decoration-none small font-weight-bold">
                        Review Requests <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Active Tasks --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-4 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $tasks ?? 0 }}</h3>
                        <p class="mb-0" style="opacity: 0.9;">Active Tasks</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.tasks.index') }}" class="text-white text-decoration-none small font-weight-bold">
                        Manage Tasks <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. User Roles (NEW) --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-5 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $roles ?? 0 }}</h3>
                        <p class="mb-0" style="opacity: 0.9;">User Roles</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-user-tag fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    {{-- Assumed Route: admin.roles.index (change if different) --}}
                    <a href="{{ Route::has('admin.roles.index') ? route('admin.roles.index') : '#' }}" class="text-white text-decoration-none small font-weight-bold">
                        Manage Roles <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 6. Total Grades (NEW) --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-6 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">{{ $grades ?? 0 }}</h3>
                        <p class="mb-0" style="opacity: 0.9;">Total Grades</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-graduation-cap fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    {{-- Assumed Route: admin.grades.index --}}
                    <a href="{{ Route::has('admin.grades.index') ? route('admin.grades.index') : '#' }}" class="text-white text-decoration-none small font-weight-bold">
                        View Grades <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 7. System Reports (NEW) --}}
    <div class="col-lg-3 col-6 mb-4">
        <div class="stat-card bg-gradient-7 text-white">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="font-weight-bold mb-1">Stats</h3>
                        <p class="mb-0" style="opacity: 0.9;">System Reports</p>
                    </div>
                    <div class="icon-circle">
                        <i class="fas fa-chart-pie fa-lg"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.reports.index') }}" class="text-white text-decoration-none small font-weight-bold">
                        Download Data <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="font-weight-bold m-0"><i class="fas fa-bolt text-warning mr-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-block py-3 font-weight-bold shadow-sm" style="border-radius: 10px; border-width: 2px;">
                            <i class="fas fa-user-plus mr-2"></i> Add Student
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.attendance.create') }}" class="btn btn-outline-success btn-block py-3 font-weight-bold shadow-sm" style="border-radius: 10px; border-width: 2px;">
                            <i class="fas fa-clipboard-check mr-2"></i> Mark Attendance
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.tasks.create') }}" class="btn btn-outline-warning btn-block py-3 font-weight-bold shadow-sm" style="border-radius: 10px; border-width: 2px;">
                            <i class="fas fa-plus-circle mr-2"></i> Create Task
                        </a>
                    </div>

                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-info btn-block py-3 font-weight-bold shadow-sm" style="border-radius: 10px; border-width: 2px;">
                            <i class="fas fa-file-download mr-2"></i> Download Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection