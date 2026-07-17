<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    if ($role === 'admin')     return redirect()->route('admin.dashboard');
    if ($role === 'evaluator') return redirect()->route('evaluator.dashboard');
    return redirect()->route('student.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* ─────────────────────────────── ADMIN ─────────────────────────── */
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\AdminExamController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/evaluators', [\App\Http\Controllers\AdminUserController::class, 'storeEvaluator'])->name('users.storeEvaluator');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('users.destroy');

    // Courses
    Route::post('/courses', [\App\Http\Controllers\AdminExamController::class, 'storeCourse'])->name('courses.store');

    // Exams
    Route::post('/exams', [\App\Http\Controllers\AdminExamController::class, 'storeExam'])
        ->middleware('exam.time')
        ->name('exams.store');
    Route::get('/exams/{exam}', [\App\Http\Controllers\AdminExamController::class, 'showExam'])->name('exams.show');
    Route::delete('/exams/{exam}', [\App\Http\Controllers\AdminExamController::class, 'destroyExam'])->name('exams.destroy');

    // Questions
    Route::post('/exams/{exam}/questions', [\App\Http\Controllers\AdminExamController::class, 'addQuestion'])->name('exams.questions.store');
    Route::delete('/exams/{exam}/questions/{question}', [\App\Http\Controllers\AdminExamController::class, 'removeQuestion'])->name('exams.questions.destroy');

    // Evaluator assignment
    Route::post('/exams/{exam}/assign', [\App\Http\Controllers\AdminExamController::class, 'assignEvaluator'])->name('exams.assign');

    // Finance
    Route::get('/finance', [\App\Http\Controllers\AdminFinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/withdrawals/{withdrawal}/approve', [\App\Http\Controllers\AdminFinanceController::class, 'approveWithdrawal'])->name('finance.withdrawals.approve');
});

/* ───────────────────────────── EVALUATOR ───────────────────────── */
Route::middleware(['auth', 'verified', 'role:evaluator'])
    ->prefix('evaluator')->name('evaluator.')
    ->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\EvaluatorScriptController::class, 'dashboard'])->name('dashboard');
    Route::get('/scripts/{script}/grade', [\App\Http\Controllers\EvaluatorScriptController::class, 'show'])->name('scripts.show');
    Route::post('/scripts/{script}/grade', [\App\Http\Controllers\EvaluatorScriptController::class, 'storeMarks'])->name('scripts.storeMarks');

    // Finance
    Route::get('/finance', [\App\Http\Controllers\EvaluatorFinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/withdraw', [\App\Http\Controllers\EvaluatorFinanceController::class, 'withdraw'])
        ->middleware('withdraw.min')
        ->name('finance.withdraw');
});

/* ───────────────────────────── STUDENT ─────────────────────────── */
Route::middleware(['auth', 'verified', 'role:student'])
    ->prefix('student')->name('student.')
    ->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\StudentExamController::class, 'dashboard'])->name('dashboard');
    Route::get('/exams/{exam}', [\App\Http\Controllers\StudentExamController::class, 'showExam'])
        ->middleware('exam.questions')
        ->name('exams.show');
    Route::post('/scripts/{exam}/upload', [\App\Http\Controllers\StudentExamController::class, 'uploadScript'])
        ->middleware('exam.questions')
        ->name('scripts.upload');

    // Finance
    Route::get('/finance', [\App\Http\Controllers\StudentFinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/deposit', [\App\Http\Controllers\StudentFinanceController::class, 'initiatePayment'])->name('finance.deposit');
});

// Callbacks for SSLCommerz (must be outside auth group due to SameSite=Lax dropping cookies on cross-origin POST)
Route::post('/student/finance/success', [\App\Http\Controllers\StudentFinanceController::class, 'success'])->name('student.finance.success');
Route::post('/student/finance/fail', [\App\Http\Controllers\StudentFinanceController::class, 'fail'])->name('student.finance.fail');
Route::post('/student/finance/cancel', [\App\Http\Controllers\StudentFinanceController::class, 'cancel'])->name('student.finance.cancel');

/* ───────────────────────────── PROFILE ─────────────────────────── */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
