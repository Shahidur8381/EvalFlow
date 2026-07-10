<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'           => \App\Http\Middleware\RoleMiddleware::class,
            'exam.time'      => \App\Http\Middleware\EnsureExamTimeIsValid::class,
            'exam.questions' => \App\Http\Middleware\EnsureExamHasQuestions::class,
            'withdraw.min'   => \App\Http\Middleware\EnsureMinimumWithdrawal::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'student/finance/success',
            'student/finance/fail',
            'student/finance/cancel',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
