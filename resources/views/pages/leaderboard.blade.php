@extends('layouts.layout')

@section('content')
    <h2 class="text-center text-success">🏆 Leaderboard - {{ $grade->name }}th Grade</h2>
    {{-- <p class="text-center text-muted">Top performing students</p> --}}

    <p class="text-center text-muted">
        {{ $selectedDate ? 'Top performing students on ' . \Carbon\Carbon::parse($selectedDate)->format('d M Y') : 'Top performing students' }}
    </p>

    <!-- قائمة منسدلة للأيام -->
    <form method="GET" class="text-center mb-4">
        <div class="dropdown-wrapper">
            <select name="date" class="form-select custom-select" onchange="this.form.submit()">
                <option value="">🌍 All Time</option>
                @foreach ($dates as $d)
                    <option value="{{ $d->date }}" {{ $selectedDate == $d->date ? 'selected' : '' }}>
                        📅 {{ \Carbon\Carbon::parse($d->date)->format('d M Y') }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <table class="table table-bordered mt-4">
        <thead class="table-success">
            <tr>
                <th style="width: 60px;">#</th>
                <th>👩‍🎓 Student</th>
                <th>⭐ Points</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1; // loop counter
                $denseRank = 1;
                $prevPoints = null;
            @endphp

            @forelse($students as $student)
                @php
                    $currentPoints = (int) ($student->points ?? 0);
                    if ($prevPoints === null) {
                        $denseRank = 1;
                    } elseif ($currentPoints < $prevPoints) {
                        $denseRank++;
                    }
                    $prevPoints = $currentPoints;
                @endphp

                <tr>
                    <td style="width: 60px; text-align: center;">
                        {{ $counter }}
                        @if ($student->points > 0)
                            @if ($denseRank == 1)
                                🥇
                            @elseif($denseRank == 2)
                                🥈
                            @elseif($denseRank == 3)
                                🥉
                            @endif
                        @endif
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $currentPoints }}</td>
                </tr>
                @php $counter++; @endphp
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">No students yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <style>
        .custom-select {
            display: inline-block;
            width: auto;
            min-width: 220px;
            padding: 10px 14px;
            font-size: 1.1rem;
            border-radius: 12px;
            border: 2px solid #28a746;
            /* أخضر أنيق */
            background-color: #f9fff9;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .custom-select:hover {
            border-color: #218838;
            background-color: #eaffea;
        }

        .custom-select:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.6);
        }
    </style>
@endsection
