<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Script;

class StudentExamController extends Controller
{
    public function dashboard()
    {
        $exams = Exam::with('course')->get();
        // Get user's submitted scripts to see which ones they already uploaded
        $submittedScriptExamIds = Script::where('student_id', auth()->id())->pluck('exam_id')->toArray();

        return view('student.dashboard', compact('exams', 'submittedScriptExamIds'));
    }

    public function uploadScript(Request $request, Exam $exam)
    {
        $request->validate([
            'answer_script' => 'required|mimes:pdf|max:10240', // Max 10MB PDF
        ]);

        $now = now();
        if ($now < $exam->start_time || $now > $exam->end_time) {
            return back()->with('error', 'You can only upload scripts during the exam time window.');
        }

        if (Script::where('student_id', auth()->id())->where('exam_id', $exam->id)->exists()) {
            return back()->with('error', 'You have already submitted an answer script for this exam.');
        }

        $path = $request->file('answer_script')->store('scripts', 'public');

        Script::create([
            'exam_id' => $exam->id,
            'student_id' => auth()->id(),
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Answer script uploaded successfully!');
    }
}
