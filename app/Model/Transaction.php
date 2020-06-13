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

    protected $statusList = [
        'success' => [
            'type' => 'success',
            'description' => 'Obrigado por sua compra. Pagamento efetuado com sucesso.'
        ],
        'pending' => [
            'type' => 'pending',
            'description' => 'Obrigado por sua compra. Recebemos sua solicitação. Seu pagamento ainda está em análise.'
        ],
        'failure' => [
            'type' => 'failure',
            'description' => 'Não foi possível processar seu pagamento.'
        ]
    ];

    public function getStatusByType($type) {
        return $this->statusList[$type];
    }

    public function getStatusDescriptionByType($type) {
        return $this->statusList[$type]['description'];
    }

    public function getCartHistoryStatusByPaymentStatus($type) {
        $cartInstance = new Cart();
        $statusPayment = $this->getStatusByType($type);

        if ($type === 'success') {
            $statusCartHistory = $cartInstance->getCartStatusByCode('PAID');
        } else if($type === 'pending') {
            $statusCartHistory = $cartInstance->getCartStatusByCode('PAYMENT_PENDING');
        } else if($type === 'failure') {
            $statusCartHistory = $cartInstance->getCartStatusByCode('CANCELED');
        }

        return [
            'status' => $statusCartHistory,
            'description' => $statusPayment['description']
        ];
    }

    public function storeTransaction($cartId, $preferenceId, $preferenceUrl) {
        $transaction = new Transaction();
        $transaction->cart_id = $cartId;
        $transaction->preference_id = $preferenceId;
        $transaction->preference_url = $preferenceUrl;
        $transaction->created_by = Auth::user()->id;
        $transaction->save();

        return $transaction;
    }

    public function getCartIdByTransaction($preferenceId) {
        return Transaction::where('preference_id', $preferenceId)
            ->first();
    }
}
