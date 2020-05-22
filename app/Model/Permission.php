<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $permissions = [
        'MANAGE_USER' => ['code' => 'user', 'route' => 'app.user', 'title' => 'Usuários', 'icon' => 'users'],
    ];
}
