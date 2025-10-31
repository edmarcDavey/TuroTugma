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
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect users to role-specific dashboards when possible.
        $user = $request->user();

        // Map IDs to dashboard routes
        $itId = '300627-101';
        $schedulerId = '300627-201';

        if ($user && $user->email === $itId) {
            $target = route('dashboard.it', [], false);
        } elseif ($user && $user->email === $schedulerId) {
            $target = route('dashboard.scheduler', [], false);
        } else {
            // fallback to existing dashboard route (public placeholder)
            $target = url('/dashboard');
        }

        return redirect()->intended($target);
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
