<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExamTimeIsValid
{
    /**
     * Handle an incoming request.
     * Ensures exam end_time is strictly after start_time.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = $request->input('start_time');
        $endTime   = $request->input('end_time');

        if ($startTime && $endTime) {
            if (strtotime($endTime) <= strtotime($startTime)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['end_time' => 'End time must be strictly after start time.']);
            }
        }

        return $next($request);
    }
}
