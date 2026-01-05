@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">✏️ Edit Question</h2>

        <form action="{{ route('questions.update', $question->id) }}" method="POST">
            @csrf @method('PUT')

            <!-- Question Text -->
            <div class="mb-3">
                <label class="form-label">Question Text</label>
                <textarea name="text" class="form-control @error('text') is-invalid @enderror" required>{{ old('text', $question->text) }}</textarea>
                @error('text')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="default_points" class="form-label">Points (1–5)</label>
                <input type="number" name="default_points" id="default_points" class="form-control" min="1"
                    max="5" value="{{ old('default_points', $question->default_points ?? 1) }}">
            </div>
            
            <!-- Type (read-only) -->
            <div class="mb-3">
                <label class="form-label">Type</label>
                <input type="text" class="form-control" value="{{ $question->type }}" readonly>
                <input type="hidden" name="type" value="{{ $question->type }}">
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">-- None --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $question->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Grade -->
            <div class="mb-3">
                <label class="form-label">Grade</label>
                <select name="grade_id" class="form-control @error('grade_id') is-invalid @enderror" required>
                    @foreach ($grades as $grade)
                        <option value="{{ $grade->id }}"
                            {{ old('grade_id', $question->grade_id) == $grade->id ? 'selected' : '' }}>
                            {{ $grade->name }}
                        </option>
                    @endforeach
                </select>
                @error('grade_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Options -->
            <h5>Options</h5>
            @error('options')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            @php
                $oldOptions = old('options', $question->options->toArray());
                $correctOption = old('correct_option');
            @endphp

            @if ($question->type === 'true_false')
                <div class="input-group mb-2">
                    <input type="text" class="form-control" value="True" disabled>
                    <div class="input-group-text">
                        <input type="radio" name="correct_option" value="0"
                            {{ ($correctOption !== null ? $correctOption == 0 : $question->options[0]->is_correct) ? 'checked' : '' }}>
                        Correct
                    </div>
                </div>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" value="False" disabled>
                    <div class="input-group-text">
                        <input type="radio" name="correct_option" value="1"
                            {{ ($correctOption !== null ? $correctOption == 1 : $question->options[1]->is_correct) ? 'checked' : '' }}>
                        Correct
                    </div>
                </div>
            @elseif($question->type === 'fill_blank' || $question->type === 'fix_answer')
                <div id="fillBlankOptions">
                    @foreach ($oldOptions as $index => $option)
                        <div class="input-group mb-2">
                            <input type="text" name="options[{{ $index }}][text]" class="form-control"
                                value="{{ $option['text'] }}" required>
                            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
                            <input type="hidden" name="options[{{ $index }}][is_correct]" value="1">
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFillBlankOption()">➕ Add Another
                    Answer</button>
            @elseif($question->type === 'multiple_choice')
                <div id="mcOptions">
                    @foreach ($oldOptions as $index => $option)
                        <div class="input-group mb-2">
                            <input type="text" name="options[{{ $index }}][text]" class="form-control"
                                value="{{ $option['text'] }}" required>
                            <div class="input-group-text">
                                <input type="radio" name="correct_option" value="{{ $index }}"
                                    {{ ($correctOption !== null ? $correctOption == $index : $option['is_correct']) ? 'checked' : '' }}>
                                Correct
                            </div>
                            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">➕ Add Option</button>
            @endif

            <br><br>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('questions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        function addOption() {
            const mcOptions = document.getElementById('mcOptions');
            const index = mcOptions.children.length;
            mcOptions.innerHTML += `
        <div class="input-group mb-2">
            <input type="text" name="options[${index}][text]" class="form-control" placeholder="Option ${index+1}" required>
            <div class="input-group-text"><input type="radio" name="correct_option" value="${index}"> Correct</div>
            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
        </div>
    `;
        }

        function addFillBlankOption() {
            const container = document.getElementById('fillBlankOptions');
            const index = container.children.length;
            container.innerHTML += `
        <div class="input-group mb-2">
            <input type="text" name="options[${index}][text]" class="form-control" placeholder="Answer ${index+1}" required>
            <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
            <input type="hidden" name="options[${index}][is_correct]" value="1">
        </div>
    `;
        }

        function removeOption(button) {
            button.parentElement.remove();
        }
    </script>
@endsection
