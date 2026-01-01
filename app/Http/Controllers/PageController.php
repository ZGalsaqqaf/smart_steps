<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Grade;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function gradePage(Grade $grade)
    {
        // التمارين السابقة (من جدول attempts)
        $previousExercises = Attempt::whereHas('student', fn($q) => $q->where('grade_id', $grade->id))
            ->with('question')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // أسئلة اليوم
        $todaysQuestions = $grade->questions()
            ->whereDate('created_at', today())
            ->get();

        return view('pages.grade', compact('grade', 'previousExercises', 'todaysQuestions'));
    }

    public function leaderboard(Grade $grade)
{
    $students = $grade->students()->with('attempts')->get()
        ->sortByDesc(fn($s) => $s->totalPoints())
        ->values(); // مهم: يعيد الفهارس من 0,1,2...
    
    return view('pages.leaderboard', compact('grade', 'students'));
}

    public function exercises(Grade $grade, $date)
    {
        $attempts = \App\Models\Attempt::whereHas('student', fn($q) => $q->where('grade_id', $grade->id))
            ->whereDate('created_at', $date)
            ->with(['question', 'student'])
            ->get();

        return view('pages.exercises', compact('grade', 'date', 'attempts'));
    }

    // في PageController
    public function unsolvedQuestionsForGrade(Grade $grade)
    {
        return $grade->questions()
            ->whereDoesntHave('attempts', function ($q) use ($grade) {
                $q->where('is_correct', true)
                    ->whereHas('student', fn($s) => $s->where('grade_id', $grade->id));
            })
            ->orderBy('created_at', 'asc') // ترتيب الإنشاء
            ->with(['options', 'category'])
            ->get();
    }

    // في PageController
    public function pickStudentWeighted(Grade $grade)
    {
        $students = $grade->students()->with(['attempts' => function ($q) {
            $q->whereDate('created_at', '>=', now()->subDays(7));
        }])->get();

        // آخر مشاركة في هذا الصف
        $lastAttempt = \App\Models\Attempt::whereHas('student', fn($q) => $q->where('grade_id', $grade->id))
            ->latest('created_at')->first();

        $weights = [];
        foreach ($students as $s) {
            $base = 1.0;
            $recentAttemptsCount = $s->attempts->count();               // محاولات آخر 7 أيام
            $penalty = 0.15 * $recentAttemptsCount;                     // خصم بسيط لكل محاولة حديثة
            $bonus = ($lastAttempt && $lastAttempt->student_id !== $s->id) ? 0.20 : 0.0; // نقطة لمن لم يكن آخر مختار
            $weight = max(0.10, $base - $penalty + $bonus);             // لا تقل عن 0.10

            $weights[] = ['id' => $s->id, 'name' => $s->name, 'w' => $weight];
        }

        // اختيار عشوائي حسب الوزن
        $sum = array_sum(array_column($weights, 'w'));
        $r = mt_rand() / mt_getrandmax() * $sum;
        $acc = 0;
        $chosen = $weights[0]['id'];
        foreach ($weights as $item) {
            $acc += $item['w'];
            if ($r <= $acc) {
                $chosen = $item['id'];
                break;
            }
        }

        return response()->json(['student_id' => $chosen]);
    }

    public function solveIndex(Grade $grade)
    {
        $questions = $this->unsolvedQuestionsForGrade($grade); // من الدالة أعلاه
        $students  = $grade->students()->orderBy('name')->get();

        return view('pages.solve_index', compact('grade', 'questions', 'students'));
    }

    public function solveShow(\App\Models\Question $question, \Illuminate\Http\Request $request)
    {
        $studentId = $request->query('student_id');
        $student   = $studentId ? \App\Models\Student::find($studentId) : null;

        return view('pages.solve', compact('question', 'student'));
    }
}
