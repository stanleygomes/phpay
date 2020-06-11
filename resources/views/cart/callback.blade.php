@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container pt-5 pb-5 error-page">
    <div class="row pt-5 pb-5">
        <div class="col-sm-6 pt-5 pb-5">
            <img src="/img/illustration-{{ $status }}.png" class="image" />
            <h2 class="mt-5">
                <strong>PEDIDO #{{ App\Helper\Helper::formatCartId($cart->id) }}</strong>
            </h2>
            <h4>{{ $description }}</h4>
            <a href="{{ route('app.cart.show', ['id' => $cart->id]) }}">
                <button type="button" class="btn mt-5 btn-lg btn-primary">
                    Mais informações
                </button>
            </a>
        </div>
    </div>
</div>

@endsection
