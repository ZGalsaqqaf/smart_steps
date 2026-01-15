@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">📚 All Questions</h2>

        <a href="{{ route('questions.create') }}" class="btn btn-success">➕ Add New Question</a>
        <br><br>
        <!-- Filters -->
        <form method="GET" action="{{ route('questions.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Grade</label>
                <select name="grade_id" class="form-control">
                    <option value="">-- All --</option>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-control">
                    <option value="">-- All --</option>
                    <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                    <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple
                        Choice</option>
                    <option value="fill_blank" {{ request('type') == 'fill_blank' ? 'selected' : '' }}>Fill in the Blank
                    </option>
                    <option value="fix_answer" {{ request('type') == 'fix_answer' ? 'selected' : '' }}>Fixed Answer</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control">
                    <option value="">-- All --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>


        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Text</th>
                    <th>Points</th>
                    <td>Status</td>
                    <th>Type</th>
                    <th>Grade</th>
                    <th>Category</th>
                    <th>Options</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $question)
                    <tr>
                        <td>{{ $question->id }}</td>
                        <td>{{ $question->text }}</td>
                        <td>{{ $question->default_points }}</td>
                        <td>{{ $question->status }}</td>
                        <td>{{ $question->type }}</td>
                        <td>{{ $question->grade->name }}</td>
                        <td>{{ $question->category->name }}</td>
                        <td>
                            @foreach ($question->options as $option)
                                <div>
                                    {{ $option->text }}
                                    @if ($option->is_correct)
                                        ✅
                                    @else
                                        ❌
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="confirmDelete({{ $question->id }})">Delete</button>
                            <form id="delete-form-{{ $question->id }}"
                                action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                style="display:none;">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $questions->links() }}
        </div>


    </div>
@endsection
