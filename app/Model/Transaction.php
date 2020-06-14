<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use SoftDeletes;

    protected $table = 'transaction';
    protected $fillable = [
        'cart_id',
        'preference_id',
        'gateway',
        'data_id',
        'type',
        'operation_type',
        'date_of_expiration',
        'application_fee',
        'currency_id',
        'transaction_amount',
        'merchant_order_id',
        'installments',
        'payment_type_id',
        'call_for_authorize_id',
        'payment_type_id_description',
        'payment_method_id',
        'payment_method_id_description',
        'status',
        'status_description',
        'status_detail',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getTransactionList($filter = null, $paginate = false, $limit = 15) {
        $address = Transaction::orderBy('id', 'desc');

        if ($filter != null && isset($filter['cart_id']) && $filter['cart_id'] != '') {
            $address->where('cart_id', $filter['cart_id']);
        }

        if ($paginate === true) {
            $address = $address->paginate($limit);
        } else {
            $address = $address->get();
        }

        return $address;
    }

    public function storeTransaction($data) {
        $transaction = new Transaction();

        $transaction->cart_id = $data['cart_id'];
        $transaction->preference_id = $data['preference_id'];
        $transaction->gateway = $data['gateway'];
        $transaction->data_id = $data['data_id'];
        $transaction->type = $data['type'];
        $transaction->operation_type = $data['operation_type'];
        $transaction->date_of_expiration = $data['date_of_expiration'];
        $transaction->application_fee = $data['application_fee'];
        $transaction->currency_id = $data['currency_id'];
        $transaction->transaction_amount = $data['transaction_amount'];
        $transaction->merchant_order_id = $data['merchant_order_id'];
        $transaction->installments = $data['installments'];
        $transaction->payment_type_id = $data['payment_type_id'];
        $transaction->call_for_authorize_id = $data['call_for_authorize_id'];
        $transaction->payment_type_id_description = $data['payment_type_id_description'];
        $transaction->payment_method_id = $data['payment_method_id'];
        $transaction->payment_method_id_description = $data['payment_method_id_description'];
        $transaction->status = $data['status'];
        $transaction->status_description = $data['status_description'];
        $transaction->status_detail = $data['status_detail'];
        $transaction->created_by = 1;

        $transaction->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $transaction
        ];
    }
}
