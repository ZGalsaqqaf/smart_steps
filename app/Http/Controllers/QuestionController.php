<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Grade;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Question::with('options', 'grade', 'category');

        if ($request->filled('grade_id')) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // $questions = $query->get();
        $questions = $query->paginate(10);
        $grades = Grade::all();
        $categories = Category::all();

        return view('questions.index', compact('questions', 'grades', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::all();
        $categories = Category::all();

        return view('questions.create', compact('grades', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|string',
            'grade_id' => 'required|exists:grades,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            $question = Question::create($request->only('text', 'type', 'category', 'grade_id', 'category_id'));

            if ($request->type === 'true_false') {
                Option::create([
                    'text' => 'True',
                    'is_correct' => ($request->correct_option == 0),
                    'question_id' => $question->id,
                ]);
                Option::create([
                    'text' => 'False',
                    'is_correct' => ($request->correct_option == 1),
                    'question_id' => $question->id,
                ]);
            } elseif ($request->type === 'fill_blank' || $request->type === 'fix_answer') {
                if (empty($request->options)) {
                    throw new \Exception("Options required for fill_blank/fix_answer");
                }
                foreach ($request->options as $option) {
                    Option::create([
                        'text' => $option['text'],
                        'is_correct' => true,
                        'question_id' => $question->id,
                    ]);
                }
            } elseif ($request->type === 'multiple_choice') {
                if (empty($request->options)) {
                    throw new \Exception("Options required for multiple_choice");
                }
                foreach ($request->options as $index => $option) {
                    Option::create([
                        'text' => $option['text'],
                        'is_correct' => ($index == $request->correct_option),
                        'question_id' => $question->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('questions.index')->with('success', 'Question added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['options' => 'You must provide valid options for this question type.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $grades = Grade::all();
        $categories = Category::all();

        $question->load('options');
        return view('questions.edit', compact('question', 'grades', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'text' => 'required|string',
            'type' => 'required|string',
            'grade_id' => 'required|exists:grades,id',
            'category_id' => 'required|exists:categories,id',

        ]);

        DB::beginTransaction();
        try {
            $question->update($request->only('text', 'type', 'category', 'grade_id', 'category_id'));

            // نحذف الخيارات القديمة ونضيف الجديدة
            $question->options()->delete();

            if ($request->type === 'true_false') {
                Option::create([
                    'text' => 'True',
                    'is_correct' => ($request->correct_option == 0),
                    'question_id' => $question->id,
                ]);
                Option::create([
                    'text' => 'False',
                    'is_correct' => ($request->correct_option == 1),
                    'question_id' => $question->id,
                ]);
            } elseif ($request->type === 'fill_blank' || $request->type === 'fix_answer') {
                if (empty($request->options)) {
                    throw new \Exception("Options required for fill_blank/fix_answer");
                }
                foreach ($request->options as $option) {
                    Option::create([
                        'text' => $option['text'],
                        'is_correct' => true,
                        'question_id' => $question->id,
                    ]);
                }
            } elseif ($request->type === 'multiple_choice') {
                if (empty($request->options)) {
                    throw new \Exception("Options required for multiple_choice");
                }
                foreach ($request->options as $index => $option) {
                    Option::create([
                        'text' => $option['text'],
                        'is_correct' => ($index == $request->correct_option),
                        'question_id' => $question->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('questions.index')->with('success', 'Question updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['options' => 'You must provide valid options for this question type.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully!');
    }
}
