@extends('layouts.adminlayout')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">➕ Add New Question</h2>

        <form action="{{ route('questions.store') }}" method="POST">
            @csrf

            <!-- Question Text -->
            <div class="mb-3">
                <label class="form-label">Question Text</label>
                <textarea name="text" class="form-control @error('text') is-invalid @enderror" required>{{ old('text') }}</textarea>
                @error('text')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Points --}}
            <div class="mb-3">
                <label for="default_points" class="form-label">Points (1–5)</label>
                <input type="number" name="default_points" id="default_points" class="form-control" min="1"
                    max="5" value="{{ old('default_points', $question->default_points ?? 1) }}">
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check">
                    <input type="checkbox" name="status" value="1" class="form-check-input"
                        {{ old('status', 1) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>
            </div>

            <!-- Type -->
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select id="questionType" name="type" class="form-control @error('type') is-invalid @enderror" required
                    onchange="renderOptions()">
                    <option value=""></option>
                    <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                    <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice
                    </option>
                    <option value="fill_blank" {{ old('type') == 'fill_blank' ? 'selected' : '' }}>Fill in the Blank
                    </option>
                    <option value="fix_answer" {{ old('type') == 'fix_answer' ? 'selected' : '' }}>Fixed Answer</option>
                </select>
                @error('type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                    <option value="">-- None --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>
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
            <div id="optionsContainer"></div>
            @error('options')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('questions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        function renderOptions() {
            const type = document.getElementById('questionType').value;
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';

            // إذا فيه بيانات قديمة (old options) نعيد رسمها
            let oldOptions = @json(old('options'));
            let correctOption = "{{ old('correct_option') }}";

            if (type === 'true_false') {
                container.innerHTML = `
            <div class="input-group mb-2">
                <input type="text" class="form-control" value="True" disabled>
                <div class="input-group-text"><input type="radio" name="correct_option" value="0" ${correctOption == 0 ? 'checked' : ''}> Correct</div>
            </div>
            <div class="input-group mb-2">
                <input type="text" class="form-control" value="False" disabled>
                <div class="input-group-text"><input type="radio" name="correct_option" value="1" ${correctOption == 1 ? 'checked' : ''}> Correct</div>
            </div>
        `;
            } else if (type === 'fill_blank' || type === 'fix_answer') {
                let html = `<div id="fillBlankOptions">`;
                if (oldOptions) {
                    oldOptions.forEach((opt, index) => {
                        html += `
                    <div class="input-group mb-2">
                        <input type="text" name="options[${index}][text]" class="form-control" value="${opt.text}" required>
                        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
                        <input type="hidden" name="options[${index}][is_correct]" value="1">
                    </div>
                `;
                    });
                } else {
                    html += `
                <div class="input-group mb-2">
                    <input type="text" name="options[0][text]" class="form-control" placeholder="Answer" required>
                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
                    <input type="hidden" name="options[0][is_correct]" value="1">
                </div>
            `;
                }
                html +=
                    `</div><button type="button" class="btn btn-sm btn-outline-primary" onclick="addFillBlankOption()">➕ Add Another Answer</button>`;
                container.innerHTML = html;
            } else if (type === 'multiple_choice') {
                let html = `<div id="mcOptions">`;
                if (oldOptions) {
                    oldOptions.forEach((opt, index) => {
                        html += `
                    <div class="input-group mb-2">
                        <input type="text" name="options[${index}][text]" class="form-control" value="${opt.text}" required>
                        <div class="input-group-text"><input type="radio" name="correct_option" value="${index}" ${correctOption == index ? 'checked' : ''}> Correct</div>
                        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
                    </div>
                `;
                    });
                } else {
                    html += `
                <div class="input-group mb-2">
                    <input type="text" name="options[0][text]" class="form-control" placeholder="Option 1" required>
                    <div class="input-group-text"><input type="radio" name="correct_option" value="0"> Correct</div>
                    <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">🗑️</button>
                </div>
            `;
                }
                html +=
                    `</div><button type="button" class="btn btn-sm btn-outline-primary" onclick="addOption()">➕ Add Option</button>`;
                container.innerHTML = html;
            }
        }

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

        // استدعاء أولي عند تحميل الصفحة
        renderOptions();
    </script>
@endsection
