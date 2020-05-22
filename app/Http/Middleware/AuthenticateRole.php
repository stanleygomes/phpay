<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateRole {
    public function handle($request, Closure $next, $roles) {
        $userLogged = Auth::user();

        if ($userLogged == null) {
            return redirect()
                ->route('auth.index');
        }

        $pattern = '/&/';
        if (preg_match($pattern, $roles)) {
            $rolesList = explode('&', $roles);
        } else {
            $rolesList = [$roles];
        }

        foreach ($rolesList as $key => $value) {
            if ($userLogged->role == $value) {
                return $next($request);
            }
        }

        return redirect()
            ->route('app.dashboard')
            ->withErrors(['Permissão de acesso negada.']);
    }
}
