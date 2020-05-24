@extends('layouts.app')
@section('pageTitle', 'Usuários')
@section('sidebarMenuUserActive', 'active')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Library</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-sm-10">
        <h1>Usuários</h1>
    </div>
    <div class="col-sm-2">
        <a href="">
            <button type="submit" class="btn btn-lg btn-primary btn-block">Cadastrar</button>
        </a>
    </div>
</div>

<form class="search-form formulary" method="post" action="{{ route('app.user.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por nome</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required value="{{ old('name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.alert-messages')

@if(count($users) == 0)
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
        @foreach($users as $key => $user)
        <div class="row shadow-sm p-3 mb-1 bg-white rounded">
            <div class="col-sm-3">
                <strong>{{ $user->name }}</strong>
            </div>
            <div class="col-sm-2">
                {{ $user->profile }}
            </div>
            <div class="col-sm-4">
                {{ $user->email }}
            </div>
            <div class="col-sm-3 text-right">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fa fa-pencil-alt"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Gerar nova senha">
                    <i class="fa fa-key"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
