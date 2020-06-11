@extends('layouts.website')
@section('pageTitle', 'Sem permissão...')

@section('content')

<div class="container pt-5 pb-5 error-page">
    <div class="row pt-5 pb-5">
        <div class="col-sm-12 pt-5 pb-5 text-center">
            <img src="/img/illustration-error.png" class="image" />
            <h1 class="mt-5">Erro 403</h1>
            <p>Este endereço não pode ser acessado.</p>
        </div>
    </div>
</div>

@endsection
