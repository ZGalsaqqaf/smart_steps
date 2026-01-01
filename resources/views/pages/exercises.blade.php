@extends('layouts.layout')

@section('content')
    <h2 class="text-info">
        📅 Exercises for {{ $grade->name }} on {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
    </h2>

    <div class="list-group mt-4">
        @forelse($attempts as $attempt)
            <div class="list-group-item">
                <strong>Question:</strong> {{ $attempt->question->text }} <br>
                <strong>Student:</strong> {{ $attempt->student->name }} <br>
                <strong>Result:</strong>
                @if($attempt->is_correct)
                    ✅ Correct ({{ $attempt->earned_points }} points)
                @else
                    ❌ Incorrect
                @endif
            </div>
        @empty
            <p class="text-muted">No attempts recorded for this date.</p>
        @endforelse
    </div>
@endsection