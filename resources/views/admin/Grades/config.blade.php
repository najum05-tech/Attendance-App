@extends('layouts.admin')

@section('title', 'Configure Grading System')

@section('content')
<div class="card shadow-sm" style="border-radius: 12px; border: none;">
    <div class="card-header bg-white" style="border-bottom: 2px solid #f0f0f0;">
        <h3 class="mb-0" style="font-weight: 600;">
            <i class="fas fa-cog text-info mr-2"></i>
            Configure Grading Criteria
        </h3>
    </div>
    <div class="card-body" style="padding: 30px;">
        @if(session('success'))
            <div class="alert alert-success" style="border-radius: 8px;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.grades.config.update') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label style="font-weight: 600;">
                            <span class="badge bg-success" style="font-size: 1.2rem; padding: 8px 12px; margin-right: 10px;">A</span>
                            Grade A (Minimum Days)
                        </label>
                        <input type="number" name="grade_a" class="form-control" value="{{ $config['grade_a'] }}" min="0" required>
                        <small class="text-muted">Students with {{ $config['grade_a'] }}+ present days get Grade A</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label style="font-weight: 600;">
                            <span class="badge bg-info" style="font-size: 1.2rem; padding: 8px 12px; margin-right: 10px;">B</span>
                            Grade B (Minimum Days)
                        </label>
                        <input type="number" name="grade_b" class="form-control" value="{{ $config['grade_b'] }}" min="0" required>
                        <small class="text-muted">Students with {{ $config['grade_b'] }}-{{ $config['grade_a']-1 }} present days get Grade B</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label style="font-weight: 600;">
                            <span class="badge bg-warning" style="font-size: 1.2rem; padding: 8px 12px; margin-right: 10px;">C</span>
                            Grade C (Minimum Days)
                        </label>
                        <input type="number" name="grade_c" class="form-control" value="{{ $config['grade_c'] }}" min="0" required>
                        <small class="text-muted">Students with {{ $config['grade_c'] }}-{{ $config['grade_b']-1 }} present days get Grade C</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label style="font-weight: 600;">
                            <span class="badge bg-danger" style="font-size: 1.2rem; padding: 8px 12px; margin-right: 10px;">D</span>
                            Grade D (Minimum Days)
                        </label>
                        <input type="number" name="grade_d" class="form-control" value="{{ $config['grade_d'] }}" min="0" required>
                        <small class="text-muted">Students with {{ $config['grade_d'] }}-{{ $config['grade_c']-1 }} present days get Grade D</small>
                    </div>
                </div>
            </div>
            <div class="alert alert-info mt-3" style="border-radius: 8px;">
                <strong>Note:</strong> Students with less than {{ $config['grade_d'] }} present days will get Grade F.
            </div>
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-save mr-2"></i>Save Configuration
                </button>
                <a href="{{ route('admin.grades.index') }}" class="btn btn-secondary ml-2" style="border-radius: 8px; padding: 10px 30px; font-weight: 600;">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Grades
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

