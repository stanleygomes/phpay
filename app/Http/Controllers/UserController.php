<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\User;
use App\Helper\Helper;

class UserController extends Controller {
    public function login() {
        if (Auth::user()) {
            return redirect()
                ->route('app.dashboard');
        }

        return view('auth.login');
    }

    public function loginPost(Request $request) {
        try {
            $userInstance = new User();
            $validateRequest = Helper::validateRequest($request, $userInstance->validationRulesLogin, $userInstance->validationMessagesLogin);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            DB::beginTransaction();
            $userInstance->login($request);
            DB::commit();

            return Redirect::route('app.dashboard')
                ->with('status', 'Sua mensagem foi enviada com sucesso.');
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function logout() {
        try {
            $userInstance = new User();
            $userInstance->logout();

            return Redirect::route('auth.login')
                ->with('status', 'Obrigado por estar conosco.');
        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function register() {
        return view('auth.register');
    }

    public function passwordRequest() {
        return view('auth.password-request');
    }

    public function passwordReset() {
        return view('auth.password-reset');
    }
}
