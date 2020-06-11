<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Model\Cart;
use App\Model\User;
use App\Model\UserPasswordReset;
use App\Helper\Helper;

class UserController extends Controller {
    public function login(Request $request) {
        if (Auth::user()) {
            return redirect()
                ->route('app.dashboard');
        }

        return view('user.login', [
            'redir' => $request != null ? $request->redir : null,
            'redirMessage' => $request != null ? $request->redirMessage : null
        ]);
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
            $user = $userInstance->login($request);
            DB::commit();

            if ($request->redir != null) {
                return Redirect::to($request->redir)
                    ->with('status', $request->redirMessage != null ? $request->redirMessage : '');
            } else {
                $userData = $user['data'];
                if ($userData->profile === 'ADMIN') {
                    return Redirect::route('app.dashboard')
                        ->with('status', 'Seja bem vindo!');
                } else {
                    return Redirect::route('app.cart.index')
                        ->with('status', 'Seja bem vindo!');
                }
            }
        } catch (AppException $e) {
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
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function register(Request $request) {
        if (Auth::user()) {
            return redirect()
                ->route('app.dashboard');
        }

        return view('user.register', [
            'redir' => $request != null ? $request->redir : null,
            'redirMessage' => $request != null ? $request->redirMessage : null
        ]);
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
            $user = $userInstance->storeUser($request);
            $userInstance->sendMailCredentials($user['data']);
            $userInstance->login($request);
            DB::commit();

            if ($request->redir != null) {
                return Redirect::to($request->redir)
                    ->with('status', $request->redirMessage != null ? $request->redirMessage : '');
            } else {
                return Redirect::route('app.dashboard')
                    ->with('status', 'Sua conta foi criada com sucesso.');
            }
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordRequest() {
        return view('user.password-request');
    }

    public function passwordRequestPost(Request $request) {
        try {
            $userInstance = new User();
            $user = $userInstance->passwordRequest($request);
            $userInstance->sendMailPasswordRequest($user);

            return Redirect::back()
                ->with('status', 'Enviamos um email para você. Por favor, verifique sua caixa de entrada para recuperação de senha.');
        } catch (AppException $e) {
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

            return view('user.password-reset', [
                'token' => $token
            ]);
        } catch (AppException $e) {
            return Redirect::route('auth.login')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordResetPost(Request $request) {
        try {
            $userInstance = new User();
            $userInstance->passwordReset($request);

            return Redirect::route('auth.login')
                ->with('status', 'Sua senha foi alterada com sucesso.');
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordGenerate($id) {
        try {
            $userInstance = new User();
            $user = $userInstance->passwordGenerate($id);
            $userInstance->sendMailCredentials($user['data']);

            return Redirect::route('app.user.index')
                ->with('status', $user['message']);
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordChange() {
        try {
            return view('user.password-change');
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function passwordChangePost(Request $request) {
        try {
            $userId = Auth::user()->id;
            $userInstance = new User();
            $user = $userInstance->passwordChange($request, $userId);

            return Redirect::route('app.user.passwordChange')
                ->with('status', $user['message']);
        } catch (AppException $e) {
            return Redirect::route('app.user.passwordChange')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function accountUpdate() {
        try {
            $userId = Auth::user()->id;
            $userInstance = new User();
            $user = $userInstance->getUserById($userId);
            $profiles = $userInstance->getProfiles();
            $modeEdit = true;
            $modeAccount = true;

            return view('user.form', [
                'user' => $user,
                'profiles' => $profiles,
                'modeEdit' => $modeEdit,
                'modeAccount' => $modeAccount
            ]);
        } catch (AppException $e) {
            return Redirect::back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function accountUpdatePost(Request $request) {
        try {
            $userId = Auth::user()->id;
            $userProfile = Auth::user()->profile;
            $request->profile = $userProfile;

            $userInstance = new User();
            $user = $userInstance->updateUser($request, $userId);

            return Redirect::route('app.user.accountUpdate')
                ->with('status', $user['message']);
        } catch (AppException $e) {
            return Redirect::route('app.user.accountUpdate')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function index() {
        try {
            $filter = Session::get('userSearch');
            $userInstance = new User();
            $users = $userInstance->getUserList($filter, true);

            return view('user.index', [
                'users' => $users,
                'filter' => $filter
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.dashboard')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function search(Request $request) {
        try {
            $filter = $request->all();
            Session::put('userSearch', $filter);
            return Redirect::route('app.user.index');
        } catch (AppException $e) {
            return Redirect::route('app.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function create() {
        try {
            $userInstance = new User();
            $profiles = $userInstance->getProfiles();
            $modeEdit = false;
            $modeAccount = false;

            return view('user.form', [
                'profiles' => $profiles,
                'modeEdit' => $modeEdit,
                'modeAccount' => $modeAccount
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request) {
        try {
            $userInstance = new User();
            $validateRequest = Helper::validateRequest($request, $userInstance->validationRules, $userInstance->validationMessages);

            if ($validateRequest != null) {
                return Redirect::back()
                    ->withErrors($validateRequest)
                    ->withInput();
            }

            $userInstance = new User();
            $user = $userInstance->storeUser($request);
            $userInstance->sendMailCredentials($user['data']);

            return Redirect::route('app.user.index')
                ->with('status', $user['message']);
        } catch (AppException $e) {
            DB::rollBack();
            return Redirect::route('app.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function edit($id) {
        try {
            $userInstance = new User();
            $user = $userInstance->getUserById($id);
            $profiles = $userInstance->getProfiles();
            $modeEdit = true;
            $modeAccount = false;

            return view('user.form', [
                'user' => $user,
                'profiles' => $profiles,
                'modeEdit' => $modeEdit,
                'modeAccount' => $modeAccount
            ]);
        } catch (AppException $e) {
            return Redirect::route('app.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id) {
        try {
            $userInstance = new User();
            $user = $userInstance->updateUser($request, $id);

            return Redirect::route('app.user.index')
                ->with('status', $user['message']);
        } catch (AppException $e) {
            return Redirect::route('app.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function delete($id) {
        try {
            $userInstance = new User();
            $delete = $userInstance->deleteUser($id);

            return Redirect::route('app.user.index')
                ->with('status', $delete['message']);
        } catch (AppException $e) {
            return Redirect::route('app.user.index')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartUserEdit() {
        try {
            $userLogged = Auth::user();

            if ($userLogged == null) {
                return Redirect::route('auth.login', ['redir' => '/cart/user/edit']);
            }

            $userInstance = new User();
            $user = $userInstance->getUserById($userLogged->id);

            return view('user.cart-user', [
                'user' => $user
            ]);
        } catch (AppException $e) {
            return Redirect::route('website.cart.cart')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function cartUserUpdate(Request $request) {
        try {
            DB::beginTransaction();
            $userLogged = Auth::user();

            if ($userLogged == null) {
                return Redirect::route('auth.login', ['redir' => '/cart/user/edit']);
            }

            $userInstance = new User();
            $user = $userInstance->updateUser($request, $userLogged->id);

            $cartInstance = new Cart();
            $cartInstance->setUser($user['data']);
            DB::commit();

            return Redirect::route('website.cart.address');
        } catch (AppException $e) {
            DB::rollback();
            return Redirect::route('website.cart.user')
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }
}
