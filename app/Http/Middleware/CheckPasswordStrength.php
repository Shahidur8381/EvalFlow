<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CheckPasswordStrength
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only validate if a password field is present in the request
        if ($request->has('password') && $request->filled('password')) {
            // We use the default rules we set up in AppServiceProvider
            $validator = Validator::make($request->all(), [
                'password' => ['required', Password::defaults()],
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        return $next($request);
    }
}
