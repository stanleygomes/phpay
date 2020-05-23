<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodsAvailable extends Model {
    protected $fillable = [
        'gateway_id',
        'method_id',
        'method_type',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $gateways = [
        [
            'id' => 'MERCADO_PAGO',
            'name' => 'Mercado Pago'
        ],
        [
            'id' => 'PAGSEGURO',
            'name' => 'Pagseguro'
        ]
    ];

    public function getGateways() {
        return $this->gateways;
    }

    public function getPaymentMethodsAvailable($id) {
        return PaymentMethodsAvailable::where('id', $id)
            ->first();
    }

    public function getAllPaymentMethodsAvailable() {
        return PaymentMethodsAvailable::get();
    }
}
