@extends('layouts.adminlayout')

@section('content')
    <div class="container">
        <h2 class="mb-4">Attempts</h2>

        <form method="GET" action="{{ route('attempts.index') }}" class="mb-3 d-flex">
            <input type="text" name="search" value="{{ $search }}" class="form-control me-2"
                placeholder="🔍 ابحث باسم الطالبة">
            <button class="btn btn-success me-2" type="submit">بحث</button>

            <!-- زر النقاط السالبة -->
            <button class="btn btn-danger" type="submit" name="negative" value="1">
                عرض النقاط السالبة
            </button>
        </form>


        <!-- جدول العرض -->
        <table class="table table-bordered">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>👩‍🎓 Student</th>
                    <th>⭐ Points</th>
                    <th>📅 Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->id }}</td>
                        <td>{{ $attempt->student->name ?? '---' }}</td>
                        <td>{{ $attempt->earned_points }}</td>
                        <td>{{ $attempt->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">لا توجد محاولات بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- روابط الصفحات -->
        {{ $attempts->links() }}
    </div>
@endsection
