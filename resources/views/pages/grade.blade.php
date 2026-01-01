@extends('layouts.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-info">📚 {{ $grade->name }}th Grade</h2>
        <!-- زر يقود إلى لوحة المتصدرات الخاصة بالصف -->
        <a href="{{ route('pages.leaderboard', $grade->id) }}" class="btn btn-outline-success">
            🏆 View Leaderboard
        </a>
    </div>

    <!-- أسئلة اليوم -->
    <div class="card shadow-lg border-0 mb-5">
        <a href="{{ route('pages.solveIndex', $grade->id) }}" class="text-decoration-none">
            <div class="card-body text-center p-5 todays-card">
                <h3 class="text-primary mb-3">🎉 Solve Unsolved Questions</h3>
                <p class="text-muted">Click to pick a question and a student fairly</p>
            </div>
        </a>
    </div>

    <style>
        .todays-card {
            transition: transform .25s ease, box-shadow .25s ease;
            border-radius: 16px;
            background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);
        }

        .todays-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        }
    </style>

    <h4 class="text-secondary mt-4">📅 Previous Exercises</h4>
    <div class="row">
        @forelse($previousExercises as $exercise)
            <div class="col-md-3 mb-3">
                <a href="{{ route('pages.exercises', [$grade->id, $exercise->created_at->toDateString()]) }}"
                    class="card text-center text-decoration-none shadow-sm border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info">
                            {{ $exercise->created_at->format('d M Y') }}
                        </h5>
                        <p class="text-muted">View solved questions</p>
                    </div>
                </a>
            </div>
        @empty
            <p class="text-muted">No exercises solved yet.</p>
        @endforelse
    </div>
@endsection
