<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionHistory extends Model {
    use SoftDeletes;

    protected $table = 'transaction_history';
    protected $fillable = [
        'transition_id',
        'status',
        'status_detail',
        'message',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
}
