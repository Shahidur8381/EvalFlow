<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExamHasQuestions
{
    /**
     * Block access to an exam if it has no questions.
     * Applies to student exam view and upload routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\Exam|null $exam */
        $exam = $request->route('exam');

        if ($exam && $exam->questions()->count() === 0) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This exam is not yet live — no questions have been added by the administrator.');
        }

        return $next($request);
    }
}
