<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Script;
use App\Models\ScriptMark;

class EvaluatorScriptController extends Controller
{
    public function dashboard()
    {
        $scripts = Script::whereHas('exam', function ($q) {
                        $q->where('assigned_evaluator_id', auth()->id());
                    })
                    ->with(['exam.course', 'exam.questions', 'student', 'scriptMarks'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $pending   = $scripts->where('status', 'pending')->count();
        $evaluated = $scripts->where('status', 'evaluated')->count();

        return view('evaluator.dashboard', compact('scripts', 'pending', 'evaluated'));
    }

    public function show(Script $script)
    {
        if ($script->exam->assigned_evaluator_id !== auth()->id()) {
            abort(403, 'You are not assigned to evaluate this script.');
        }

        $script->load(['exam.course', 'exam.questions', 'student', 'scriptMarks']);
        return view('evaluator.grade', compact('script'));
    }

    public function storeMarks(Request $request, Script $script)
    {
        if ($script->exam->assigned_evaluator_id !== auth()->id()) {
            abort(403, 'You are not assigned to evaluate this script.');
        }

        $script->load('exam.questions');
        $questions = $script->exam->questions;

        // Build dynamic validation rules per question
        $rules = [];
        foreach ($questions as $q) {
            $rules["marks.{$q->id}"] = "required|numeric|min:0|max:{$q->marks}";
        }
        $request->validate($rules);

        // Upsert each question mark
        foreach ($questions as $q) {
            ScriptMark::updateOrCreate(
                ['script_id' => $script->id, 'question_id' => $q->id],
                ['marks_obtained' => $request->input("marks.{$q->id}")]
            );
        }

        // Update cached total on the script
        $total = ScriptMark::where('script_id', $script->id)->sum('marks_obtained');
        $script->update([
            'marks_obtained' => $total,
            'status'         => 'evaluated',
        ]);

        return redirect()->route('evaluator.dashboard')
                         ->with('success', 'All marks saved for ' . $script->student->name . '! Total: ' . $total . '/' . $script->exam->total_marks);
    }
}
