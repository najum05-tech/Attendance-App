@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
<style>
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-edit text-primary mr-2"></i>
            Edit Role
        </h3>
    </div>
    <div class="card-body" style="padding: 30px;">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                    <i class="fas fa-tag text-primary mr-2"></i>Role Name
                </label>
                <input type="text" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $role->name) }}"
                       placeholder="Enter role name"
                       required>
                @error('name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                    <i class="fas fa-key text-primary mr-2"></i>Permissions (Optional)
                </label>
                <textarea name="permissions" class="form-control" rows="4" placeholder="Enter permissions">{{ old('permissions', $role->permissions) }}</textarea>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-save mr-2"></i>Update Role
                </button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary ml-2" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
