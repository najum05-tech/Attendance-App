@extends('layouts.admin')

@section('title', 'Attendance Records')

@section('content')
<style>
    .attendance-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .attendance-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
                <i class="fas fa-calendar-check text-success mr-2"></i>
                All Attendance Records
            </h3>
            <a href="{{ route('admin.attendance.create') }}" class="btn btn-success" style="border-radius: 8px; font-weight: 600; padding: 8px 20px;">
                <i class="fas fa-plus mr-2"></i>Mark Attendance
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">User</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Date</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Day</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Marked By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $record)
                    <tr class="attendance-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #333;">
                            {{ $record->user->username ?? 'N/A' }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ \Carbon\Carbon::parse($record->created_at)->format('M d, Y') }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ \Carbon\Carbon::parse($record->created_at)->format('l') }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle;">
                            @if($record->status == 'present')
                                <span class="status-badge bg-success text-white">
                                    <i class="fas fa-check-circle mr-1"></i>Present
                                </span>
                            @else
                                <span class="status-badge bg-danger text-white">
                                    <i class="fas fa-times-circle mr-1"></i>{{ ucfirst($record->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ $record->marked_by ?? 'System' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5" style="color: #999;">
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
@endsection
