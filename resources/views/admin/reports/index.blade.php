@extends('layouts.admin')

@section('title', 'Attendance Reports')

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
    .report-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .report-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none; margin-bottom: 30px;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-chart-line text-info mr-2"></i>
            Generate Attendance Report
        </h3>
    </div>
    <div class="card-body" style="padding: 30px;">
        <form action="{{ route('admin.reports.generate') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>From Date
                        </label>
                        <input type="date" name="from_date" class="form-control" value="{{ old('from_date') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-calendar-check text-primary mr-2"></i>To Date
                        </label>
                        <input type="date" name="to_date" class="form-control" value="{{ old('to_date') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label style="font-weight: 600; color: #333; margin-bottom: 8px;">
                            <i class="fas fa-user text-primary mr-2"></i>Student (Optional)
                        </label>
                        <select name="user_id" class="form-control">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>{{ $student->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-file-export mr-2"></i>Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

@if(isset($attendances) && $attendances->count() > 0)
<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-table text-success mr-2"></i>
            Report Results
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">Student</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Date</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr class="report-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #333;">
                            {{ $attendance->user->username ?? 'N/A' }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            {{ \Carbon\Carbon::parse($attendance->created_at)->format('M d, Y') }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle;">
                            <span class="badge bg-success text-white" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
