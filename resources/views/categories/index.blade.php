@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-secondary mb-4">📚 All categories</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $category->id }})">
                                Delete
                            </button>

                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $categories->links() }}
        </div>

        <a href="{{ route('categories.create') }}" class="btn btn-success">➕ Add New category</a>
    </div>
@endsection

