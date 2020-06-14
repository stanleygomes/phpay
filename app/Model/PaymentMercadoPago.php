<?php

namespace App\Model;

use MercadoPago;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\Log;
use App\Helper\Helper;

class PaymentMercadoPago {
    public function getAccessToken() {
        $accesToken = null;
        $env = env('MP_ENV');

        if ($env === 'PRD') {
            $accesToken = env('MP_PROD_ACCESS_TOKEN');

            if ($accesToken == null || $accesToken == '') {
                throw new AppException('Por favor, informe o MP_PROD_ACCESS_TOKEN no arquivo .env');
            }
        } else if ($env === 'SANDBOX') {
            $accesToken = env('MP_TEST_ACCESS_TOKEN');

            if ($accesToken == null || $accesToken == '') {
                throw new AppException('Por favor, informe o MP_TEST_ACCESS_TOKEN no arquivo .env');
            }
        } else {
            throw new AppException('Por favor, informe o MP_ENV no arquivo .env');
        }

        return $accesToken;
    }

    public function getBackUrls() {
        return [
            'success' => route('website.cart.callback', ['status' => 'success']),
            'failure' => route('website.cart.callback', ['status' => 'failure']),
            'pending' => route('website.cart.callback', ['status' => 'pending'])
        ];
    }

    public function getPayer($customer = null) {
        $payer = new MercadoPago\Payer();

        $customerName = Helper::splitName($customer->user_name);

        $payer->name = $customerName['first_name'];
        $payer->surname = $customerName['last_name'];
        $payer->email = $customer->user_email;
        $payer->date_created = date('Y-m-d') . 'T' . date('H:m:s') .  '.0-03:00';

        if ($customer->user_phone != null) {
            $phone = Helper::splitPhone($customer->user_phone);
            $payer->phone = array(
                'area_code' => $phone['area_code'],
                'number' => $phone['number']
            );
        }

        if ($customer->user_cpf != null) {
            $payer->identification = array(
                'type' => 'CPF',
                'number' => $customer->user_cpf
            );
        }

        if ($customer->address_street != null) {
            $payer->address = array(
                'street_name' => $customer->address_street,
                'street_number' => $customer->address_number,
                'zip_code' => $customer->address_zipcode
            );
        }

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

    public function getItem($product = null) {
        $item = new MercadoPago\Item();
        $item->title = $product->product_title;
        $item->quantity = $product->qty;
        $item->unit_price = $product->product_price;

        return $item;
    }

    public function getPaymentMethods($paymentMethodsAvailable) {
        $excludedPaymentMethods = $this->getExcludedPaymentMethods($paymentMethodsAvailable['methods']);

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

    public function getPaymentStatusFromApi($request) {
        Log::debug('// -------------- start');
        Log::debug($request);

        $accesToken = $this->getAccessToken();
        $MP = new MercadoPago\SDK();
        $MP->setAccessToken($accesToken);

        $merchantOrder = null;
        $type = $request->type;
        $id = $request->id;
        $dataId = $request->data_id;

        // test a payment
        // curl -X GET https://api.mercadopago.com/v1/payments/:data_id?access_token=ACCESS_TOKEN

        if ($type === null || $dataId === null) {
            $message = 'Parametros {type e data_id} não informados na requisicao.';

            Log::debug('// -------------- start');
            Log::debug($request);
            Log::debug($message);
            Log::debug('// -------------- end');

            return [
                'message' => $message
            ];
        }

        if ($type === 'payment') {
            $payment = MercadoPago\Payment::find_by_id($dataId);
            if ($payment == null) {
                $message = 'Pagamento {' . $dataId . '} não encontrado.';
                Log::debug('// -------------- start');
                Log::debug($request);
                Log::debug($message);
                Log::debug('// -------------- end');
                return ['message' => $message];
            } else {
                $merchantOrderId = $payment->order->id;
                $merchantOrder = MercadoPago\MerchantOrder::find_by_id($merchantOrderId);
                Log::debug('MERCHANT ORDER');
            }
        } else if ($type === 'merchant_order') {
            $merchantOrder = MercadoPago\MerchantOrder::find_by_id($id);
        } else {
            $message = 'Metodo {' . $type . '} não suportado.';
            Log::debug('// -------------- start');
            Log::debug($request);
            Log::debug($message);
            Log::debug('// -------------- end');
            return [
                'message' => $message
            ];
        }

        $fees = [];

        for ($i = 0; $i < count($payment->fee_details); $i++) {
            $fee = $payment->fee_details[$i];
            $feePayerDescription = null;

            if ($fee->fee_payer === 'collector') {
                $feePayerDescription = 'Loja';
            } else if ($fee->fee_payer === 'payer') {
                $feePayerDescription = 'Cliente';
            }

            $feeFormated = [
                'amount' => $fee->amount,
                'fee_payer' => $fee->fee_payer,
                'fee_payer_description' => $feePayerDescription,
                'type' => $fee->type,
                'type_description' => $this->getFee($fee->type)['description']
            ];

            array_push($fees, $feeFormated);
        }

        if ($merchantOrder == null) {
            $message = 'Pedido não encontrado.';
            Log::debug('// -------------- start');
            Log::debug($request);
            Log::debug($message);
            Log::debug('// -------------- end');
            return [
                'message' => $message
            ];
        }

        return [
            'data_id' => $request->data_id,
            'type' => $request->type,
            'operation_type' => $payment->operation_type,
            'date_of_expiration' => $payment->date_of_expiration,
            'preference_id' => $merchantOrder->preference_id,
            'application_fee' => $payment->application_fee,
            'currency_id' => $payment->currency_id,
            'transaction_amount' => $payment->transaction_amount,
            'merchant_order_id' => $payment->order->id,
            'installments' => $payment->installments,
            'payment_type_id' => $payment->payment_type_id,
            'call_for_authorize_id' => $payment->call_for_authorize_id,
            'payment_type_id_description' => $this->getPaymentType($payment->payment_type_id)['description'],
            'payment_method_id' => $payment->payment_method_id,
            'payment_method_id_description' => $this->getPaymentMethod($payment->payment_method_id)['method'],
            'status' => $payment->status,
            'status_description' => $this->getPaymentStatus($payment->status)['description'],
            'status_detail' => $payment->status_detail,
            'fees' => $fees
        ];

        // $paidAmount = 0;
        // foreach ($merchantOrder->payments as $payment) {
        //     if ($payment['status'] == 'approved') {
        //         $paidAmount += $payment['transaction_amount'];
        //     }
        // }

        // quando for implementar a parte de frete
        // $merchantOrder->shipping_cost
        // $merchantOrder->shipments

        // Log::debug('Total pago: ' . $paidAmount);

        // if ($paidAmount >= $merchantOrder->total_amount) {
        //     if (count($merchantOrder->shipments) > 0) {
        //         if ($merchantOrder->shipments[0]->status == 'ready_to_ship') {
        //             Log::debug('Pedido totalmente pago. Preparado para despacho.');
        //         }
        //     } else {
        //         Log::debug('Pedido totalmente pago.');
        //         // TODO: enviar email para o cliente informando que foi pago
        //     }
        // } else {
        //     Log::debug('Pedido ainda pendente.');
        // }

        // Log::debug('// -------------- end');

        // return ['message' => 'Fim da verificação.'];
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

    public function getPaymentStatus($lookup) {
        $allStatus = $this->getAllPaymentStatus();

        for ($i = 0; $i < count($allStatus); $i++) {
            $status = $allStatus[$i];
            if ($status['code'] === $lookup) {
                return $status;
            }
        }

        return null;
    }

    public function getPaymentType($lookup) {
        $allTypes = $this->getAllPaymentTypes();

        for ($i = 0; $i < count($allTypes); $i++) {
            $type = $allTypes[$i];
            if ($type['type'] === $lookup) {
                return $type;
            }
        }

        return null;
    }

    public function getPaymentMethod($lookup) {
        $allMethods = $this->getAllPaymentMethods();

        for ($i = 0; $i < count($allMethods); $i++) {
            $method = $allMethods[$i];
            if ($method['id'] === $lookup) {
                return $method;
            }
        }

        return null;
    }

    public function getFee($lookup) {
        $allFees = $this->getAllFees();

        for ($i = 0; $i < count($allFees); $i++) {
            $fee = $allFees[$i];
            if ($fee['code'] === $lookup) {
                return $fee;
            }
        }

        return null;
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

    public function getAllFees() {
        $fees = [
            [
                'code' => 'mercadopago_fee',
                'description' => 'Custo de utilização do Mercado Pago.'
            ],
            [
                'code' => 'coupon_fee',
                'description' => 'Desconto de cupom.'
            ],
            [
                'code' => 'financing_fee',
                'description' => 'Custos de parcelamento.'
            ],
            [
                'code' => 'shipping_fee',
                'description' => 'Custos de envio.'
            ],
            [
                'code' => 'application_fee',
                'description' => 'Comissão do Marketplace pelo serviço.'
            ],
            [
                'code' => 'discount_fee',
                'description' => 'Desconto dado pelo seller (por meio de absorção dos custos).'
            ]
        ];

        return $fees;
    }

    public function getAllPaymentStatus() {
        $paymentStatus = [
            [
                'code' => 'approved',
                'description' => 'O pagamento foi aprovado.',
            ],
            [
                'code' => 'authorized',
                'description' => 'O pagamento foi autorizado, mas ainda não recebemos.'
            ],
            [
                'code' => 'in_process',
                'description' => 'O pagamento está em análise.'
            ],
            [
                'code' => 'in_mediation',
                'description' => 'Solicitante iniciou uma disputa.'
            ],
            [
                'code' => 'rejected',
                'description' => 'O pagamento foi rejeitado. O cliente precisa tentar novamente.'
            ],
            [
                'code' => 'cancelled',
                'description' => 'O pagamento foi cancelado por uma das partes ou o tempo para pagamento expirou'
            ],
            [
                'code' => 'refunded',
                'description' => 'O pagamento foi devolvido ao cliente.'
            ],
            [
                'code' => 'charged_back',
                'description' => 'Foi feito um estorno no cartão de crédito do cliente.'
            ]
        ];

        return $paymentStatus;
    }

    public function getAllPaymentTypes() {
        $paymentTypes = [
            [
                'type' => 'account_money',
                'description' => 'Dinheiro da conta do MercadoPago.'
            ],
            [
                'type' => 'ticket',
                'description' => 'Boleto impresso.'
            ],
            [
                'type' => 'bank_transfer',
                'description' => 'Transferência bancária.'
            ],
            [
                'type' => 'atm',
                'description' => 'Pagamento em caixa eletonico (ATM).'
            ],
            [
                'type' => 'credit_card',
                'description' => 'Pagamento em cartão de crédito.'
            ],
            [
                'type' => 'debit_card',
                'description' => 'Pagamento em cartão de débito.'
            ],
            [
                'type' => 'prepaid_card',
                'description' => 'Pagamento em cartão pré-pago.'
            ]
        ];

        return $paymentTypes;
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
