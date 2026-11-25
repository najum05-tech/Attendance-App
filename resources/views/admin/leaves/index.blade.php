@extends('layouts.admin')

@section('title', 'Leave Requests')

@section('content')
<style>
    .leave-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .leave-card:hover {
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
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-file-medical text-warning mr-2"></i>
            All Leave Requests
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">ID</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Student</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">From Date</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">To Date</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Reason</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Submitted</th>
                        <th style="border: none; padding: 15px; font-weight: 600; text-align: center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaves as $leave)
                    <tr class="leave-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #666;">#{{ $leave->id }}</td>
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #333;">
                            {{ $leave->user->username ?? 'N/A' }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ Str::limit($leave->reason, 40) }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle;">
                            @if($leave->status == 'pending')
                                <span class="status-badge bg-warning text-white">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($leave->status == 'approved' || $leave->status == 'Approved')
                                <span class="status-badge bg-success text-white">
                                    <i class="fas fa-check-circle mr-1"></i>Approved
                                </span>
                            @else
                                <span class="status-badge bg-danger text-white">
                                    <i class="fas fa-times-circle mr-1"></i>Rejected
                                </span>
                            @endif
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ $leave->created_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                            <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn btn-sm btn-primary" style="border-radius: 6px;">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5" style="color: #999;">
                            <i class="fas fa-file-medical fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No leave requests found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
