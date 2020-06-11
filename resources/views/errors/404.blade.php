@extends('layouts.website')
@section('pageTitle', 'Página não encontrada...')

@section('content')

<div class="container pt-5 pb-5 error-page">
    <div class="row pt-5 pb-5">
        <div class="col-sm-12 pt-5 pb-5 text-center">
            <img src="/img/illustration-error.png" class="image" />
            <h1 class="mt-5">Erro 404</h1>
            <p>A página que você procurou não foi encontrada.</p>
        </div>
    </div>
</div>

@endsection
