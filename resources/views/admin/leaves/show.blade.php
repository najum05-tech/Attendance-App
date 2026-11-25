@extends('layouts.admin')

@section('title', 'Leave Details')

@section('content')
<style>
    .info-card {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }
    .info-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .info-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
</style>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm info-card" style="border-radius: 12px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
                <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
                    <i class="fas fa-file-medical text-warning mr-2"></i>
                    Leave Request Details
                </h3>
            </div>
            <div class="card-body" style="padding: 30px;">
                <div class="info-item">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user text-primary mr-3" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">Student:</strong>
                        <span style="color: #333; font-weight: 600;">{{ $leave->user->username ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-calendar-alt text-primary mr-3" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">From Date:</strong>
                        <span style="color: #333;">{{ \Carbon\Carbon::parse($leave->from_date ?? $leave->start_date)->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-calendar-check text-primary mr-3" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">To Date:</strong>
                        <span style="color: #333;">{{ \Carbon\Carbon::parse($leave->to_date ?? $leave->end_date)->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas fa-file-alt text-primary mr-3 mt-1" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">Reason:</strong>
                        <span style="color: #333;">{{ $leave->reason }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-info-circle text-primary mr-3" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">Status:</strong>
                        @if($leave->status == 'pending')
                            <span class="badge bg-warning text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        @elseif($leave->status == 'approved' || $leave->status == 'Approved')
                            <span class="badge bg-success text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                <i class="fas fa-check-circle mr-1"></i>Approved
                            </span>
                        @else
                            <span class="badge bg-danger text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                <i class="fas fa-times-circle mr-1"></i>Rejected
                            </span>
                        @endif
                    </div>
                </div>

                @if($leave->admin_comment || $leave->comment)
                <div class="info-item">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas fa-comment text-primary mr-3 mt-1" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">Admin Comment:</strong>
                        <span style="color: #333;">{{ $leave->admin_comment ?? $leave->comment }}</span>
                    </div>
                </div>
                @endif

                <div class="info-item">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-primary mr-3" style="width: 20px;"></i>
                        <strong style="color: #666; min-width: 120px;">Submitted:</strong>
                        <span style="color: #666;">{{ $leave->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if($leave->status == 'pending' || $leave->status == 'Pending')
        <div class="card shadow-sm" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white" style="padding: 30px;">
                <h4 class="mb-4" style="font-weight: 600;">
                    <i class="fas fa-gavel mr-2"></i>Take Action
                </h4>
                <p class="mb-4" style="opacity: 0.9;">Review and approve or reject this leave request.</p>
                
                <form action="{{ route('admin.leaves.approve', $leave->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-white mb-2" style="font-weight: 500;">Comment (Optional)</label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Add a comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" style="border-radius: 8px; padding: 12px; font-weight: 600;">
                        <i class="fas fa-check-circle mr-2"></i>Approve Leave
                    </button>
                </form>

                <form action="{{ route('admin.leaves.reject', $leave->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="text-white mb-2" style="font-weight: 500;">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="2" placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-block" style="border-radius: 8px; padding: 12px; font-weight: 600;">
                        <i class="fas fa-times-circle mr-2"></i>Reject Leave
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card shadow-sm" style="border-radius: 12px; border: none;">
            <div class="card-body" style="padding: 30px; text-center;">
                <div class="mb-3">
                    @if($leave->status == 'approved' || $leave->status == 'Approved')
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h4 class="text-success font-weight-bold">Leave Approved</h4>
                    @else
                        <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                        <h4 class="text-danger font-weight-bold">Leave Rejected</h4>
                    @endif
                </div>
                <p class="text-muted">This leave request has already been processed.</p>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-primary mt-3" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
