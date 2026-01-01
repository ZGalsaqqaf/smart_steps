<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::all();
        return view('grades.index', compact('grades'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('grades.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:grades,name',
        ]);

        Grade::create($request->only('name'));

        return redirect()->route('grades.index')->with('success', 'Grade created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        return view('grades.show', compact('grade'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        return view('grades.edit', compact('grade'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:grades,name,' . $grade->id,
        ]);

        $grade->update($request->only('name'));

        return redirect()->route('grades.index')->with('success', 'Grade updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Grade deleted successfully!');
    }

    public function home()
{
    $grades = Grade::all(); // جلب كل الصفوف من قاعدة البيانات
    return view('pages.home', compact('grades'));
}

}
