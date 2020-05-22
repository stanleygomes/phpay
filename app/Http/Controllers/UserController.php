<?php

namespace App\Http\Controllers;
use App\Helper\HttpUtil;
use App\Helper\LangUtil;
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
            $message = LangUtil::getMessage('USER_LIST_ERROR');
            return HttpUtil::httpResponse($message, 500);
        }
    }
}
