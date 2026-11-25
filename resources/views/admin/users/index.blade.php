@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<style>
    .user-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .user-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .role-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
                <i class="fas fa-users text-primary mr-2"></i>
                Manage Users
            </h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 8px 20px;">
                <i class="fas fa-user-plus mr-2"></i>Add New User
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">#</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Avatar</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Username</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Email</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Role</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Attendance</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Leaves</th>
                        <th style="border: none; padding: 15px; font-weight: 600; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="user-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #666;">{{ $loop->iteration }}</td>
                        <td style="padding: 20px; vertical-align: middle;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-circle">
                            @else
                                <div class="avatar-circle bg-gradient-to-br from-indigo-400 to-purple-500 d-flex align-items-center justify-content-center text-white font-weight-bold" style="font-size: 1.2rem;">
                                    {{ strtoupper(substr($user->username, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #333;">{{ $user->username }}</td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">{{ $user->email }}</td>
                        <td style="padding: 20px; vertical-align: middle;">
                            @if($user->role == 'admin')
                                <span class="role-badge bg-danger text-white">Admin</span>
                            @elseif($user->role == 'teacher')
                                <span class="role-badge bg-info text-white">Teacher</span>
                            @else
                                <span class="role-badge bg-success text-white">Student</span>
                            @endif
                        </td>
                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                            <span class="badge bg-info" style="padding: 4px 10px; border-radius: 15px; font-weight: 600;">
                                {{ $user->attendances_count ?? 0 }} records
                            </span>
                        </td>
                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                            <span class="badge bg-warning" style="padding: 4px 10px; border-radius: 15px; font-weight: 600;">
                                {{ $user->leave_requests_count ?? 0 }} requests
                            </span>
                        </td>
                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" style="border-radius: 6px; margin-right: 5px;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning" style="border-radius: 6px; margin-right: 5px;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')" style="border-radius: 6px;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5" style="color: #999;">
                            <i class="fas fa-users fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
