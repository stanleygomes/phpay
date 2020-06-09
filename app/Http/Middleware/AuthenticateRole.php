<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateRole {
    public function handle($request, Closure $next, $roles) {
        $userLogged = Auth::user();

        if ($userLogged == null) {
            return redirect()
                ->route('auth.login');
        }

        $pattern = '/&/';
        if (preg_match($pattern, $roles)) {
            $rolesList = explode('&', $roles);
        } else {
            $rolesList = [$roles];
        }

        foreach ($rolesList as $key => $value) {
            if ($userLogged->profile == $value) {
                return $next($request);
            }
        }

        return redirect()
            ->route('app.cart.index')
            ->withErrors(['Permissão de acesso negada.']);
    }
}
