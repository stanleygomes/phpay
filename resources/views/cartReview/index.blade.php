@extends('layouts.app')
@section('pageTitle', 'Avaliações')
@section('sidebarMenuCartReviewActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <h1>Avaliações</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.cartReview.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por nome</label>
                <input type="text" id="inputName" name="user_name" class="form-control" placeholder="Nome" value="{{ isset($filter['user_name']) ? $filter['user_name'] : old('user_name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($cartReviews) == 0)
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
        <div class="row p-3 mb-3 border-top border-bottom">
            <div class="col-sm-3">
                <strong>Nome</strong>
            </div>
            <div class="col-sm-4">
                <strong>Email</strong>
            </div>
            <div class="col-sm-2">
                <strong>Celular</strong>
            </div>
            <div class="col-sm-1">
                <strong>Nota</strong>
            </div>
            <div class="col-sm-2 text-center">
                <strong>Opções</strong>
            </div>
        </div>

        @foreach($cartReviews as $key => $cartReview)
        <div class="row p-3 mb-1 border rounded mb-2">
            <div class="col-sm-3">
                {{ $cartReview->user_name }}
            </div>
            <div class="col-sm-4">
                {{ $cartReview->user_email }}
            </div>
            <div class="col-sm-2">
                {{ $cartReview->user_phone }}
            </div>
            <div class="col-sm-1">
                <strong>Nota&nbsp;{{ $cartReview->evaluation }}</strong>
            </div>
            <div class="col-sm-2 text-right">
                <a href="{{ route('app.cart.show', [ 'id' => $cartReview->cart_id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar pedido">
                        <i class="fa fa-shopping-bag"></i>
                    </button>
                </a>
                <!--
                <a href="{{ route('app.cartReview.delete', [ 'id' => $cartReview->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                    </button>
                </a>
                -->
            </div>
            <div class="col-sm-10 pt-2 border-top">
                <strong>Mais informações</strong>
                <div>
                    {{ $cartReview->description }}
                </div>
            </div>
            <div class="col-sm-2">
                <a href="{{ route('app.cart.show', [ 'id' => $cartReview->cart_id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm mt-3" data-toggle="tooltip" data-placement="top" title="Visualizar pedido">
                        <strong>#{{ App\Helper\Helper::formatCartId($cartReview->cart_id) }}</strong>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $cartReviews->links() }}
        </div>
    </div>
</div>@endif

@endsection
