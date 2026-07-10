<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMinimumWithdrawal
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->input('amount') < 100) {
            return back()->withErrors(['amount' => 'Minimum withdrawal amount is 100 TK.'])->withInput();
        }

        return $next($request);
    }
}
