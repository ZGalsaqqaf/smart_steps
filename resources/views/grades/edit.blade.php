@extends('layouts.adminlayout')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary mb-4">✏️ Edit Grade</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('grades.update', $grade->id) }}" method="POST" class="w-50 mx-auto">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Grade Name</label>
            <input type="text" name="name" value="{{ $grade->name }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('grades.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection