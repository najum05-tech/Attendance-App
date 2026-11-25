@extends('layouts.admin')

@section('title', 'System Attendance Report')

@section('content')
<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0;">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0" style="font-weight: 600;">
                <i class="fas fa-chart-bar text-info mr-2"></i>
                System-Wide Attendance Report
            </h3>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
        <p class="text-muted mb-0 mt-2">
            Period: {{ \Carbon\Carbon::parse($request->from_date)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($request->to_date)->format('M d, Y') }}
        </p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">Student</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Present</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Absent</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Leave</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($report as $student)
                    <tr>
                        <td style="padding: 15px; font-weight: 600;">{{ $student['username'] }}</td>
                        <td style="padding: 15px;">
                            <span class="badge bg-success">{{ $student['present'] }}</span>
                        </td>
                        <td style="padding: 15px;">
                            <span class="badge bg-danger">{{ $student['absent'] }}</span>
                        </td>
                        <td style="padding: 15px;">
                            <span class="badge bg-info">{{ $student['leave'] }}</span>
                        </td>
                        <td style="padding: 15px;">
                            <span class="badge bg-primary" style="font-size: 1.1rem; padding: 6px 12px;">{{ $student['grade'] }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

