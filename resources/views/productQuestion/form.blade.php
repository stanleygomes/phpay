@extends('layouts.account')
@section('pageTitle', 'Responder pergunta')
@section('sidebarMenuProductQuestionActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.productQuestion.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>Responder pergunta</h1>
        <p>A mensagem será respondida ao remetente abaixo.</p>
    </div>
</div>

<div class="col-sm-12">
    <div class="row shadow p-3 mb-3 bg-white rounded">
        <div class="col-sm-5">
            {{ $user->name }}
        </div>
        <div class="col-sm-4">
            {{ $user->email }}
        </div>
        <div class="col-sm-3">
            {{ $user->phone }}
        </div>
        <div class="col-sm-12 mt-2 pt-2 border-top">
            <strong>Pergunta</strong>
            <div>
                {{ $productQuestion->question }}
            </div>
        </div>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ route('app.productQuestion.update', [ 'id' => $productQuestion->id ]) }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-12 mt-3">
            <div class="form-group">
                <label for="inputAnswer">Enviar resposta*</label>
                <textarea name="answer" id="inputAnswer" name="answer" class="form-control" rows="5" required autofocus>{{ old('answer') }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Enviando...">Enviar</button>
        </div>
    </div>
</form>

@endsection
