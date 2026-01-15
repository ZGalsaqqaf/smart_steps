<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Question;
use App\Models\Student;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attempts = Attempt::with(['student', 'question'])->paginate(10);
        return view('attempts.index', compact('attempts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'student_id'  => 'required|exists:students,id',
            'answer'      => 'required|string',
        ]);

        $question = Question::with('options')->findOrFail($request->question_id);

        // التحقق من صحة الإجابة
        $isCorrect = false;
        if ($question->type === 'true_false' || $question->type === 'multiple_choice') {
            $correctOption = $question->options()->where('is_correct', true)->first();
            $isCorrect = $correctOption && strtolower($correctOption->text) === strtolower($request->answer);
        } elseif ($question->type === 'fill_blank' || $question->type === 'fix_answer') {
            $correctOptions = $question->options()->where('is_correct', true)->pluck('text')->map(fn($t) => strtolower(trim($t)));
            $isCorrect = $correctOptions->contains(strtolower(trim($request->answer)));
        }

        // ✅ حساب النقاط
        if ($isCorrect) {
            // إذا صحيحة → تأخذ كامل النقاط
            $earnedPoints = $question->default_points ?? 5;
        } else {
            // إذا خطأ → ننقص نقطة من قيمة السؤال
            $newPoints = max(($question->default_points ?? 5) - 1, 0);
            $earnedPoints = $newPoints;

            // نحدث قيمة السؤال نفسها بحيث تقل المرة القادمة
            $question->update(['default_points' => $newPoints]);
        }

        Attempt::create([
            'student_id'    => $request->student_id,
            'question_id'   => $request->question_id,
            'answer'        => $request->answer,
            'is_correct'    => $isCorrect,
            'earned_points' => $earnedPoints,
        ]);

        // منطق الإبطال
        $questionInactive = false;

        if ($question->type === 'true_false') {
            // يبطل فور أول خطأ
            if (!$isCorrect) {
                $question->update(['status' => false]);
                $questionInactive = true;
            }
        } else {
            // يبطل بعد ثاني خطأ
            $wrongCount = Attempt::where('question_id', $question->id)
                ->where('is_correct', false)
                ->count();

            if ($wrongCount >= 2) {
                $question->update(['status' => false]);
                $questionInactive = true;
            }
        }

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'earned_points' => $earnedPoints,
            'message' => $isCorrect ? 'Correct! 🎉' : 'Incorrect ❌',
            'question_inactive' => $questionInactive, // ✅ جديد
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // عرض محاولات طالب معين
    public function studentAttempts(Student $student)
    {
        $attempts = Attempt::where('student_id', $student->id)->with('question')->get();
        return view('attempts.student', compact('student', 'attempts'));
    }

    // عرض محاولات سؤال معين
    public function questionAttempts(Question $question)
    {
        $attempts = Attempt::where('question_id', $question->id)->with('student')->get();
        return view('attempts.question', compact('question', 'attempts'));
    }
}
