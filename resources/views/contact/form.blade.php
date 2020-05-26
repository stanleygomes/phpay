@extends('layouts.app')
@section('pageTitle', 'Responder mensagem')
@section('sidebarMenuContactActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.contact.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>Responder mensagem</h1>
        <p>A mensagem será respondida ao remetente abaixo.</p>
    </div>
</div>

<div class="col-sm-12">
    <div class="row shadow p-3 mb-3 bg-white rounded">
        <div class="col-sm-3">
            {{ $contact->name }}
        </div>
        <div class="col-sm-4">
            {{ $contact->email }}
        </div>
        <div class="col-sm-3">
            {{ $contact->phone }}
        </div>
        <div class="col-sm-2 text-right">
            <a href="{{ route('app.contact.delete', [ 'id' => $contact->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar a mensagem de {{ $contact->name }}?">
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                    <i class="fa fa-trash"></i>
                </button>
            </a>
        </div>
        <div class="col-sm-12 mt-2 pt-2 border-top">
            <strong>Mensagem</strong>
            <div>
                {{ $contact->message }}
            </div>
        </div>
    </div>
</div>

<form class="search-form formulary mt-3" method="post" action="{{ route('app.contact.reply', [ 'id' => $contact->id ]) }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <input type="hidden" name="contact_id" value="{{ $contact->id }}">

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="inputMessage">Resposta*</label>
                <textarea name="message" id="inputMessage" name="message" class="form-control" rows="5" required autofocus>{{ old('message') }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Enviando...">Responder</button>
        </div>
    </div>

    @if(count($contactReplies) > 0)
    <h3 class="mt-5 mb-3">Respostas</h3>
    @endif

    <div class="col-sm-12">
        @foreach($contactReplies as $key => $reply)
        <div class="row shadow p-3 mb-3 bg-white rounded">
            <div class="col-sm-6">
                Data: {{ $reply->created_at->format('d/m/Y') }} br
            </div>
            <div class="col-sm-6">
                Usuário: {{ $reply->user_name }}
            </div>
            <div class="col-sm-12 mt-2 pt-2 border-top">
                <strong>Mensagem:</strong>
                <div>
                    {{ $reply->message }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

</form>

@endsection
