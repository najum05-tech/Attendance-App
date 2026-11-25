@extends('layouts.admin')

@section('title', 'Mark Attendance')

@section('content')
<style>
    .form-card {
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
    }
    .form-header {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
        border-bottom: none;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px;
        height: auto;
    }
    .form-control:focus {
        border-color: #38f9d7;
        box-shadow: 0 0 0 0.2rem rgba(56, 249, 215, 0.25);
    }
    .btn-save {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 8px;
        transition: transform 0.2s;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 233, 123, 0.3);
        color: white;
    }
    .btn-cancel {
        background-color: #f8f9fa;
        color: #6c757d;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .btn-cancel:hover {
        background-color: #e2e6ea;
        text-decoration: none;
        color: #545b62;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm form-card">
            
            {{-- Header --}}
            <div class="card-header form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0" style="font-weight: 600;">
                        <i class="fas fa-user-check mr-2"></i>Mark New Attendance
                    </h3>
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-light" style="border-radius: 20px; color: #43e97b; font-weight: bold;">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body p-4">
                {{-- Error Handling --}}
                @if ($errors->any())
                    <div class="alert alert-danger" style="border-radius: 8px;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.attendance.store') }}" method="POST">
                    @csrf

                    {{-- Student Selection --}}
                    <div class="form-group mb-4">
                        <label for="user_id" style="font-weight: 600; color: #555; margin-bottom: 8px;">Select Student</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                            </div>
                            <select name="user_id" id="user_id" class="form-control border-left-0" style="border-radius: 0 8px 8px 0;" required>
                                <option value="">-- Choose a Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->username }} ({{ $student->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Date Selection --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="date" style="font-weight: 600; color: #555; margin-bottom: 8px;">Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                            <i class="fas fa-calendar-alt text-muted"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="date" id="date" class="form-control border-left-0" 
                                           style="border-radius: 0 8px 8px 0;"
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Status Selection --}}
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="status" style="font-weight: 600; color: #555; margin-bottom: 8px;">Status</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                                            <i class="fas fa-info-circle text-muted"></i>
                                        </span>
                                    </div>
                                    <select name="status" id="status" class="form-control border-left-0" style="border-radius: 0 8px 8px 0;" required>
                                        <option value="Present">Present</option>
                                        <option value="Absent">Absent</option>
                                        <option value="Leave">Leave</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-cancel mr-3">Cancel</a>
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save mr-2"></i> Save Attendance
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection