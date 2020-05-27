<?php

namespace App\Model;

use MercadoPago;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Log;

class PaymentMercadoPago {
    public function getAccessToken() {
        $accesToken = null;
        $env = env('MP_ENV');

        if ($env === 'PRD') {
            $accesToken = env('PROD_ACCESS_TOKEN');

            if ($accesToken == null || $accesToken == '') {
                throw new AppException('Por favor, informe o PROD_ACCESS_TOKEN no arquivo .env');
            }
        } else if ($env === 'SANDBOX') {
            $accesToken = env('TEST_ACCESS_TOKEN');

            if ($accesToken == null || $accesToken == '') {
                throw new AppException('Por favor, informe o TEST_ACCESS_TOKEN no arquivo .env');
            }
        } else {
            throw new AppException('Por favor, informe o MP_ENV no arquivo .env');
        }

        return $accesToken;
    }

    public function getBackUrls() {
        $env = env('MP_ENV');

        if ($env === 'PRD') {
            $urlSuccess = env('PROD_URL_CALLBACK_SUCCESS');
            $urlFailure = env('PROD_URL_CALLBACK_FAILURE');
            $urlPending = env('PROD_URL_CALLBACK_PENDING');

            if ($urlSuccess == null || $urlSuccess == '') {
                throw new AppException('Por favor, informe o PROD_URL_CALLBACK_SUCCESS no arquivo .env');
            }

            if ($urlFailure == null || $urlFailure == '') {
                throw new AppException('Por favor, informe o PROD_URL_CALLBACK_FAILURE no arquivo .env');
            }

            if ($urlPending == null || $urlPending == '') {
                throw new AppException('Por favor, informe o PROD_URL_CALLBACK_PENDING no arquivo .env');
            }

            return [
                'success' => $urlPending,
                'failure' => $urlFailure,
                'pending' => $urlPending
            ];
        } else {
            $urlSuccess = env('TEST_URL_CALLBACK_SUCCESS');
            $urlFailure = env('TEST_URL_CALLBACK_FAILURE');
            $urlPending = env('TEST_URL_CALLBACK_PENDING');

            if ($urlSuccess == null || $urlSuccess == '') {
                throw new AppException('Por favor, informe o TEST_URL_CALLBACK_SUCCESS no arquivo .env');
            }

            if ($urlFailure == null || $urlFailure == '') {
                throw new AppException('Por favor, informe o TEST_URL_CALLBACK_FAILURE no arquivo .env');
            }

            if ($urlPending == null || $urlPending == '') {
                throw new AppException('Por favor, informe o TEST_URL_CALLBACK_PENDING no arquivo .env');
            }

            return [
                'success' => $urlPending,
                'failure' => $urlFailure,
                'pending' => $urlPending
            ];
        }
    }

    public function getPayer($customer = null) {
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

    public function getItems($items = []) {
        $itemsPayment = [];

        if (count($items) === 0) {
            throw new AppException('Por favor, informe ao menos 1 item para pagamento.');
        }

        for ($i = 0; $i < count($items); $i++) {
            $item = $this->getItem($items[$i]);
            array_push($itemsPayment, $item);
        }

        return $itemsPayment;
    }

    public function getItem($item = null) {
        $item = new MercadoPago\Item();
        $item->title = 'Meu produto';
        $item->quantity = 1;
        $item->unit_price = 75.56;

        return $item;
    }

    public function getPaymentMethods($paymentMethodsAvailable) {
        $excludedPaymentMethods = $this->getExcludedPaymentMethods($paymentMethodsAvailable['methods']);

        Log::debug($excludedPaymentMethods);

        return [
            'excluded_payment_methods' => $excludedPaymentMethods,
            'excluded_payment_types' => [],
            'installments' => $paymentMethodsAvailable['installments'] // numero maximo de parcelas
        ];
    }

    public function getPreference($customer, $items, $paymentMethodsAvailable) {
        $accesToken = $this->getAccessToken();
        $appDomain = env('APP_DOMAIN');
        $mpApp = env('MP_APP');

        if ($appDomain == null || $appDomain == '') {
            throw new AppException('Por favor, informe o APP_DOMAIN no arquivo .env');
        }

        if ($mpApp == null || $mpApp == '') {
            throw new AppException('Por favor, informe o MP_APP no arquivo .env');
        }

        $MP = new MercadoPago\SDK();
        $MP->setAccessToken($accesToken);

        $preference = new MercadoPago\Preference();

        $preference->payer = $this->getPayer($customer);
        $preference->items = $this->getItems($items);
        $preference->back_urls = $this->getBackUrls();
        $preference->payment_methods = $this->getPaymentMethods($paymentMethodsAvailable);
        // $preference->auto_return = "approved";

        $preference->save();

        return $preference;
    }

    public function createCustomerSandbox() {
        $accesToken = $this->getAccessToken();
        $url = 'https://api.mercadopago.com/users/test_user?access_token=' . $accesToken;

        $ch = curl_init();

        $headers = [
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

    public function failureStatusMessage($status, $statusDetail) {
        $statusList = [
            [
                'status' => 'approved',
                'status_detail' => 'accredited',
                'message' => 'Pronto, seu pagamento foi aprovado! No resumo, você verá a cobrança do valor como statement_descriptor.'
            ],
            [
                'status' => 'in_process',
                'status_detail' => 'pending_contingency',
                'message' => 'Estamos processando o pagamento. Não se preocupe, em menos de 2 días úteis te avisaremos por e-mail se foi creditado.'
            ],
            [
                'status' => 'in_process',
                'status_detail' => 'pending_review_manual',
                'message' => 'Estamos processando seu pagamento. Não se preocupe, em menos de 2 días úteis te avisaremos por e-mail se foi creditado ou se necessitamos de mais informação.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_bad_filled_card_number',
                'message' => 'Revise o número do cartão.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_bad_filled_date',
                'message' => 'Revise a data de vencimento.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_bad_filled_other',
                'message' => 'Revise os dados.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_bad_filled_security_code',
                'message' => 'Revise o código de segurança do cartão.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_blacklist',
                'message' => 'Não pudemos processar seu pagamento.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_call_for_authorize',
                'message' => 'Você deve autorizar ao seu método de pagamento o pagamento do valor ao Mercado Pago.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_card_disabled',
                'message' => 'Ligue para o seu método de pagamento para ativar seu cartão. O telefone está no verso do seu cartão.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_card_error',
                'message' => 'Não conseguimos processar seu pagamento.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_duplicated_payment',
                'message' => 'Você já efetuou um pagamento com esse valor. Caso precise pagar novamente, utilize outro cartão ou outra forma de pagamento.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_high_risk',
                'message' => 'Seu pagamento foi recusado. Escolha outra forma de pagamento. Recomendamos meios de pagamento em dinheiro.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_insufficient_amount',
                'message' => 'O seu método de pagamento possui saldo insuficiente.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_invalid_installments',
                'message' => 'O seu método de pagamento não processa pagamentos em installments parcelas.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_max_attempts',
                'message' => 'Você atingiu o limite de tentativas permitido. Escolha outro cartão ou outra forma de pagamento.'
            ],
            [
                'status' => 'rejected',
                'status_detail' => 'cc_rejected_other_reason',
                'message' => 'Seu método de pagamento não processa o pagamento.'
            ]
        ];

        for ($i = 0; $i < count($statusList); $i++) {
            $statusItem = $statusList[$i];

            if ($statusItem['status'] === $status && $statusItem['status'] === $statusDetail) {
                return $statusItem['message'];
            }
        }

        return 'Seu método de pagamento não processa o pagamento.';
    }

    public function getExcludedPaymentMethods($paymentMethodsAvailable = []) {
        $excludedPaymentMethods = [];
        $paymentMethods = $this->getAllPaymentMethods();

        for ($i = 0; $i < count($paymentMethods); $i++) {
            $paymentMethod = $paymentMethods[$i];
            $exists = in_array($paymentMethod['id'], $paymentMethodsAvailable);

            if ($exists === false) {
                $method = [
                    'id' => $paymentMethod['id']
                ];

                array_push($excludedPaymentMethods, $method);
            }
        }

        return $excludedPaymentMethods;
    }

    public function getAllPaymentMethods() {
        $paymentMethods = [
            [
                'method' => 'Visa',
                'type' => 'credit_card',
                'id' => 'visa'
            ],
            [
                'method' => 'Mastercard',
                'type' => 'credit_card',
                'id' => 'master'
            ],
            [
                'method' => 'American Express',
                'type' => 'credit_card',
                'id' => 'amex'
            ],
            [
                'method' => 'Hipercard',
                'type' => 'credit_card',
                'id' => 'hipercard'
            ],
            [
                'method' => 'Diners Club International',
                'type' => 'credit_card',
                'id' => 'diners'
            ],
            [
                'method' => 'Elo',
                'type' => 'credit_card',
                'id' => 'elo'
            ],
            [
                'method' => 'Cartão Mercado Livre',
                'type' => 'credit_card',
                'id' => 'melicard'
            ],
            [
                'method' => 'Boleto Bancario',
                'type' => 'ticket',
                'id' => 'bolbradesco'
            ],
            // [
            //     'method' => 'Dinheiro em conta',
            //     'type' => 'account_money',
            //     'id' => 'account_money'
            // ],
            [
                'method' => 'Giftcard',
                'type' => 'digital_currency',
                'id' => 'giftcard'
            ],
            [
                'method' => 'Pagamento na Lotérica',
                'type' => 'ticket',
                'id' => 'pec'
            ],
        ];

        return $paymentMethods;
    }
}
