@extends('layouts.account')
@section('pageTitle', 'Meus pedidos')
@section('sidebarMenuCartActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-12">
        @include('layouts.components.alert-messages')
    </div>
    <div class="col-sm-12">
        <a href="{{ route('app.cart.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-9 mt-4">
        <h2>Pedido #{{ App\Helper\Helper::formatCartId($cart->id) }}</h2>
        <p>Data: {{ $cart->order_date != null ? $cart->order_date->format('d/m/Y H:i') : '' }}</p>
    </div>
    <div class="col-sm-3 mt-4 text-right text-left-xs">
        <a href="{{ route('app.cartReview.create', [ 'cart_id' => $cart->id ]) }}" class="not-underlined">
            <button type="button" class="btn btn-warning btn-sm">
                <i class="fa fa-star"></i>
                <strong>Avaliar pedido</strong>
            </button>
        </a>
    </div>
</div>

<div class="cart">
    <div class="row mt-2 py-3">
        <div class="col-sm-6">
            <div class="border rounded py-4 px-3">
                <h5>
                    <strong>Dados pessoais</strong>
                    <hr />
                </h5>
                <div class="mt-2">
                    <strong>{{ $cart->user_name }}</strong>
                    <br />
                    {{ $cart->user_email }}
                    <br />
                    {{ $cart->user_phone }}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="border rounded py-4 px-3 mt-2-xs">
                <h5>
                    <strong>Endereço</strong>
                    <hr />
                </h5>
                <div class="mt-2">
                    <strong>{{ $cart->address_name }}</strong>
                    <br />
                    {{ $cart->address_street }}, {{ $cart->address_number }} {{ $cart->address_complement != null ? ' - ' . $cart->address_complement : '' }}
                    <br />
                    {{ $cart->address_district }} - {{ $cart->address_city }} / {{ $cart->address_state }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="row mt-4 py-3 border-bottom border-top">
            <div class="col-sm-6">
                <strong>Produto</strong>
            </div>
            <div class="col-sm-2 hidden-xs">
                <strong>Qtd.</strong>
            </div>
            <div class="col-sm-2 hidden-xs">
                <strong>Preço Un.</strong>
            </div>
            <div class="col-sm-2 hidden-xs">
                <strong>Preço total</strong>
            </div>
        </div>
        @foreach($cartItems as $key => $cartItem)
        <div class="row py-3 border-bottom list-items">
            <div class="col-sm-1">
                <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                    <img src="{{ '/uploads/product/' . $cartItem->product_photo_url }}" class="thumbnail border" />
                </a>
            </div>
            <div class="col-sm-5">
                <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                    <div>
                        <strong>{{ $cartItem->product_title }}</strong>
                    </div>
                </a>
                <em>Código: {{ $cartItem->product_code }}</em>
            </div>
            <div class="col-sm-2">
                <span class="visible-xs">Quantidade:</span>
                <span>{{ $cartItem->qty }}</span>
            </div>
            <div class="col-sm-2">
                <h5>
                    <span class="visible-xs">Unidade:</span>
                    <span>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price) }}</span>
                </h5>
            </div>
            <div class="col-sm-2">
                <h5>
                    <span class="visible-xs">Total:</span>
                    <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price * $cartItem->qty) }}</strong>
                </h5>
            </div>
        </div>
        @endforeach

        <div class="row p-3">
            <div class="col-3 col-sm-10 text-right">
                <strong>Total:</strong>
            </div>
            <div class="col-9 col-sm-2 text-right-xs">
                <h5>
                    <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR(App\Helper\Helper::sumCartItem($cartItems)) }}</strong>
                </h5>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="row mt-5">
            <div class="col-sm-12">
                <h3>Histórico do pedido</h3>
            </div>
        </div>
        <div class="row mt-4 py-3 border-bottom border-top hidden-xs">
            <div class="col-sm-3">
                <strong>Data</strong>
            </div>
            <div class="col-sm-3">
                <strong>Histórico</strong>
            </div>
            <div class="col-sm-6">
                <strong>Descrição</strong>
            </div>
        </div>
        @foreach($cartHistories as $key => $cartHistory)
        <div class="row py-3 border-bottom list-items">
            <div class="col-sm-3">
                {{ $cartHistory->created_at != null ? $cartHistory->created_at->format('d/m/Y H:i') : '' }}
            </div>
            <div class="col-sm-3">
                <span class="badge badge-{{ App\Helper\Helper::statusColorCart($cartHistory->status) }}">{{ $cartHistory->status }}</span>
            </div>
            <div class="col-sm-6">
                <span>{{ $cartHistory->description }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if($cart->last_status !== $canceledStatus)
<div class="row mt-5">
    <div class="col-sm-12">
        <h3>Solicitação de cancelamento</h3>
        <p>Por favor, descreva o motivo da sua solicitação.</p>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ route('app.cart.cancel', [ 'id' => $cart->id ]) }}">
    {!! csrf_field() !!}

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="inputDescription">Descrição*</label>
                <textarea name="description" id="inputDescription" name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-danger btn-block" data-message="Enviando...">Solicitar</button>
        </div>
    </div>
</form>
@endif

@endsection
