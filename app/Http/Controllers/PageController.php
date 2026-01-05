<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function gradePage(Grade $grade)
    {
        // التمارين السابقة (من جدول attempts)
        $previousExercises = Attempt::whereHas('student', fn($q) => $q->where('grade_id', $grade->id))
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        // أسئلة اليوم
        $todaysQuestions = $grade->questions()
            ->whereDate('created_at', today())
            ->get();

        return view('pages.grade', compact('grade', 'previousExercises', 'todaysQuestions'));
    }

    public function leaderboard(Grade $grade, Request $request)
    {
        // جلب كل الأيام التي فيها محاولات
        $dates = Attempt::whereHas('student', fn($q) => $q->where('grade_id', $grade->id))
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $selectedDate = $request->input('date');

        if ($selectedDate) {
            // النقاط حسب اليوم المحدد فقط
            $students = Student::where('grade_id', $grade->id)
                ->withSum(['attempts as points' => function ($q) use ($selectedDate) {
                    $q->whereDate('created_at', $selectedDate);
                }], 'earned_points')
                ->orderByDesc('points')
                ->get();
        } else {
            // النقاط الكلية (الوضع الافتراضي)
            $students = Student::where('grade_id', $grade->id)
                ->withSum('attempts as points', 'earned_points')
                ->orderByDesc('points')
                ->get();
        }

        return view('pages.leaderboard', compact('grade', 'students', 'dates', 'selectedDate'));
    }

    public function exercises(Grade $grade, $date)
    {
        $attempts = Attempt::whereHas('student', fn($q) => $q->where('grade_id', $grade->id))
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
    public function pickStudentWeighted(Grade $grade, Request $request)
    {
        $questionId = $request->input('question_id'); // نرسل السؤال من الـ JS
        $students = $grade->students()->pluck('id')->toArray();

        $key = "picked_students_{$grade->id}_{$questionId}";
        $alreadyPicked = session()->get($key, []);

        $available = array_diff($students, $alreadyPicked);

        if (empty($available)) {
            $alreadyPicked = [];
            $available = $students;
        }

        $chosen = $available[array_rand($available)];

        $alreadyPicked[] = $chosen;
        session()->put($key, $alreadyPicked);

        return response()->json(['student_id' => $chosen]);
    }

    public function solveIndex(Grade $grade)
    {
        $questions = $this->unsolvedQuestionsForGrade($grade); // من الدالة أعلاه
        $students  = $grade->students()->orderBy('name')->get();

        return view('pages.solve_index', compact('grade', 'questions', 'students'));
    }

    public function solveShow(\App\Models\Question $question, Request $request)
    {
        $studentId = $request->query('student_id');
        $student   = $studentId ? \App\Models\Student::find($studentId) : null;

        return view('pages.solve', compact('question', 'student'));
    }

    public function points(Grade $grade, Request $request)
    {
        $query = Student::where('grade_id', $grade->id)->with('grade');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $students = $query->orderBy('name')->get();

        return view('pages.points', compact('students', 'grade'));
    }

    public function addPoints(Request $request, Student $student)
    {
        $points = $request->input('points', 1);

        Attempt::create([
            'student_id' => $student->id,
            'question_id' => null,
            'answer' => 'manual',
            'is_correct' => true,
            'earned_points' => $points,
        ]);

        return back()->with('success', "Added {$points} points to {$student->name}");
    }
}
