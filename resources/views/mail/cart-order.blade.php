@extends('layouts.email')

@section('body')

<h1>Pedido #{{ App\Helper\Helper::formatCartId($data->id) }}</h1>

<p>Recebemos seu pedido. Obrigado por sua compra. Seguem os dados do pedido abaixo:</p>

<br />

<p>
    <strong>Dados do cliente</strong>
</p>

<p><strong>Nome:</strong> {{ $data->user_name }}</p>
<p><strong>Email:</strong> {{ $data->user_email }}</p>
<p><strong>Telefone:</strong> {{ $data->user_phone }}</p>

<br />

<p>
    <strong>Endereço</strong>
</p>

<strong>{{ $data->address_name }}</strong>
<br />
{{ $data->address_street }}, {{ $data->address_number }} {{ $data->address_complement != null ? ' - ' . $data->address_complement : '' }}
<br />
{{ $data->address_district }} - {{ $data->address_city }} / {{ $data->address_state }}

<br />

<p>
    <strong>Produtos</strong>
</p>

<table width="100%" border="1">
    <tr>
        <th>
            <strong>Foto</strong>
        </th>
        <th>
            <strong>Produto</strong>
        </th>
        <th>
            <strong>Qtd.</strong>
        </th>
        <th>
            <strong>Preço Un.</strong>
        </th>
        <th>
            <strong>Preço total</strong>
        </th>
    </tr>
    @foreach($data->itens as $key => $cartItem)
    <tr>
        <td>
            <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                <img src="{{ public_path() .    '/uploads/product/' . $cartItem->product_photo_url }}" class="thumbnail border" />
            </a>
        </td>
        <td>
            <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                <div>
                    <strong>{{ $cartItem->product_title }}</strong>
                </div>
                <em>Código: {{ $cartItem->product_code }}</em>
            </a>
        </td>
        <td>
            <span>{{ $cartItem->qty }}</span>
        </td>
        <td>
            <span>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price) }}</span>
        </td>
        <td>
            <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price * $cartItem->qty) }}</strong>
        </td>
    </tr>
    @endforeach
</table>

<br />
<br />

Acesse o site para visualizar mais informações sobre seu pedido:

<br />
<br />

<a href="{{ route('auth.login') . '?redir=' . route('app.cart.show', ['id' => $data->id]) }}">
    Clique aqui
</a>

@endsection
