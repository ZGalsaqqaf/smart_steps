@extends('layouts.layout')

@section('content')
    <h2 class="text-center text-success">🏆 Leaderboard - {{ $grade->name }}th Grade</h2>
    <p class="text-center text-muted">Top performing students</p>

    <table class="table table-bordered mt-4">
        <thead class="table-success">
            <tr>
                <th style="width: 60px;">#</th> {{-- عمود ضيق --}}
                <th>👩‍🎓 Student</th>
                <th>⭐ Points</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $index => $student)
                <tr>
                    <td style="width: 60px;"> {{-- نفس العرض للـ td --}}
                        {{ $index + 1 }}
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @endif
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->totalPoints() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No students yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection