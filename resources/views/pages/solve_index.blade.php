@extends('layouts.layout')

@section('content')
    <h2 class="text-primary">📝 Solve - {{ $grade->name }}th Grade</h2>

    <div class="row mt-4">
        <!-- Choose Question -->
        <div class="col-md-8">
            <div class="card p-3 h-100">
                <h5>Choose Question</h5>
                <select id="questionSelect" class="form-select mb-3">
                    <option value="">-- Select a Question --</option>
                    @foreach ($questions as $q)
                        <option value="{{ $q->id }}" data-type="{{ $q->type }}">
                            {{ Str::limit($q->text, 80) }}
                        </option>
                    @endforeach
                </select>
                <button id="autoQuestion" class="btn btn-outline-primary">Auto-pick earliest unsolved</button>
            </div>
        </div>

        <!-- Choose Student -->
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h5>Choose Student</h5>
                <select id="studentSelect" class="form-select mb-3">
                    <option value="">Auto (weighted fairness)</option>
                    @foreach ($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
                <button id="spinWheel" class="btn btn-success">Spin 🎡</button>
                <div id="winnerName" class="mt-3 text-success fw-bold"></div>
            </div>
        </div>
    </div>

    <!-- مربع الإجابة -->
    <div id="questionArea" class="card p-5 mt-4 d-none" style="background: #fdf6e3; border: 2px solid #f1c40f;">
        <h4 id="questionText" class="text-dark mb-4"></h4>
        <div id="questionOptions" class="mt-3"></div>
        <button id="submitAnswer" class="btn btn-lg btn-warning mt-4">Submit Answer 🚀</button>
    </div>

    <!-- Overlay للنتيجة -->
    <div id="resultOverlay" class="overlay d-none">
        <div class="overlay-content">
            <h1 id="resultMessage"></h1>
            <p id="resultPoints"></p>
            <button onclick="hideOverlay()" class="btn btn-light">Continue</button>
        </div>
    </div>

    <style>
        .option-card {
            padding: 20px;
            margin: 10px 0;
            border: 2px solid #ccc;
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
            font-size: 1.2rem;
            background: #fdfdfd;
            transition: all 0.2s ease;
        }

        .option-card.selected {
            background: #007bff;
            color: white;
            border-color: #0056b3;
        }

        .option-card:hover {
            background: rgba(241, 241, 241, 0.63);
            border-color: #007bff;
        }

        /* عجلة صغيرة ممتعة */
        .wheel {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(#fde68a, #fff3bf, #fff8e1, #fde68a);
            margin: auto;
        }

        .wheel.spin {
            animation: spin 1.2s ease-out;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(720deg);
            }
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .overlay-content {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .overlay-content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#studentSelect').select2({
                placeholder: "🔍 Search student...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>
        const questions = @json($questions);
        const students = @json($students->pluck('name', 'id'));
        const gradeId = {{ $grade->id }};

        // اختيار السؤال تلقائيًا
        document.getElementById('autoQuestion').onclick = () => {
            if (questions.length) {
                document.getElementById('questionSelect').value = questions[0].id;
                renderQuestion(questions[0]);
            }
        };

        // عند اختيار سؤال من القائمة
        document.getElementById('questionSelect').onchange = (e) => {
            const qId = e.target.value;
            const q = questions.find(q => q.id == qId);
            if (q) renderQuestion(q);
        };


        // اختيار الطالب بالعجلة
        document.getElementById('spinWheel').onclick = async () => {
            const qId = document.getElementById('questionSelect').value;
            const chosenManual = document.getElementById('studentSelect').value;
            let studentId;

            if (chosenManual) {
                studentId = chosenManual;
            } else {
                const res = await fetch(`/pages/grade/${gradeId}/pick-student`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        question_id: qId
                    }) // مهم: نرسل السؤال
                });
                const data = await res.json();
                studentId = data.student_id;
            }

            // تحديث العرض كل مرة
            const wheel = document.createElement('div');
            wheel.className = 'wheel spin';
            document.getElementById('winnerName').innerHTML = '';
            document.getElementById('winnerName').appendChild(wheel);

            setTimeout(() => {
                wheel.classList.remove('spin');
                document.getElementById('winnerName').textContent = students[studentId];
                document.getElementById('studentSelect').value = studentId;
            }, 1300);
        };

        // إرسال الإجابة
        document.getElementById('submitAnswer').onclick = async () => {
            const qId = document.getElementById('questionSelect').value;
            const sId = document.getElementById('studentSelect').value;
            let answer;

            // إذا السؤال اختياري (cards)
            answer = document.getElementById('questionOptions').dataset.selected;

            // إذا السؤال نصي (textarea)
            if (!answer) {
                answer = document.querySelector('[name="answer"]')?.value;
            }

            if (!qId || !sId || !answer) {
                showOverlay('⚠️ Please select question, student, and answer.', '');
                return;
            }

            const res = await fetch('/attempts', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: qId,
                    student_id: sId,
                    answer
                })
            });
            const data = await res.json();

            showOverlay(data.message, `Points: ${data.earned_points}`);

            // ✅ هنا التعديل
            if (data.is_correct || data.question_inactive) {
                // إذا الإجابة صحيحة أو السيرفر قال إن السؤال انتهى
                const idx = questions.findIndex(q => q.id == qId);
                if (idx !== -1) questions.splice(idx, 1);
                document.querySelector(`#questionSelect option[value="${qId}"]`)?.remove();
                document.getElementById('questionArea').classList.add('d-none');
                document.getElementById('studentSelect').value = "";
                document.getElementById('winnerName').textContent = "";
            }


        };
        // Overlay functions
        function showOverlay(message, points) {
            document.getElementById('resultMessage').textContent = message;
            document.getElementById('resultPoints').textContent = points;
            document.getElementById('resultOverlay').classList.remove('d-none');
        }

        function hideOverlay() {
            document.getElementById('resultOverlay').classList.add('d-none');
        }

        function renderQuestion(q) {
            document.getElementById('questionArea').classList.remove('d-none');
            document.getElementById('questionText').textContent = q.text;
            const container = document.getElementById('questionOptions');
            container.innerHTML = '';

            if (q.type === 'true_false') {
                container.innerHTML = `
            <div class="option-card" data-value="True">✅ True</div>
            <div class="option-card" data-value="False">❌ False</div>
        `;
            } else if (q.type === 'multiple_choice') {
                q.options.forEach(opt => {
                    container.innerHTML += `
                <div class="option-card" data-value="${opt.text}">${opt.text}</div>
            `;
                });
            } else if (q.type === 'fill_blank' || q.type === 'fix_answer') {
                container.innerHTML =
                    `<textarea name="answer" class="form-control" rows="3" placeholder="Write your answer here"></textarea>`;
            }

            // إضافة حدث للنقر على المربعات
            document.querySelectorAll('.option-card').forEach(card => {
                card.onclick = () => {
                    // إزالة التحديد من البقية
                    document.querySelectorAll('.option-card').forEach(c => c.classList.remove('selected'));
                    // تحديد الحالي
                    card.classList.add('selected');
                    // حفظ القيمة المختارة في dataset
                    container.dataset.selected = card.dataset.value;
                };
            });
        }
    </script>
@endsection
