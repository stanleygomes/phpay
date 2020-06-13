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
            <a href="{{ $routeCartShow }}">
                <button type="button" class="btn mt-5 btn-lg btn-primary">
                    Mais informações
                </button>
            </a>
        </div>
        <div class="col-sm-6 mt-5">
            @if($status !== 'failure')
            <div class="text-center mt-5">
                {!! QrCode::size(200)->generate($routeCartShow) !!}
                <p class="mt-3">
                    <strong>Acompanhe seu pedido</strong>
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
