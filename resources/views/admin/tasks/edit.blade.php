@extends('layouts.admin')

@section('title', 'Edit Task')

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
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-edit text-warning mr-2"></i>
            Edit Task
        </h3>
    </div>
    <div class="card-body" style="padding: 30px;">
        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius: 8px; border-left: 4px solid #dc3545;">
                <strong><i class="fas fa-exclamation-circle mr-2"></i>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                    <i class="fas fa-heading text-primary mr-2"></i>Title
                </label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $task->title) }}" placeholder="Enter task title" required>
            </div>

            <div class="form-group">
                <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                    <i class="fas fa-align-left text-primary mr-2"></i>Description (Rich Text Editor)
                </label>
                <textarea name="description" id="taskDescription" class="form-control" rows="8" placeholder="Enter task description" required>{{ old('description', $task->description) }}</textarea>
                <small class="form-text text-muted">Use the toolbar above to format your text, add lists, links, etc.</small>
            </div>
            
            <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
            <script>
                CKEDITOR.replace('taskDescription', {
                    height: 300,
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                        { name: 'links', items: ['Link', 'Unlink'] },
                        { name: 'insert', items: ['Image'] },
                        { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                        { name: 'colors', items: ['TextColor', 'BGColor'] },
                        { name: 'tools', items: ['Maximize', 'Source'] }
                    ]
                });
            </script>

            <div class="form-group">
                <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                    <i class="fas fa-user-graduate text-primary mr-2"></i>Assign To (Student)
                </label>
                <select name="user_id" class="form-select">
                    <option value="">All Students</option>
                    @foreach($users as $user)
                        @if($user->role == 'student')
                            <option value="{{ $user->id }}" {{ old('user_id', $task->user_id) == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                        @endif
                    @endforeach
                </select>
                <small class="form-text text-muted">Leave empty to assign to all students</small>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-save mr-2"></i>Update Task
                </button>
                <a href="{{ route('admin.tasks.index') }}" class="btn btn-secondary ml-2" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
