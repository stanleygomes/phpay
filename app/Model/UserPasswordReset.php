<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPasswordReset extends Model {
    use SoftDeletes;

    protected $table = 'user_password_reset';
    protected $fillable = [
        'user_id',
        'token'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function storeUserPasswordReset($userId) {
        $userLoginHistory = new UserPasswordReset();
        $userLoginHistory->user_id = $userId;
        $userLoginHistory->created_by = 1;
        $userLoginHistory->token = sha1(time());
        $userLoginHistory->save();

        return $userLoginHistory;
    }

    public function getUserByToken($token) {
        $userPasswordReset = UserPasswordReset::where('token', $token)
            ->first();

        if ($userPasswordReset != null) {
            return $userPasswordReset->user_id;
        }

        return null;
    }
}
