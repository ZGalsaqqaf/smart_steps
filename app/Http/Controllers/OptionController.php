<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Question $question)
    {
        $options = $question->options; // جلب الخيارات المرتبطة بالسؤال
        return view('options.index', compact('question', 'options'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Question $question)
    {
        return view('options.create', compact('question'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Question $question)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
        ]);

        $question->options()->create($request->only('text', 'is_correct'));

        return redirect()->route('options.index', $question->id)
                         ->with('success', 'Option created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question, Option $option)
    {
        return view('options.edit', compact('question', 'option'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Question $question, Request $request, Option $option)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
        ]);

        $option->update($request->only('text', 'is_correct'));

        return redirect()->route('options.index', $question->id)
                         ->with('success', 'Option updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question, Option $option)
    {
        $option->delete();
        return redirect()->route('options.index', $question->id)
                         ->with('success', 'Option deleted successfully!');
    }
}
