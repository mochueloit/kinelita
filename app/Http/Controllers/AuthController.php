<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            $activityLog->log(
                'admin.login_failed',
                'Intento de login fallido',
                properties: ['email' => $credentials['email']],
                request: $request,
            );

            return back()->withErrors([
                'email' => 'Credenciales incorrectas.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! Auth::user()->is_admin) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'No tienes permisos de administrador.',
            ])->onlyInput('email');
        }

        $activityLog->log(
            'admin.login',
            'Inicio de sesión de administrador',
            Auth::user(),
            request: $request,
        );

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request, ActivityLogService $activityLog): RedirectResponse
    {
        if ($user = Auth::user()) {
            $activityLog->log(
                'admin.logout',
                'Cierre de sesión de administrador',
                $user,
                request: $request,
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('ranking');
    }
}
