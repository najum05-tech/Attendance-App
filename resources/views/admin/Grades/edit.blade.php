@extends('layouts.app')

@section('title', 'Edit Grade')

@section('content')
<div class="container">
    <h2 class="mb-3">Edit Grade</h2>

    <form action="{{ route('admin.grades.update', $grade->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Subject</label>
            <input type="text" name="subject" value="{{ $grade->subject }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Grade</label>
            <input type="text" name="grade" value="{{ $grade->grade }}" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
