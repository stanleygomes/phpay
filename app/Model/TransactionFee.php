<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFee extends Model {
    use SoftDeletes;

    protected $table = 'transaction_fee';
    protected $fillable = [
        'cart_id',
        'transaction_id',
        'amount',
        'fee_payer',
        'fee_payer_description',
        'type',
        'type_description',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function getTransactionFeeList($filter = null, $paginate = false, $limit = 15) {
        $address = TransactionFee::orderBy('id', 'desc');

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

    public function storeTransactionFeeList($list, $transactionId, $cartId) {
        for ($i = 0; $i < count($list); $i++) {
            $fee = $list[$i];
            $fee['transaction_id'] = $transactionId;
            $fee['cart_id'] = $cartId;

            $this->storeTransactionFee($fee);
        }
    }

    public function storeTransactionFee($data) {
        $transactionFee = new TransactionFee();

        $transactionFee->cart_id = $data['cart_id'];
        $transactionFee->transaction_id = $data['transaction_id'];
        $transactionFee->amount = $data['amount'];
        $transactionFee->fee_payer = $data['fee_payer'];
        $transactionFee->fee_payer_description = $data['fee_payer_description'];
        $transactionFee->type = $data['type'];
        $transactionFee->type_description = $data['type_description'];
        $transactionFee->created_by = 1;

        $transactionFee->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $transactionFee
        ];
    }
}
