<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Script;

class StudentExamController extends Controller
{
    public function dashboard()
    {
        $exams = Exam::with(['course', 'questions'])
                     ->orderByDesc('start_time')
                     ->get();

        // Map exam_id => script for this student
        $myScripts = Script::where('student_id', auth()->id())
                           ->with('exam')
                           ->get()
                           ->keyBy('exam_id');

        return view('student.dashboard', compact('exams', 'myScripts'));
    }

    public function showExam(Exam $exam)
    {
        $exam->load(['course', 'questions']);
        $myScript = Script::where('student_id', auth()->id())
                          ->where('exam_id', $exam->id)
                          ->with('scriptMarks')
                          ->first();

        // If not submitted yet, check if they have enough balance to participate
        if (!$myScript && auth()->user()->balance < $exam->total_marks) {
            return redirect()->route('student.dashboard')->with('error', 'Insufficient credits to view this exam. You need ' . $exam->total_marks . ' credits. Please top up your wallet.');
        }

        return view('student.exam', compact('exam', 'myScript'));
    }

    public function uploadScript(Request $request, Exam $exam)
    {
        $request->validate([
            'answer_script' => 'required|mimes:pdf|max:20480',
        ]);

        $now = now();
        if ($now < $exam->start_time || $now > $exam->end_time) {
            return back()->with('error', 'Uploads are only allowed within the exam time window.');
        }

        if (Script::where('student_id', auth()->id())->where('exam_id', $exam->id)->exists()) {
            return back()->with('error', 'You have already submitted an answer script for this exam.');
        }

        $user = auth()->user();

        if ($user->balance < $exam->total_marks) {
            return back()->with('error', 'Insufficient credits. You need ' . $exam->total_marks . ' credits to submit this exam. Please top up your wallet.');
        }

        $path = $request->file('answer_script')->store('scripts', 'public');

        // Deduct balance and log transaction
        $user->decrement('balance', $exam->total_marks);
        \App\Models\Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'exam_fee',
            'amount'      => $exam->total_marks,
            'status'      => 'completed',
            'description' => 'Exam fee deduction for: ' . $exam->title,
        ]);

        Script::create([
            'exam_id'    => $exam->id,
            'student_id' => $user->id,
            'file_path'  => $path,
            'status'     => 'pending',
        ]);

        return redirect()->route('student.dashboard')
                         ->with('success', 'Answer script uploaded successfully for "' . $exam->title . '"!');
    }
}
