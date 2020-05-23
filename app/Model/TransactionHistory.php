<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model {
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

    public function getTransactionHistory($id) {
        return TransactionHistory::where('id', $id)
            ->first();
    }

    public function getAllTransactionHistory() {
        return TransactionHistory::get();
    }
}
