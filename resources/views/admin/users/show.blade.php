@extends('layouts.admin')

@section('title', 'User Attendance Summary')

@section('content')
<style>
    .stat-box {
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        color: white;
        margin-bottom: 20px;
    }
    .stat-box h3 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 style="font-weight: 700; color: #333;">
                    <i class="fas fa-user text-primary mr-2"></i>
                    {{ $user->username }} - Attendance Summary
                </h2>
                <p class="text-muted mb-0">{{ $user->email }} | Role: {{ ucfirst($user->role) }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <h3>{{ $totalPresent }}</h3>
            <p class="mb-0" style="font-size: 1rem; opacity: 0.9;">Present Days</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <h3>{{ $totalAbsent }}</h3>
            <p class="mb-0" style="font-size: 1rem; opacity: 0.9;">Absent Days</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <h3>{{ $totalLeave }}</h3>
            <p class="mb-0" style="font-size: 1rem; opacity: 0.9;">Leave Days</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3>{{ $totalDays }}</h3>
            <p class="mb-0" style="font-size: 1rem; opacity: 0.9;">Total Days</p>
        </div>
    </div>
</div>

<!-- Leave Statistics -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm" style="border-radius: 12px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0;">
                <h4 class="mb-0" style="font-weight: 600;">
                    <i class="fas fa-file-medical text-warning mr-2"></i>
                    Leave Statistics
                </h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="p-3">
                            <h3 class="text-success">{{ $approvedLeaves }}</h3>
                            <p class="mb-0 text-muted">Approved Leaves</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <h3 class="text-warning">{{ $pendingLeaves }}</h3>
                            <p class="mb-0 text-muted">Pending Leaves</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <h3 class="text-danger">{{ $rejectedLeaves }}</h3>
                            <p class="mb-0 text-muted">Rejected Leaves</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attendance Records -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm" style="border-radius: 12px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0;">
                <h4 class="mb-0" style="font-weight: 600;">
                    <i class="fas fa-calendar-check text-success mr-2"></i>
                    Attendance Records
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                            <tr>
                                <th style="border: none; padding: 15px; font-weight: 600;">Date</th>
                                <th style="border: none; padding: 15px; font-weight: 600;">Day</th>
                                <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr>
                                <td style="padding: 15px; vertical-align: middle;">
                                    {{ \Carbon\Carbon::parse($attendance->created_at)->format('M d, Y') }}
                                </td>
                                <td style="padding: 15px; vertical-align: middle;">
                                    {{ \Carbon\Carbon::parse($attendance->created_at)->format('l') }}
                                </td>
                                <td style="padding: 15px; vertical-align: middle;">
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                            <i class="fas fa-check-circle mr-1"></i>Present
                                        </span>
                                    @elseif($attendance->status == 'absent')
                                        <span class="badge bg-danger text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                            <i class="fas fa-times-circle mr-1"></i>Absent
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                            <i class="fas fa-calendar mr-1"></i>Leave
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3" style="opacity: 0.3;"></i>
                                    <p class="mb-0">No attendance records found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

