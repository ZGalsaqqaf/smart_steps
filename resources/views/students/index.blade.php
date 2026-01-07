@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">👩‍🎓 All Students</h2>

        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('students.create') }}" class="btn btn-success">➕ Add New Student</a>

            <a href="{{ route('students.import') }}" class="btn btn-warning">
                📥 Import Students
            </a>
        </div>

        <div class="mb-3">
            <form method="GET" action="{{ route('students.index') }}" class="d-flex gap-2">
                <!-- فلتر حسب grade -->
                <select name="grade_id" class="form-select w-auto">
                    <option value="">All Grades</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                    @endforeach
                </select>

                <!-- زر ترتيب أبجدي -->
                <button type="submit" name="sort" value="name" class="btn btn-outline-primary">
                    Sort by Name (A-Z)
                </button>

                <button type="submit" class="btn btn-secondary">Apply</button>
            </form>
        </div>


        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>points</th>
                    <th>Actions</th>
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
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $student->id }})">
                                Delete
                            </button>
                            <form id="delete-form-{{ $student->id }}"
                                action="{{ route('students.destroy', $student->id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            {{-- <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">View</a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $students->links() }}
        </div>

    </div>
@endsection
