<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model {
    use SoftDeletes;

    protected $table = 'transaction';
    protected $fillable = [
        'cart_id',
        'preference_id',
        'preference_url',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function storeTransaction($cartId, $preferenceId, $preferenceUrl) {
        $transaction = new Transaction();
        $transaction->cart_id = $cartId;
        $transaction->preference_id = $preferenceId;
        $transaction->preference_url = $preferenceUrl;
        $transaction->created_by = Auth::user()->id;
        $transaction->save();

        return $transaction;
    }
}
