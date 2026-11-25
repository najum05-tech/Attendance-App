@extends('layouts.app')

@section('title', 'Add Grade')

@section('content')
<div class="container">
    <h2 class="mb-3">Add Grade</h2>

    <form action="{{ route('admin.grades.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Student</label>
            <select name="student_id" class="form-control">
                @foreach($students as $student)
                <option value="{{ $student->id }}">{{ $student->username }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control">
        </div>

        <div class="mb-3">
            <label>Grade</label>
            <input type="text" name="grade" class="form-control">
        </div>

        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
