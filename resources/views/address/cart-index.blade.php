@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container cart mb-5">
    <div class="hidden-xs">
        @include('layouts.components.cart-stepper', ['step' => 'address'])
    </div>

    @include('layouts.components.alert-messages')

    <div class="row mt-5">
        <div class="col-sm-12">
            <h2 class="ml-4">Por favor, confirme seu endereço</h2>
        </div>
    </div>

    @if(count($addresses) == 0)
    <div class="text-center mt-5">
        <h1>
            <div class="mb-3">
                <i class="fa fa-search"></i>
            </div>
            Não foram encontrados resultados.
        </h1>
    </div>
    @else
    <div class="col-sm-12">
        <div class="mt-3">
            @foreach($addresses as $key => $address)
            <div class="row p-3 mb-1 border-top">
                <div class="col-sm-8">
                    <h4>
                        <strong>{{ $address->name }}</strong>
                    </h4>
                    {{ $address->street }}, {{ $address->number }} {{ $address->complement != null ? ' - ' . $address->complement : '' }}
                    <br />
                    {{ $address->district }} - {{ $address->city }} / {{ $address->state }}
                </div>
                <div class="col-sm-2 text-right">
                    <a href="{{ route('website.cart.addressEdit', [ 'id' => $address->id ]) }}" class="not-underlined">
                        <button type="button" class="btn btn-lg btn-block btn-outline-primary mt-3">
                            Editar
                        </button>
                    </a>
                </div>
                <div class="col-sm-2 text-right">
                    <a href="{{ route('website.cart.addressConfirm', [ 'id' => $address->id ]) }}" class="not-underlined">
                        <button type="button" class="btn btn-lg btn-block btn-primary mt-3">
                            Selecionar
                        </button>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="row mt-5">
        <div class="col-sm-3">
            <a href="{{ route('website.cart.userEdit') }}" class="not-underlined">
                <button type="button" class="btn btn-lg btn-outline-primary btn-block mt-3">
                    <i class="fa fa-chevron-left"></i>
                    Editar meus dados
                </button>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ route('website.cart.addressCreate') }}" class="not-underlined">
                <button type="button" class="btn btn-lg btn-outline-primary btn-block mt-3">
                    Cadastrar novo endereço
                </button>
            </a>
        </div>
    </div>
</div>
@endsection
