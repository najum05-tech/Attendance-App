@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<style>
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-user-edit text-primary mr-2"></i>
            Edit User
        </h3>
    </div>
    <div class="card-body" style="padding: 30px;">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-user text-primary mr-2"></i>Username
                        </label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-envelope text-primary mr-2"></i>Email
                        </label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-user-tag text-primary mr-2"></i>Role
                        </label>
                        <select name="role" class="form-select" required>
                            <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        </select>
                        @error('role')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-image text-primary mr-2"></i>Avatar
                        </label>
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="avatarInput" accept="image/*">
                            <label class="custom-file-label" for="avatarInput">Choose file...</label>
                        </div>
                        <small class="form-text text-muted">Upload a profile picture (JPG, PNG, max 2MB)</small>
                        @error('avatar')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">Current Avatar</label>
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-preview mb-2" id="avatarPreview">
                        @else
                            <div class="avatar-preview bg-gradient-to-br from-indigo-400 to-purple-500 d-flex align-items-center justify-content-center text-white font-weight-bold mx-auto mb-2" id="avatarPreview" style="font-size: 2rem;">
                                {{ strtoupper(substr($user->username, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-save mr-2"></i>Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatarPreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    preview.outerHTML = '<img src="' + e.target.result + '" alt="Avatar" class="avatar-preview mb-2" id="avatarPreview">';
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
