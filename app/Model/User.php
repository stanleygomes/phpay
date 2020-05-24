<?php

namespace App\Model;

use Exception;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Helper\Helper;

class User extends Authenticatable {
    use Notifiable;
    use SoftDeletes;

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

    protected $profiles = [
        'ADMIN',
        'COLABORATOR',
        'CUSTOMER'
    ];

    protected $roles = [
        'ADMIN' => ['MANAGE_USER'],
    ];

    protected $permissions = [
        'MANAGE_USER' => ['code' => 'user', 'route' => 'app.user', 'title' => 'Usuários', 'icon' => 'users'],
    ];

    public function getProfiles() {
        return $this->profiles;
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

    public function passwordRequest($request) {
        $user = $this->getUserByEmail($request->email);

        if ($user == null) {
            throw new Exception('Não encontramos um usuário cadastrado com esse email.');
        }

        $userPasswordResetInstance = new UserPasswordReset();
        $userPasswordReset = $userPasswordResetInstance->storeUserPasswordReset($user->id);
        $user->token = $userPasswordReset->token;

        return $user;
    }

    public function passwordReset($request) {
        $userPasswordResetInstance = new UserPasswordReset();
        $userId = $userPasswordResetInstance->getUserByToken($request->token);

        if ($request->token == null || $userId == null) {
            throw new Exception('O token informado é inválido. Por favor, tente recuperar a senha novamente.');
        }

        if ($request->password === null) {
            throw new Exception('Por favor, informe a nova senha e a repetição dela.');
        }

        $hashPassword = Hash::make($request->password);

        $user = User::where('id', $userId)
            ->update([
                'password' => $hashPassword
            ]);

        return $user;
    }

    public function passwordGenerate($id) {
        $user = User::getUserById($id);

        if ($user == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $passwordPlain = rand(1111, 9999) * 987456;
        $hashPassword = Hash::make($passwordPlain);

        User::where('id', $id)
            ->update([
                'password' => $hashPassword
            ]);

        $user->password_plain = $passwordPlain;

        return [
            'message' => 'Senha gerada e enviada para o email ' . $user->email . ' com sucesso.',
            'data' => $user
        ];
    }

    public function getUserById($id) {
        return User::where('id', $id)
            ->first();
    }

    public function getUserList($filter = null, $paginate = false, $limit = 15) {
        $user = User::orderBy('name', 'asc');

        if ($filter != null && $filter['name'] != '') {
            $user->where('name', 'like', '%' . $filter['name'] . '%');
        }

        if ($paginate === true) {
            $user = $user->paginate($limit);
        } else {
            $user = $user->get();
        }

        return $user;
    }

    public function getUserByEmail($email) {
        return User::where('email', $email)
            ->select('id', 'name', 'email', 'profile', 'password')
            ->first();
    }

    public function storeUser($request) {
        $user = $this->getUserByEmail($request->email);

        if ($user != null) {
            throw new Exception('Já existe uma conta cadastrada com esse email. Você pode efetuar o login.');
        }

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;

        if (Auth::user() != null) {
            $user->created_by = Auth::user()->id;
        } else {
            $user->created_by = 1;
        }

        if ($request->password != null) {
            $passwordPlain = $request->password;
            $user->profile = 'CUSTOMER';
        } else {
            $passwordPlain = rand(1111, 9999) * 987456;
            $user->profile = $request->profile;
        }

        $user->password = Hash::make($passwordPlain);

        $user->save();

        $user->password_plain = $passwordPlain;

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $user
        ];
    }

    public function updateUser ($request, $id) {
        $user = User::getUserById($id);

        if ($user == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->profile = $request->profile;

        $user->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $user
        ];
    }

    public function deleteUser ($id) {
        $user = User::getUserById($id);

        if ($user == null) {
            throw new Exception('Cadastro [' . $id . '] não encontrado.');
        }

        $user->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $user->save();

        return 'Cadastro deletado com sucesso.';
    }

    public function logout() {
        Auth::logout();
    }

    public function sendMailPasswordRequest($user) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => $user->email,
            'to_name' => $user->name
        ];
        $subject = 'Sua nova senha';
        $template = 'password-request';
        $data = $user;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(Exception $e) {
            Log::error($e);
            throw new Exception('Erro ao enviar email.');
        }
    }

    public function sendMailCredentials($user) {
        $param = [
            'from_email' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'to_email' => $user->email,
            'to_name' => $user->name
        ];
        $subject = 'Suas credenciais de acesso';
        $template = 'user-password-generate';
        $data = $user;

        try {
            $helperInstance = new Helper();
            $helperInstance->sendMail($param, $data, $template, $subject);
        } catch(Exception $e) {
            Log::error($e);
            throw new Exception('Erro ao enviar email.');
        }
    }
}
