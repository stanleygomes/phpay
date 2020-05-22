<?php

namespace App\Http\Controllers;

use MercadoPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MercadoPagoController extends Controller {
    public function preview () {
        $accesToken = $this->getAccessToken();

        $MP = new MercadoPago\SDK();
        $MP->setAccessToken($accesToken);

        $preference = new MercadoPago\Preference();

        $preference->payer = $this->getPayer();
        $preference->items = $this->getItems();
        $preference->back_urls = $this->getBackUrls();
        $preference->payment_methods = $this->getPaymentMethods();
        // $preference->auto_return = "approved";

        $preference->save();

        // criar um pagamento aqui numa tabela do banco e referenciar cliente e $preference->id

        return view('mercado-pago.preview', compact('preference'));
    }

    public function status (Request $request) {
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
                // enviar email para o cliente informando que foi pago
            }
        } else {
            Log::debug('Pedido ainda pendente.');
        }

        // atualizar status de pagamento pelo reference->id

        Log::debug('// -------------- end');

        return ['message' => 'Fim da verificação.'];
    }

    public function callback (Request $request, $type) {
        // salvar essa response na tabela relacionando ao pagamento
        $callbackData = $request;
        // enviar email para o cliente informando o status atual do pedido
        return view('mercado-pago.callback', compact('type'));
    }

    public function createCustomerSandbox () {
        $accesToken = $this->getAccessToken();
        $url = 'https://api.mercadopago.com/users/test_user?access_token=' . $accesToken;

        $ch = curl_init();

        $headers  = [
            'Content-Type: application/json'
        ];

        $postData = [
            'site_id' => 'MLB'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }
    private function getPayer ($customer = null) {
        $payer = new MercadoPago\Payer();

        $payer->name = "Joao";
        $payer->surname = "Silva";
        $payer->email = "user@email.com";
        $payer->date_created = "2018-06-02T12:58:41.425-04:00";
        $payer->phone = array(
            "area_code" => "11",
            "number" => "4444-4444"
        );
        $payer->identification = array(
            "type" => "CPF",
            "number" => "19119119100"
        );
        $payer->address = array(
            "street_name" => "Street",
            "street_number" => 123,
            "zip_code" => "06233200"
        );

        return $payer;
    }

    private function getItems ($items = null) {
        $items = [];

        $item = $this->getItem();
        array_push($items, $item);

        return $items;
    }

    private function getItem ($item = null) {
        $item = new MercadoPago\Item();
        $item->title = 'Meu produto';
        $item->quantity = 1;
        $item->unit_price = 75.56;

        return $item;
    }

    private function getBackUrls () {
        return [
            'success' => env('MP_ENV') === 'PRD' ? env('PROD_URL_CALLBACK_SUCCESS') : env('TEST_URL_CALLBACK_SUCCESS'),
            'failure' => env('MP_ENV') === 'PRD' ? env('PROD_URL_CALLBACK_FAILURE') : env('TEST_URL_CALLBACK_FAILURE'),
            'pending' => env('MP_ENV') === 'PRD' ? env('PROD_URL_CALLBACK_PENDING') : env('TEST_URL_CALLBACK_PENDING')
        ];
    }

    private function getAccessToken () {
        return env('MP_ENV') === 'PRD' ? env('PROD_ACCESS_TOKEN') : env('TEST_ACCESS_TOKEN');
    }

    private function getPaymentMethods() {
        return [
            "excluded_payment_methods" => array(
                // array("id" => "master")
            ),
            "excluded_payment_types" => array(
                // array("id" => "ticket")
            ),
            "installments" => 10 // numero maximo de parcelas
        ];
    }
}
