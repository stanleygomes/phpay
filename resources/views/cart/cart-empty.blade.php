@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container pt-5 pb-5">
    <div class="row pt-5 pb-5">
        <div class="col-sm-12 pt-5 pb-5 text-center">
            <h1>
                <i class="fa fa-shopping-bag"></i>
                <div>
                    Carrinho vazio.
                </div>
            </h1>
            <p>Você ainda não adicionou produtos em seu carrinho.</p>
            <a href="{{ route('website.home') }}">
                <button type="button" class="btn btn-lg btn-primary">
                    Buscar produtos
                </button>
            </a>
        </div>
    </div>
</div>

@endsection
