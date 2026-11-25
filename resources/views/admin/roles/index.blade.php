@extends('layouts.admin')

@section('title', 'Roles Management')

@section('content')
<style>
    .role-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .role-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
                <i class="fas fa-user-tag text-primary mr-2"></i>
                Roles Management
            </h3>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 8px 20px;">
                <i class="fas fa-plus mr-2"></i>Add New Role
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
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">#</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Role Name</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Permissions</th>
                        <th style="border: none; padding: 15px; font-weight: 600; text-align: center; width: 200px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $key => $role)
                    <tr class="role-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #666;">{{ $key + 1 }}</td>
                        <td style="padding: 20px; vertical-align: middle;">
                            <span class="badge bg-primary" style="padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                                {{ $role->name }}
                            </span>
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ $role->permissions ?? '—' }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; text-align: center;">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning" style="border-radius: 6px; margin-right: 5px;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')" style="border-radius: 6px;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5" style="color: #999;">
                            <i class="fas fa-user-tag fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No roles found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
