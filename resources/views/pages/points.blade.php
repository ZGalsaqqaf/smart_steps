@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-success mb-4">⭐ Manage Points</h2>
        <p class="text-muted">Add or deduct points manually for students</p>

        <!-- مربع البحث -->
        <form method="GET" action="{{ route('points.index', $grade->id ?? null) }}" class="mb-3 d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control w-50"
                placeholder="🔍 Search student by name...">
            <button type="submit" class="btn btn-outline-primary">Search</button>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        @if (session('danger'))
            <div class="alert alert-danger">{{ session('danger') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>👩‍🎓 Student</th>
                    <th>Grade</th>
                    <th>Current Points</th>
                    <th>Add</th>
                    <th>Reduce</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->grade->name }}</td>
                        <td>{{ $student->totalPoints() }}</td>
                        <td>
                            <form action="{{ route('points.add', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="number" name="points" value="1" min="1" max="5"
                                    step="1" class="form-control d-inline w-25">
                                <button type="submit" class="btn btn-success btn-sm">➕ Add</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('points.reduce', $student->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="number" name="reducePoints" value="-1" min="-3" max="-1"
                                    step="1" class="form-control d-inline w-25">
                                <button type="submit" class="btn btn-danger btn-sm">➖ Reduce</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
