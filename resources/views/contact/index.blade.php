@extends('layouts.app')
@section('pageTitle', 'Mensagens no site')
@section('sidebarMenuContactActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-10">
        <h1>Mensagens no site</h1>
        <p>Mensagens enviadas no formulário de contato.</p>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.contact.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por nome</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" value="{{ isset($filter['name']) ? $filter['name'] : old('name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($contacts) == 0)
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
            <div class="col-sm-3">
                <strong>Celular</strong>
            </div>
            <div class="col-sm-2 text-center">
                <strong>Opções</strong>
            </div>
        </div>

        @foreach($contacts as $key => $contact)
        <div class="row p-3 mb-1 border rounded mb-2">
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
                <a href="{{ route('app.contact.reply', [ 'id' => $contact->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Responder">
                        <i class="fa fa-envelope"></i>
                    </button>
                </a>
                <a href="{{ route('app.contact.delete', [ 'id' => $contact->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
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
        @endforeach

        <div class="row mt-5">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endif

@endsection
