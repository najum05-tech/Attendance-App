@extends('layouts.admin')

@section('title', 'Tasks Management')

@section('content')
<style>
    .task-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .task-card:hover {
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
                <i class="fas fa-tasks text-warning mr-2"></i>
                Tasks Management
            </h3>
            <a href="{{ route('admin.tasks.create') }}" class="btn btn-warning" style="border-radius: 8px; font-weight: 600; padding: 8px 20px;">
                <i class="fas fa-plus mr-2"></i>Create Task
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3" style="border-radius: 8px; border-left: 4px solid #28a745;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">#</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Title</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Description</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Assigned To</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                        <th style="border: none; padding: 15px; font-weight: 600; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr class="task-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #666;">{{ $loop->iteration }}</td>
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #333;">
                            {{ $task->title ?? 'N/A' }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ Str::limit(strip_tags($task->description), 50) }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ $task->user->username ?? 'All Students' }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle;">
                            @if($task->status == 'pending')
                                <span class="status-badge bg-warning text-white">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($task->status == 'submitted')
                                <span class="status-badge bg-info text-white">
                                    <i class="fas fa-check mr-1"></i>Submitted
                                </span>
                            @else
                                <span class="status-badge bg-success text-white">
                                    <i class="fas fa-check-circle mr-1"></i>{{ ucfirst($task->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                            <div class="btn-group" role="group">
                                @if($task->status == 'completed' || $task->submission)
                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#taskModal{{ $task->id }}" style="border-radius: 6px; margin-right: 5px;">
                                        <i class="fas fa-eye"></i> Review
                                    </button>
                                @endif
                                <a href="{{ route('admin.tasks.edit', $task->id) }}" class="btn btn-sm btn-warning" style="border-radius: 6px; margin-right: 5px;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this task?')" style="border-radius: 6px;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Task Review Modal -->
                    @if($task->status == 'completed' || $task->submission)
                    <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content" style="border-radius: 12px;">
                                <div class="modal-header bg-gradient-to-r from-indigo-600 to-purple-600 text-white" style="border-radius: 12px 12px 0 0;">
                                    <h5 class="modal-title">Review Task Submission</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <strong>Task:</strong> {{ $task->title }}
                                    </div>
                                    <div class="mb-3">
                                        <strong>Student Submission:</strong>
                                        <div class="p-3 bg-light rounded mt-2">{{ $task->submission ?? 'No submission yet' }}</div>
                                    </div>
                                    @if($task->admin_feedback)
                                    <div class="mb-3">
                                        <strong>Your Feedback:</strong>
                                        <div class="p-3 bg-info text-white rounded mt-2">{{ $task->admin_feedback }}</div>
                                    </div>
                                    @endif
                                    <form action="{{ route('admin.tasks.approve', $task->id) }}" method="POST" class="mb-2">
                                        @csrf
                                        <div class="form-group">
                                            <label>Feedback (Optional)</label>
                                            <textarea name="admin_feedback" class="form-control" rows="2" placeholder="Add feedback...">{{ $task->admin_feedback }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-block">
                                            <i class="fas fa-check mr-2"></i>Approve Task
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tasks.reject', $task->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Rejection Reason <span class="text-danger">*</span></label>
                                            <textarea name="admin_feedback" class="form-control" rows="2" placeholder="Please provide a reason for rejection..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fas fa-times mr-2"></i>Reject Task
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: #999;">
                            <i class="fas fa-tasks fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No tasks found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

