<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with('grade');

        // فلتر حسب grade
        if ($request->has('grade_id') && $request->grade_id != '') {
            $query->where('grade_id', $request->grade_id);
        }

        // ترتيب أبجدي
        if ($request->has('sort') && $request->sort == 'name') {
            $query->orderBy('name', 'asc');
        }

        $students = $query->paginate(10);
        $grades = Grade::all();

        return view('students.index', compact('students', 'grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::all();
        return view('students.create', compact('grades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:students,name,',
            'grade_id' => 'required|exists:grades,id',
        ]);

        Student::create($request->only('name', 'grade_id'));

        return redirect()->route('students.index')->with('success', 'Student created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $grades = Grade::all();
        return view('students.edit', compact('student', 'grades'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:students,name,' . $student->id,
            'grade_id' => 'required|exists:grades,id',
        ]);

        $student->update($request->only('name', 'grade_id'));

        return redirect()->route('students.index')->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }

    public function addPoints(Request $request, Student $student)
    {
        $points = $request->input('points', 1); // افتراضي نقطة واحدة

        Attempt::create([
            'student_id' => $student->id,
            'question_id' => null,
            'answer' => 'manual',
            'is_correct' => true,
            'earned_points' => $points,
        ]);

        return response()->json([
            'message' => "Added {$points} points to {$student->name}",
            'new_total' => $student->totalPoints(),
        ]);
    }

    public function showImportForm()
    {
        return view('students.import');
    }

    public function importCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file')->getPathname(), 'r');
        $header = fgetcsv($file); // قراءة رؤوس الأعمدة

        while (($row = fgetcsv($file)) !== false) {
            if (empty($row[0])) continue; // تجاهل الصفوف الفارغة
            // تحديث أو إنشاء طالب جديد حسب الاسم
            Student::updateOrCreate(
                ['name' => $row[0]], // الشرط: الاسم لازم يكون unique
                [
                    'grade_id' => $row[1],
                    'points'   => $row[2] ?? 0,
                ]
            );
        }

        fclose($file);

        return redirect()->route('students.index')->with('success', 'Successfully imported students: added {{ $newCount }} new and updated {{ $updatedCount }} existing.');
    }
}
