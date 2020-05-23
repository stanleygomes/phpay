<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserLoginHistory extends Authenticatable {
    use Notifiable;

    protected $table = 'user_login_history';
    protected $fillable = [
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function storeUserLoginHistory($userId) {
        $userLoginHistory = new UserLoginHistory();
        $userLoginHistory->user_id = $userId;
        $userLoginHistory->save();

        return $userLoginHistory->id;
    }
}
