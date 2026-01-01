@extends('layouts.adminlayout')

@section('content')
<div class="container mt-4">
    <h2 class="text-primary mb-4">➕ Add New Grade</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('grades.store') }}" method="POST" class="w-50 mx-auto">
        @csrf
        <div class="mb-3">
            <label class="form-label">Grade Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter grade name" required>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('grades.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection