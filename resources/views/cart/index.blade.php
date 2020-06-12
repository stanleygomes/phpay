@extends('layouts.account')
@section('pageTitle', 'Meus pedidos')
@section('sidebarMenuCartActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-10">
        <h1>Meus pedidos</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.cart.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por código</label>
                <input type="text" id="inputName" name="id" class="form-control" placeholder="Código" value="{{ isset($filter['id']) ? $filter['id'] : old('id') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block mt-0-xs" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($carts) == 0)
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
        <div class="row py-3 mb-1 border-top border-bottom hidden-xs">
            <div class="col-sm-2">
                <strong>Cód.</strong>
            </div>
            <div class="col-sm-4">
                <strong>Nome</strong>
            </div>
            <div class="col-sm-2">
                <strong>Data</strong>
            </div>
            <div class="col-sm-2">
                <strong>Status</strong>
            </div>
            <div class="col-sm-2 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($carts as $key => $cart)
        <div class="row py-3 mb-1 border-bottom">
            <div class="col-sm-2">
                <strong>#{{ App\Helper\Helper::formatCartId($cart->id) }}</strong>
            </div>
            <div class="col-sm-4">
                {{ $cart->user_name }}
            </div>
            <div class="col-sm-2">
                {{ $cart->order_date != null ? $cart->order_date->format('d/m/Y H:i') : '' }}
            </div>
            <div class="col-sm-2">
                <span class="badge badge-{{ App\Helper\Helper::statusColorCart($cart->last_status) }}">{{ $cart->last_status }}</span>
            </div>
            <div class="col-12 col-sm-2 text-right text-left-xs mt-2-xs">
                <a href="{{ route('app.cartReview.create', [ 'cart_id' => $cart->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Avaliar pedido">
                        <i class="fa fa-star"></i>
                        <span class="visible-xs">Avaliar</span>
                    </button>
                </a>
                <a href="{{ route('app.cart.show', [ 'id' => $cart->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar pedido">
                        <i class="fa fa-shopping-bag"></i>
                        <span class="visible-xs">Visualizar</span>
                    </button>
                </a>
                {{--
                <a href="{{ route('app.cart.delete', [ 'id' => $cart->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                    <i class="fa fa-trash"></i>
                </button>
                </a>
                --}}
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $carts->links() }}
        </div>
    </div>
</div>
@endif

@endsection
