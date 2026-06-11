<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Exempt Midtrans webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && strtolower($user->username) === 'admin') {
                return route('dashboard');
            }
            if ($user && strtolower($user->username) === 'owner') {
                return route('owner.dashboard', ['owner' => 'owner']);
            }
            if ($user && strtolower($user->username) === 'worker') {
                return route('worker.dashboard', ['worker' => 'worker']);
            }
            return route('user.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
