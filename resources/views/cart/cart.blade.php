@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container cart">
    <div class="row mb-5">
        <div class="col-sm-12">
            <h1>Meu carrinho</h1>

            <div class="row p-3 border-bottom border-top">
                <div class="col-sm-6">
                    <strong>Produto</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Qtd.</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Preço</strong>
                </div>
                <div class="col-sm-2 text-center">
                    <strong>Opções</strong>
                </div>
            </div>

            @foreach($cartItems as $key => $cartItem)
            <div class="row p-3 mt-1 border-bottom list-items">
                <div class="col-sm-2">
                    <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                        <img src="{{ '/uploads/product/' . $cartItem->product_photo_url }}" class="thumbnail border" />
                    </a>
                </div>
                <div class="col-sm-4">
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
                        <div class="form-group">
                            <select name="qty" id="inputCategory" required class="form-control onchange-submit">
                                @for($i = 1; $i < $cartItem->stock_qty; $i++)
                                    <option value="{{ $i }}" {{ $cartItem->qty === $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-sm-2">
                    R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price) }}
                </div>
                <div class="col-sm-2 text-center">
                    <a href="{{ route('website.cart.deleteProduct', ['id' => $cartItem->product_id]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                            <i class="fa fa-trash"></i>
                        </button>
                    </a>
                </div>
            </div>
            @endforeach

            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <div class="py-3 border-bottom">
                        <strong>Total</strong>
                    </div>
                    <div class="py-3 border-bottom">
                        <h3>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cart->price_total) }}</h3>
                    </div>
                    <div class="py-3 text-right">
                        <a href="{{ route('website.cart.cart') }}">
                            <button type="button" class="btn btn-lg btn-primary">
                                Continuar
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
