@extends('layouts.admin')

@section('title', 'Grade Management')

@section('content')
<style>
    .grade-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #e0e0e0;
    }
    .grade-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .grade-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .grade-A { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
    .grade-B { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
    .grade-C { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
    .grade-D { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
    .grade-F { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
</style>

<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0; border-radius: 12px 12px 0 0;">
        <h3 class="card-title mb-0" style="font-weight: 600; color: #333;">
            <i class="fas fa-graduation-cap text-info mr-2"></i>
            Student Grades (Auto-calculated from Attendance)
        </h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white;">
                    <tr>
                        <th style="border: none; padding: 15px; font-weight: 600;">#</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Student Name</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Present Days</th>
                        <th style="border: none; padding: 15px; font-weight: 600;">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grades as $studentId => $gradeData)
                    <tr class="grade-card">
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #666;">{{ $loop->iteration }}</td>
                        <td style="padding: 20px; vertical-align: middle; font-weight: 600; color: #333;">
                            {{ $gradeData['name'] }}
                        </td>
                        <td style="padding: 20px; vertical-align: middle; color: #666;">
                            <span class="badge bg-info" style="padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                {{ $gradeData['present'] }} days
                            </span>
                        </td>
                        <td style="padding: 20px; vertical-align: middle;">
                            <span class="grade-badge grade-{{ $gradeData['grade'] }}">
                                {{ $gradeData['grade'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5" style="color: #999;">
                            <i class="fas fa-graduation-cap fa-3x mb-3" style="opacity: 0.3;"></i>
                            <p class="mb-0">No grades available.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 bg-light" style="border-top: 1px solid #e0e0f0;">
            <div class="row">
                <div class="col-md-10">
                    <h5 class="mb-3" style="font-weight: 600; color: #333;">
                        <i class="fas fa-info-circle text-info mr-2"></i>Current Grading Criteria:
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><span class="grade-badge grade-A" style="display: inline-block; width: 40px; text-align: center; margin-right: 10px;">A</span> {{ $gradeA ?? 26 }}+ days present</li>
                                <li><span class="grade-badge grade-B" style="display: inline-block; width: 40px; text-align: center; margin-right: 10px;">B</span> {{ $gradeB ?? 20 }}-{{ ($gradeA ?? 26) - 1 }} days present</li>
                                <li><span class="grade-badge grade-C" style="display: inline-block; width: 40px; text-align: center; margin-right: 10px;">C</span> {{ $gradeC ?? 15 }}-{{ ($gradeB ?? 20) - 1 }} days present</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><span class="grade-badge grade-D" style="display: inline-block; width: 40px; text-align: center; margin-right: 10px;">D</span> {{ $gradeD ?? 10 }}-{{ ($gradeC ?? 15) - 1 }} days present</li>
                                <li><span class="grade-badge grade-F" style="display: inline-block; width: 40px; text-align: center; margin-right: 10px;">F</span> Less than {{ $gradeD ?? 10 }} days</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ route('admin.grades.config') }}" class="btn btn-info" style="border-radius: 8px;">
                        <i class="fas fa-cog mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
