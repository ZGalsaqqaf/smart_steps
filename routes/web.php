<?php

use App\Http\Controllers\AttemptController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GradeController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// صفحة الترتيب (Leaderboard)
Route::get('/pages/leaderboard/{grade}', [PageController::class, 'leaderboard'])
    ->name('pages.leaderboard');


// صفحة حل الأسئلة (Solve)
Route::get('/solve', function () {
    return view('pages.solve');
});


Route::resource('grades', GradeController::class);
Route::resource('students', StudentController::class);
Route::resource('questions', QuestionController::class);
Route::resource('options', OptionController::class);
Route::resource('attempts', AttemptController::class)->only(['index', 'store']);
Route::resource('categories', CategoryController::class);

Route::get('/pages/grade/{grade}', [PageController::class, 'gradePage'])->name('pages.grade');
Route::get('/pages/exercises/{grade}/{date}', [PageController::class, 'exercises'])
    ->name('pages.exercises');
Route::get('/pages/leaderboard/{grade}', [PageController::class, 'leaderboard'])
    ->name('pages.leaderboard');
// web.php
Route::get('/pages/grade/{grade}/solve', [PageController::class, 'solveIndex'])
    ->name('pages.solveIndex'); // صفحة اختيار سؤال وطالبة وعرض غير المحلول

Route::post('/pages/grade/{grade}/pick-student', [PageController::class, 'pickStudentWeighted'])
    ->name('pages.pickStudentWeighted'); // اختيار تلقائي بوزن

// مسار حل سؤال لطالبة معينة (واجهة العرض/الاختبار الفعلية)
Route::get('/pages/solve/{question}', [PageController::class, 'solveShow'])
    ->name('pages.solve'); // يعتمد على ?student_id=

    // عرض صفحة النقاط
Route::get('/points/{grade}', [PageController::class, 'points'])->name('points.index');

// إضافة نقاط لطالب محدد
Route::post('/points/add/{student}', [PageController::class, 'addPoints'])->name('points.add');
Route::post('/points/reduce/{student}', [PageController::class, 'reducePoints'])->name('points.reduce');
