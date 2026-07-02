<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'evaluator') return redirect()->route('evaluator.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminExamController::class, 'dashboard'])->name('dashboard');
    Route::post('/courses', [\App\Http\Controllers\AdminExamController::class, 'storeCourse'])->name('courses.store');
    Route::post('/exams', [\App\Http\Controllers\AdminExamController::class, 'storeExam'])->name('exams.store');
});

// Evaluator Routes
Route::middleware(['auth', 'verified', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('evaluator.dashboard');
    })->name('dashboard');
});

// Student Routes
Route::middleware(['auth', 'verified', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\StudentExamController::class, 'dashboard'])->name('dashboard');
    Route::post('/scripts/{exam}/upload', [\App\Http\Controllers\StudentExamController::class, 'uploadScript'])->name('scripts.upload');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
