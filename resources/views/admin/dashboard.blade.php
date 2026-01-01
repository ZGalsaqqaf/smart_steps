@extends('layouts.adminlayout')

@section('content')
<div class="container text-center">
    <h1 class="text-secondary mb-4">⚙️ Admin Dashboard</h1>
    <p class="text-muted">Quick links to manage your data</p>

    <div class="row">
        <!-- Grades -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">📚 Grades</h3>
                    <a href="{{ route('grades.index') }}" class="btn btn-info m-2">View All</a>
                    <a href="{{ route('grades.create') }}" class="btn btn-success m-2">Add New</a>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">🏷️ Categories</h3>
                    <a href="{{ route('categories.index') }}" class="btn btn-info m-2">View All</a>
                    <a href="{{ route('categories.create') }}" class="btn btn-success m-2">Add New</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Students -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">👩‍🎓 Students</h3>
                    <a href="{{ route('students.index') }}" class="btn btn-info m-2">View All</a>
                    <a href="{{ route('students.create') }}" class="btn btn-success m-2">Add New</a>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="col-md-6">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title">❓ Questions</h3>
                    <a href="{{ route('questions.index') }}" class="btn btn-info m-2">View All</a>
                    <a href="{{ route('questions.create') }}" class="btn btn-success m-2">Add New</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection