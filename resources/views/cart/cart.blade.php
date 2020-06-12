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

    <div class="hidden-xs">
        @include('layouts.components.cart-stepper', ['step' => ($finish == null ? 'cart' : 'finish')])
    </div>

    <div class="row mb-5">
        <div class="col-sm-12">
            <div class="col-sm-12">
                <h1>Meu carrinho</h1>
            </div>

            @if($finish != null)
            <div class="row py-3">
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
                            <div class="mt-2">
                                <a href="{{ route('website.cart.userEdit', [ 'id' => $cart->user_id ]) }}">
                                    Editar meus dados
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="border rounded mt-2-xs py-4 px-3">
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
            </div>
            @endif

            <div class="col-sm-12">
                <div class="row mt-4 p-3 border-bottom border-top">
                    <div class="col-sm-5">
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
                    @if($finish == null)
                    <div class="col-sm-1 hidden-xs text-center">
                        <strong>Opções</strong>
                    </div>
                    @endif
                </div>
                @foreach($cartItems as $key => $cartItem)
                <div class="row p-3 border-bottom list-items">
                    <div class="col-3 col-sm-2">
                        <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                            <img src="{{ '/uploads/product/' . $cartItem->product_photo_url }}" class="thumbnail border" />
                        </a>
                    </div>
                    <div class="col-9 col-sm-3">
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
                                <div class="visible-xs">
                                    <label for="inputQty" class="mt-2">
                                        <strong>Quantidade</strong>
                                    </label>
                                </div>
                                <select name="qty" id="inputQty" required class="form-control onchange-submit">
                                    @for($i = 1; $i < $cartItem->stock_qty + 1; $i++)
                                        <option value="{{ $i }}" {{ $cartItem->qty === $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 col-sm-2">
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
                    <div class="col-12 col-sm-1 py-1-xs text-center text-left-xs">
                        <a href="{{ route('website.cart.deleteProduct', ['id' => $cartItem->product_id]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                                <i class="fa fa-trash"></i>
                                <span class="visible-xs">Remover produto</span>
                            </button>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-sm-6 hidden-xs">
                    @if($finish != null)
                    <div class="col-sm-6">
                        <a href="{{ route('website.cart.address') }}" class="not-underlined">
                            <button type="button" class="btn btn-lg btn-outline-primary btn-block mt-5">
                                <i class="fa fa-chevron-left"></i>
                                Escolher endereço
                            </button>
                        </a>
                    </div>
                    @endif
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
                        <p class="text-left">
                            <strong>
                                * Você será redirecionado para a página de pagamento da plataforma do Mercado Pago, com segurança.
                            </strong>
                        </p>
                        <form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('website.cart.payment') }}">
                            {!! csrf_field() !!}
                            <button type="submit" class="btn btn-lg btn-success" data-message="Um momento...">
                                Finalizar e pagar
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
