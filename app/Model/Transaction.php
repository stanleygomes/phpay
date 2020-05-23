<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $table = 'transaction';
    protected $fillable = [
        'customer_id',
        'qty',
        'total_price',
        'payment_method_id',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getTransaction($id) {
        return Transaction::where('id', $id)
            ->first();
    }

    public function getAllTransaction() {
        return Transaction::get();
    }
}
