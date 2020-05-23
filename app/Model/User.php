<?php

namespace App\Model;

use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class User extends Authenticatable {
    use Notifiable;

    protected $table = 'user';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public $validationRules = [
        'name' => 'required',
        'email' => 'required'
    ];

    public $validationMessages = [
        'name.required' => 'O campo nome é obrigatório.',
        'email.required' => 'O campo email é obrigatório.'
    ];

    public $validationRulesLogin = [
        'email' => 'required',
        'password' => 'required'
    ];

    public $validationMessagesLogin = [
        'email.required' => 'O campo email é obrigatório.',
        'password.required' => 'O campo senha é obrigatório.'
    ];

    public function getRole($code) {
    }

    public function getPermissionsByRole($code) {
    }

    public function getUserByEmail($email) {
        return User::where('email', $email)
            ->select('id', 'name', 'email', 'profile', 'password')
            ->first();
    }

    public function login($request) {
        $user = $this->getUserByEmail($request->email);

        if ($user == null) {
            throw new Exception('Não encontramos um usuário cadastrado com esse email.');
        }

        // $hashPassword = Hash::make($request->password);
        $validPassword = Hash::check($request->password, $user->password);

        if ($validPassword === false) {
            throw new Exception('A senha informada não está correta. Você pode tentar a recuperação de senha abaixo.');
        }

        Auth::login($user);

        $userLoginHistoryInstance = new UserLoginHistory();
        $userLoginHistoryInstance->storeUserLoginHistory($user->id);

        return $user;
    }

    public function logout() {
        Auth::logout();
    }

    public function getAllUsers() {
        return User::get();
    }
}
