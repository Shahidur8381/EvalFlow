<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Script;

class EvaluatorScriptController extends Controller
{
    public function dashboard()
    {
        $scripts = Script::with(['exam.course', 'student'])->orderBy('created_at', 'desc')->get();
        return view('evaluator.dashboard', compact('scripts'));
    }

    public function show(Script $script)
    {
        $script->load(['exam.course', 'student']);
        return view('evaluator.grade', compact('script'));
    }

    public function storeMarks(Request $request, Script $script)
    {
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $script->exam->total_marks,
        ]);

        $script->update([
            'marks_obtained' => $request->marks_obtained,
            'status' => 'evaluated',
        ]);

        return redirect()->route('evaluator.dashboard')->with('success', 'Marks saved successfully for ' . $script->student->name);
    }
}
