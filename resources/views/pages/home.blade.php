@extends('layouts.layout')

@section('content')
    <h1 class="text-center text-secondary">🏫 Welcome to Smart Steps</h1>
    <p class="text-center text-muted">Choose your grade to start practicing</p>

    <div class="row mt-5 justify-content-center">
        @foreach($grades as $grade)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-info">
                    <div class="card-body text-center">
                        <h4 class="card-title text-info mb-4">📚 {{ $grade->name }}th grade</h4>
                        {{-- <p class="card-text">Click to view details</p> --}}
                        <a href="{{ route('pages.grade', $grade->id) }}" class="btn btn-info">Open</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
