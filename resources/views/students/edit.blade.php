@extends('layouts.adminlayout')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary mb-4">✏️ Edit Student</h2>

    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Grade</label>
            <select name="grade_id" class="form-control" required>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}" {{ $student->grade_id == $grade->id ? 'selected' : '' }}>
                        {{ $grade->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection