<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentMercadoPagoController extends Controller {

    public function preview() {
        /*


<script src="https://www.mercadopago.com/v2/security.js" view="{{ env('APP_DOMAIN') }}"></script>

<form enctype="multipart/form-data" action="{{ route('mercadoPago.preview') }}" method="POST">
    <script
        src="https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js"
        data-header-color="#c0392b"
        data-elements-color="#81ecec"
        data-button-label="Pagar"
        data-preference-id="<?php echo $preference->id; ?>"
    >
    </script>
</form>

Deixar claro para o cliente, que ele será redirecionado para a página do mercado pago para fazer o pagamento com segurança.

<a href="<?php echo $preference->init_point; ?>">Pagar com Mercado Pago</a>

*/


    }

    public function callback(Request $request, $type) {

        // http://localhost:8000/mercado-pago/callback/failure?
        // collection_id=null
        // &collection_status=null
        // &external_reference=
        // &payment_type=null
        // &merchant_order_id=null
        // &preference_id=152969681-9486732f-3779-41fa-b75f-728b964d30d0
        // &site_id=MLB
        // &processing_mode=aggregator
        // &merchant_account_id=null

        // TODO: salvar essa response na tabela relacionando ao pagamento
        $callbackData = $request;
        // TODO: enviar email para o cliente informando o status atual do pedido

        /*

        @if($type === 'success')
        Pagamento efetuado com sucesso.
        @elseif($type === 'pending')
        Recebemos sua solicitação. Seu pagamento ainda está em análise.
        @elseif($type === 'failure')
        Não foi possível processar seu pagamento.
        @endif


        */


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
