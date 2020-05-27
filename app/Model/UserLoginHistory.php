<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLoginHistory extends Model {
    use SoftDeletes;

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
        $userLoginHistory->created_by = 1;
        $userLoginHistory->save();

        return $userLoginHistory->id;
    }
}
