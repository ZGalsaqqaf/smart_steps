@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <a href="{{ route('students.index') }}" class="btn btn-secondary mt-3">⬅️ Back to Students</a>
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">📥 Import Students from CSV</h4>
            </div>
            <div class="card-body">
                <!-- رسالة نجاح أو خطأ -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- فورم رفع الملف -->
                <form action="{{ route('students.import.csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose CSV File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".csv,.txt" required>
                    </div>
                    <button type="submit" class="btn btn-success">
                        🚀 Import Students
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
