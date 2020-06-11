@extends('layouts.website')
@section('pageTitle', 'Sessão expirada...')

@section('content')

<div class="container pt-5 pb-5 error-page">
    <div class="row pt-5 pb-5">
        <div class="col-sm-12 pt-5 pb-5 text-center">
            <img src="/img/illustration-error.png" class="image" />
            <h1 class="mt-5">Erro 419</h1>
            <p>Sessão expirada. Será necessário efetuar o login novamente.</p>
            <br />
            <a href="{{ route('auth.login') }}">Clique aqui</a>
        </div>
    </div>
</div>

@endsection
