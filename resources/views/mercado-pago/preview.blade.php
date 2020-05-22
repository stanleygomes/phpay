@extends('layouts.website')

@section('content')

<script src="https://www.mercadopago.com/v2/security.js" view="{{ env('APP_DOMAIN') }}"></script>

<form action="{{ route('mercadoPago.preview') }}" method="POST">
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

@endsection
