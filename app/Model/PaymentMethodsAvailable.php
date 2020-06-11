<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethodsAvailable extends Model {
    use SoftDeletes;

    protected $table = 'payment_methods_available';
    protected $fillable = [
        'gateway',
        'method_id',
        'method_name',
        'method_code',
        'method_type',
        'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $gateways = [
        'MERCADO_PAGO' => [
            'id' => 'MERCADO_PAGO',
            'name' => 'Mercado Pago'
        ],
        'PAGSEGURO' => [
            'id' => 'PAGSEGURO',
            'name' => 'Pagseguro'
        ]
    ];

    public function getGateways() {
        return $this->gateways;
    }

    public $validationRules = [
        'method_code' => 'required'
    ];

    public $validationMessages = [
        'method_code.required' => 'O campo método de pagamento é obrigatório.'
    ];

    public function getGatewaysMethods() {
        $paymentMercadoPagoInstance = new PaymentMercadoPago();
        $paymentMercadoPago = $paymentMercadoPagoInstance->getAllPaymentMethods();

        $paymentPagSeguro = [];

        for($i = 0; $i < count($paymentMercadoPago); $i++) {
            $method = $paymentMercadoPago[$i];
            $method['gateway'] = $this->gateways['MERCADO_PAGO']['id'];
            $method['gateway_name'] = $this->gateways['MERCADO_PAGO']['name'];
            $method['code'] = $method['id'] . '_' . $method['type'];
            $paymentMercadoPago[$i] = $method;
        }

        $methods = array_merge($paymentPagSeguro, $paymentMercadoPago);

        return $methods;
    }

    public function getGatewayMethod($code) {
        $gatewayMethods = $this->getGatewaysMethods();

        for($i = 0; $i < count($gatewayMethods); $i++) {
            $gatewayMethod = $gatewayMethods[$i];
            if (isset($gatewayMethod) && isset($gatewayMethod['code']) && $gatewayMethod['code'] == $code) {
                return $gatewayMethod;
            }
        }

        return null;
    }

    public function getPaymentMethodsAvailableById($id) {
        return PaymentMethodsAvailable::where('id', $id)
            ->first();
    }

    public function getPaymentMethodsAvailableByCode($code) {
        return PaymentMethodsAvailable::where('method_code', $code)
            ->first();
    }

    public function getAvailableMethodIdsFromGateway($gateway) {
        $paymentMethodsAvailable = PaymentMethodsAvailable::where('gateway', $gateway)
            ->get();

        $methodIds = [];

        for ($i = 0; $i < count($paymentMethodsAvailable); $i++) {
            $item = $paymentMethodsAvailable[$i];
            array_push($methodIds, $item->method_id);
        }

        return $methodIds;
    }

    public function getPaymentMethodsAvailableList($filter = null, $paginate = false, $limit = 15) {
        $paymentMethodsAvailable = PaymentMethodsAvailable::orderBy('id', 'desc');

        if ($filter != null && isset($filter['method_name']) && $filter['method_name'] != '') {
            $paymentMethodsAvailable->where('method_name', 'like', '%' . $filter['method_name'] . '%');
        }

        if ($paginate === true) {
            $paymentMethodsAvailable = $paymentMethodsAvailable->paginate($limit);
        } else {
            $paymentMethodsAvailable = $paymentMethodsAvailable->get();
        }

        return $paymentMethodsAvailable;
    }

    public function storePaymentMethodsAvailable($request) {
        $paymentMethodsAvailable = new PaymentMethodsAvailable();
        $gatewayMethodSelected = $this->getGatewayMethod($request->method_code);
        $exists = $this->getPaymentMethodsAvailableByCode($request->method_code);

        if ($exists != null) {
            throw new AppException('O método de pagamento (' . $gatewayMethodSelected['gateway_name'] . ' - ' . $gatewayMethodSelected['method'] . ') já está ativado.');
        }

        $paymentMethodsAvailable->gateway = $gatewayMethodSelected['gateway'];
        $paymentMethodsAvailable->method_id = $gatewayMethodSelected['id'];
        $paymentMethodsAvailable->method_name = $gatewayMethodSelected['method'];
        $paymentMethodsAvailable->method_code = $gatewayMethodSelected['code'];
        $paymentMethodsAvailable->method_type = $gatewayMethodSelected['type'];
        $paymentMethodsAvailable->created_by = Auth::user()->id;

        $paymentMethodsAvailable->save();

        return [
            'message' => 'Cadastro efetuado com sucesso.',
            'data' => $paymentMethodsAvailable
        ];
    }

    public function updatePaymentMethodsAvailable($request, $id) {
        $paymentMethodsAvailable = $this->getPaymentMethodsAvailableById($id);

        if ($paymentMethodsAvailable == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $gatewayMethodSelected = $this->getGatewayMethod($request->method_code);

        $paymentMethodsAvailable->gateway = $gatewayMethodSelected['gateway'];
        $paymentMethodsAvailable->method_id = $gatewayMethodSelected['id'];
        $paymentMethodsAvailable->method_name = $gatewayMethodSelected['gateway'];
        $paymentMethodsAvailable->method_code = $gatewayMethodSelected['code'];
        $paymentMethodsAvailable->method_type = $gatewayMethodSelected['type'];

        $paymentMethodsAvailable->save();

        return [
            'message' => 'Cadastro atualizado com sucesso.',
            'data' => $paymentMethodsAvailable
        ];
    }

    public function deletePaymentMethodsAvailable ($id) {
        $paymentMethodsAvailable = $this->getPaymentMethodsAvailableById($id);

        if ($paymentMethodsAvailable == null) {
            throw new AppException('Cadastro [' . $id . '] não encontrado.');
        }

        $paymentMethodsAvailable->deleted_at = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'));
        $paymentMethodsAvailable->save();

        return [
            'message' => 'Cadastro deletado com sucesso.'
        ];
    }
}
