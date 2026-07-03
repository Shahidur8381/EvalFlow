<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Script;
use App\Models\Exam;

class EvaluatorScriptController extends Controller
{
    public function dashboard()
    {
        // Only show scripts from exams assigned to THIS evaluator.
        $scripts = Script::whereHas('exam', function ($q) {
                        $q->where('assigned_evaluator_id', auth()->id());
                    })
                    ->with(['exam.course', 'exam.questions', 'student'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $pending   = $scripts->where('status', 'pending')->count();
        $evaluated = $scripts->where('status', 'evaluated')->count();

        return view('evaluator.dashboard', compact('scripts', 'pending', 'evaluated'));
    }

    public function show(Script $script)
    {
        // Ensure this evaluator is assigned to the exam.
        if ($script->exam->assigned_evaluator_id !== auth()->id()) {
            abort(403, 'You are not assigned to evaluate this script.');
        }

        $script->load(['exam.course', 'exam.questions', 'student']);
        return view('evaluator.grade', compact('script'));
    }

    public function storeMarks(Request $request, Script $script)
    {
        // Ensure this evaluator is assigned to the exam.
        if ($script->exam->assigned_evaluator_id !== auth()->id()) {
            abort(403, 'You are not assigned to evaluate this script.');
        }

        $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $script->exam->total_marks,
        ]);

        $script->update([
            'marks_obtained' => $request->marks_obtained,
            'status'         => 'evaluated',
        ]);

        return redirect()->route('evaluator.dashboard')
                         ->with('success', 'Marks saved for ' . $script->student->name . '!');
    }
}
