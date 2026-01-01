@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">📚 All Grades</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grades as $grade)
                    <tr>
                        <td>{{ $grade->id }}</td>
                        <td>{{ $grade->name }}th grade</td>
                        <td>
                            <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $grade->id }})">
                                Delete
                            </button>

                            <form id="delete-form-{{ $grade->id }}" action="{{ route('grades.destroy', $grade->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('grades.create') }}" class="btn btn-success">➕ Add New Grade</a>
    </div>
@endsection

