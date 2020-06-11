@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container pt-5 pb-5 error-page">
    <div class="row pt-5 pb-5">
        <div class="col-sm-12 pt-5 pb-5 text-center">
            <img src="/img/illustration-empty.png" class="image" />
            <h1 class="mt-5">Carrinho vazio</h1>
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
