<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\User;
use App\Model\UserPasswordReset;
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
                ->with('status', '');
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

    public function registerPost(Request $request) {
        try {
            $userInstance = new User();
            $validateRequest = Helper::validateRequest($request, $userInstance->validationRules, $userInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            DB::beginTransaction();
            $userInstance = new User();
            $user = $userInstance->storeUser($request);
            $userInstance->sendMailCredentials($user);
            $userInstance->login($request);
            DB::commit();

            return Redirect::route('app.dashboard')
                ->with('status', 'Sua conta foi criada com sucesso.');
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordRequest() {
        return view('auth.password-request');
    }

    public function passwordRequestPost(Request $request) {
        try {
            $userInstance = new User();
            $user = $userInstance->passwordRequest($request);
            $userInstance->sendMailPasswordRequest($user);

            return Redirect::back()
                ->with('status', 'Enviamos um email para você. Por favor, verifique sua caixa de entrada para recuperação de senha.');
        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordReset(Request $request) {
        try {
            $token = $request->token;

            $userPasswordResetInstance = new UserPasswordReset();
            $userId = $userPasswordResetInstance->getUserByToken($request->token);

            if ($userId == null) {
                return Redirect::route('auth.login')
                    ->with('status', 'Essa solicitação de recuperação de senha expirou, por favor tente novamente.');
            }

            return view('auth.password-reset', compact('token'));
        } catch (Exception $e) {
            return Redirect::route('auth.login')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordResetPost(Request $request) {
        try {
            $userInstance = new User();
            $user = $userInstance->passwordReset($request);

            return Redirect::route('auth.login')
                ->with('status', 'Sua senha foi alterada com sucesso.');
        } catch (Exception $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function index() {
        try {
            $filter = Session::get('userSearch');
            $userInstance = new User();
            $users = $userInstance->getUserList($filter, true);

            return view('user.index', compact('users'));
        } catch (Exception $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
