<?php

namespace App\Http\Controllers;
use App\Helper\Helper;
use App\Model\User;
use App\Exceptions\Handler as Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {
    public function index() {
        try {
            $userInstance = new User();
            return $userInstance->getAllUsers();
        } catch(Exception $e) {
            Log::error($e);
            $message = Helper::getMessage('USER_LIST_ERROR');
            return Helper::httpResponse($message, 500);
        }
    }

    public function login() {
        return view('auth.login');
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
