<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentMercadoPagoController extends Controller {
    public function preview() {
        // payment page
        $customer = null;
        $items = [1, 2, 3];
        $paymentMethodsAvailable = [
            'methods' => ['visa', 'master'],
            'installments' => 10
        ];

        try {
            // criar dados para solicitacao de pagamento
            $preference = $this->getPreference($customer, $items, $paymentMethodsAvailable);
            // TODO: criar um pagamento aqui numa tabela do banco e referenciar cliente e $preference->id

        } catch(AppException $e) {
            Log::error($e);
            return 'Ocorreu um erro ao gerar a solicitação de pagamento.';
        }

        $paymentUrl = $preference->init_point;

        return view('mercado-pago.preview', [
            'preference' => $preference,
            'paymentUrl' => $paymentUrl
        ]);
    }


    public function callback(Request $request, $type) {
        // TODO: salvar essa response na tabela relacionando ao pagamento
        $callbackData = $request;
        // TODO: enviar email para o cliente informando o status atual do pedido
        return view('mercado-pago.callback', [
            'type' => $type
        ]);
    }

    public function status(Request $request) {
        Log::debug('// -------------- start');
        Log::debug($request);

        $accesToken = $this->getAccessToken();
        $MP = new MercadoPago\SDK();
        $MP->setAccessToken($accesToken);

        $merchantOrder = null;
        $topic = $request->topic;
        $id = $request->id;

        if ($topic === null || $id === null) {
            $message = 'Parametros {topic e id} não informados na requisicao.';
            Log::debug($message);
            return ['message' => $message];
        }

        if ($topic === 'payment') {
            $payment = MercadoPago\Payment::find_by_id($id);
            if ($payment === null) {
                $message = 'Pagamento {' . $id . '} não encontrado.';
                Log::debug($message);
                return ['message' => $message];
            } else {
                $merchantOrderId = $payment->order->id;
                $merchantOrder = MercadoPago\MerchantOrder::find_by_id($merchantOrderId);
            }
        } else if ($topic === 'merchant_order') {
            $merchantOrder = MercadoPago\MerchantOrder::find_by_id($id);
        } else {
            $message = 'Metodo {' . $topic . '} não suportado.';
            Log::debug($message);
            return ['message' => $message];
        }

        if ($merchantOrder === null) {
            $message = 'Pedido não encontrado.';
            Log::debug($message);
            return ['message' => $message];
        }

        $paidAmount = 0;
        foreach ($merchantOrder->payments as $payment) {
            if ($payment['status'] == 'approved') {
                $paidAmount += $payment['transaction_amount'];
            }
        }

        Log::debug('Total pago: ' . $paidAmount);

        if ($paidAmount >= $merchantOrder->total_amount) {
            if (count($merchantOrder->shipments) > 0) {
                if ($merchantOrder->shipments[0]->status == 'ready_to_ship') {
                    Log::debug('Pedido totalmente pago. Preparado para despacho.');
                }
            } else {
                Log::debug('Pedido totalmente pago.');
                // TODO: enviar email para o cliente informando que foi pago
            }
        } else {
            Log::debug('Pedido ainda pendente.');
        }

        // TODO: atualizar status de pagamento pelo reference->id

        Log::debug('// -------------- end');

        return ['message' => 'Fim da verificação.'];
    }

}
