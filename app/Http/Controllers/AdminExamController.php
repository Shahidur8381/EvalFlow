<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;

class AdminExamController extends Controller
{
    /* ── DASHBOARD ────────────────────────────────── */
    public function dashboard()
    {
        $courses   = Course::all();
        $exams     = Exam::with(['course', 'questions', 'scripts', 'assignedEvaluator'])
                         ->orderByDesc('created_at')
                         ->get();
        $evaluators = User::where('role', 'evaluator')->get();

        return view('admin.dashboard', compact('courses', 'exams', 'evaluators'));
    }

    /* ── COURSES ──────────────────────────────────── */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code',
        ]);

        Course::create($request->only('name', 'code'));
        return redirect()->route('admin.dashboard')->with('success', 'Course created successfully!');
    }

    /* ── EXAMS ────────────────────────────────────── */
    public function storeExam(Request $request)
    {
        // Time validation is handled by the exam.time middleware.
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time'   => 'required|date',
        ]);

        Exam::create($request->only('course_id', 'title', 'start_time', 'end_time'));
        return redirect()->route('admin.dashboard')->with('success', 'Exam created! Now add questions.');
    }

    public function destroyExam(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Exam deleted.');
    }

    /* ── EXAM DETAIL (questions) ──────────────────── */
    public function showExam(Exam $exam)
    {
        $exam->load(['course', 'questions', 'scripts.student', 'assignedEvaluator']);
        $evaluators = User::where('role', 'evaluator')->get();
        return view('admin.exams.show', compact('exam', 'evaluators'));
    }

    /* ── QUESTIONS ────────────────────────────────── */
    public function addQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'body'  => 'required|string',
            'marks' => 'required|integer|min:1|max:999',
        ]);

        $order = $exam->questions()->max('order') + 1;
        $exam->questions()->create([
            'body'  => $request->body,
            'marks' => $request->marks,
            'order' => $order,
        ]);

        return redirect()->route('admin.exams.show', $exam)
                         ->with('success', 'Question added!');
    }

    public function removeQuestion(Exam $exam, Question $question)
    {
        $question->delete();
        return redirect()->route('admin.exams.show', $exam)
                         ->with('success', 'Question removed.');
    }

    /* ── EVALUATOR ASSIGNMENT ─────────────────────── */
    public function assignEvaluator(Request $request, Exam $exam)
    {
        $request->validate([
            'evaluator_id' => 'nullable|exists:users,id',
        ]);

        $exam->update(['assigned_evaluator_id' => $request->evaluator_id ?: null]);

        $msg = $request->evaluator_id
            ? 'Evaluator assigned successfully!'
            : 'Evaluator removed. Scripts are now unassigned.';

        return redirect()->route('admin.exams.show', $exam)->with('success', $msg);
    }
}
