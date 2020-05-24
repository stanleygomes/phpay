@extends('layouts.app')
@section('pageTitle', 'Usuários')
@section('sidebarMenuUserActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-10">
        <h1>Usuários</h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('app.user.create') }}" class="not-underlined">
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
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" value="{{ $filter ? $filter['name'] : old('name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block" data-message="Buscando...">Buscar</button>
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
            <div class="col-sm-4">
                {{ $user->email }}
            </div>
            <div class="col-sm-2">
                <span class="badge badge-secondary">{{ $user->profile }}</span>
            </div>
            <div class="col-sm-3 text-right">
                <a href="{{ route('app.user.edit', [ 'id' => $user->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                </a>
                <a href="{{ route('app.user.passwordGenerate', [ 'id' => $user->id ]) }}" class="not-underlined confirmAction" data-message="Deseja gerar uma nova senha?">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Gerar nova senha">
                        <i class="fa fa-key"></i>
                    </button>
                </a>
                <a href="{{ route('app.user.delete', [ 'id' => $user->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar o usuário {{ $user->name }}?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endif

@endsection
