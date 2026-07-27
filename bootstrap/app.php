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
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Exempt Midtrans webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'midtrans/notification',
        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            /** @var \App\Models\User|null $user */
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user && $user->hasRole('admin')) {
                return route('dashboard');
            }
            if ($user && $user->hasRole('owner')) {
                return route('owner.dashboard', ['owner' => 'owner']);
            }
            if ($user && $user->hasRole('worker')) {
                return route('worker.dashboard', ['worker' => 'worker']);
            }
            return route('user.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
