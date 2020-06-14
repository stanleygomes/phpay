@extends('layouts.email')

@section('body')

<h3>Seu pagamento foi aprovado! Obrigado por sua compra.</h3>

<p><strong>Pedido:</strong> #{{ App\Helper\Helper::formatCartId($data->id) }}</p>

<br />
<br />

Acesse o site para visualizar mais informações sobre seu pedido:

<br />
<br />

<a href="{{ route('auth.login') . '?redir=' . route('app.cart.show', ['id' => $data->id]) }}">
    Clique aqui
</a>

@endsection
