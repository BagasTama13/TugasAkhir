<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the user login view.
     */
    public function createUser(): View
    {
        return view('auth.login-user');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.login-admin');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('admin')) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($user && $user->hasRole('owner')) {
            return redirect()->route('owner.dashboard', ['owner' => $user->username ?? 'owner']);
        }

        if ($user && $user->hasRole('worker')) {
            return redirect()->route('worker.dashboard', ['worker' => $user->username ?? 'worker']);
        }

        return redirect()->intended(route('user.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
