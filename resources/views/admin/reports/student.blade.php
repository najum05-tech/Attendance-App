@extends('layouts.admin')

@section('title', 'Student Attendance Report')

@section('content')
<div class="card shadow-sm" style="border-radius: 12px; border: none; margin-bottom: 30px;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0;">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-user-graduate text-primary mr-2"></i>
                Attendance Report: {{ $user->username }}
            </h3>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
        <p class="text-muted mb-0 mt-2">
            Period: {{ \Carbon\Carbon::parse($request->from_date)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($request->to_date)->format('M d, Y') }}
        </p>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="text-center p-4 bg-success text-white rounded-lg">
                    <h2 class="mb-0">{{ $present }}</h2>
                    <p class="mb-0">Present</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-4 bg-danger text-white rounded-lg">
                    <h2 class="mb-0">{{ $absent }}</h2>
                    <p class="mb-0">Absent</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-4 bg-info text-white rounded-lg">
                    <h2 class="mb-0">{{ $leave }}</h2>
                    <p class="mb-0">Leave</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center p-4 bg-warning text-white rounded-lg">
                    <h2 class="mb-0">{{ $approvedLeaves }}</h2>
                    <p class="mb-0">Approved Leaves</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->created_at)->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'info') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-4 text-muted">No records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

