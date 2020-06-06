@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container cart">
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.components.alert-messages')
        </div>
    </div>

    @include('layouts.components.cart-stepper', ['step' => ($finish == null ? 'cart' : 'finish')])

    <div class="row mb-5">
        <div class="col-sm-12">
            <div class="col-sm-12">
                <h1>Meu carrinho</h1>
            </div>

            @if($finish != null)
            <div class="row mt-4 py-3 border-top">
                <div class="col-sm-6 border-right">
                    <div class="col-sm-12">
                        <h5>
                            <strong>Dados pessoais</strong>
                            <hr />
                        </h5>
                        <div class="mt-2">
                            <strong>{{ $cart->user_name }}</strong>
                            <br />
                            {{ $cart->user_email }} - {{ $cart->user_phone }}
                            <div class="mt-2">
                                <a href="{{ route('website.cart.userEdit', [ 'id' => $cart->user_id ]) }}">
                                    Editar meus dados
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
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
                        <div class="mt-2">
                            <a href="{{ route('website.cart.addressEdit', [ 'id' => $cart->address_id ]) }}">
                                Editar este endereço
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row mt-4 p-3 border-bottom border-top">
                <div class="col-sm-5">
                    <strong>Produto</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Qtd.</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Preço Un.</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Preço total</strong>
                </div>
                @if($finish == null)
                <div class="col-sm-1 text-center">
                    <strong>Opções</strong>
                </div>
                @endif
            </div>

            @foreach($cartItems as $key => $cartItem)
            <div class="row p-3 border-bottom list-items">
                <div class="col-sm-2">
                    <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                        <img src="{{ '/uploads/product/' . $cartItem->product_photo_url }}" class="thumbnail border" />
                    </a>
                </div>
                <div class="col-sm-3">
                    <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                        <div>
                            <strong>{{ $cartItem->product_title }}</strong>
                        </div>
                    </a>
                    <em>Código: {{ $cartItem->product_code }}</em>
                </div>
                <div class="col-sm-2">
                    <form enctype="multipart/form-data" class="search-form formulary" method="get" action="{{ route('website.cart.updateProduct', ['id' => $cartItem->product_id]) }}">
                        {!! csrf_field() !!}
                        <div class="form-group w-50">
                            <select name="qty" id="inputCategory" required class="form-control onchange-submit">
                                @for($i = 1; $i < $cartItem->stock_qty + 1; $i++)
                                    <option value="{{ $i }}" {{ $cartItem->qty === $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-sm-2">
                    <h5>
                        <span>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price) }}</span>
                    </h5>
                </div>
                <div class="col-sm-2">
                    <h5>
                        <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price * $cartItem->qty) }}</strong>
                    </h5>
                </div>
                <div class="col-sm-1 text-center">
                    <a href="{{ route('website.cart.deleteProduct', ['id' => $cartItem->product_id]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                            <i class="fa fa-trash"></i>
                        </button>
                    </a>
                </div>
            </div>
            @endforeach

            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-6">
                        <a href="{{ route('website.cart.address') }}" class="not-underlined">
                            <button type="button" class="btn btn-lg btn-outline-primary btn-block mt-5">
                                <i class="fa fa-chevron-left"></i>
                                Escolher endereço
                            </button>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="py-3 border-bottom">
                        <strong>Total</strong>
                    </div>
                    <div class="py-3 border-bottom">
                        <h3>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR(App\Helper\Helper::sumCartItem($cartItems)) }}</h3>
                    </div>
                    <div class="py-3 text-right">
                        @if($finish == null)
                        <a href="{{ route('website.cart.userEdit') }}">
                            <button type="button" class="btn btn-lg btn-primary">
                                Continuar
                            </button>
                        </a>
                        @else
                        <a href="{{ route('website.cart.cart', ['finish' => 'finish']) }}">
                            <button type="button" class="btn btn-lg btn-success">
                                Finalizar
                            </button>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
