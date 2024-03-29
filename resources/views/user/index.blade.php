@extends('layouts.account')
@section('pageTitle', 'Usuários')
@section('sidebarMenuUserActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-10">
        <h1>Usuários</h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('app.user.create') }}" class="not-underlined">
            <button type="submit" class="btn btn-lg btn-primary btn-block hidden-xs">Cadastrar</button>
            <button type="submit" class="btn btn-lg btn-primary rounded-circle py-3 px-4 shadow-lg float-button visible-xs">
                <i class="fa fa-plus"></i>
            </button>
        </a>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.user.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por nome</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" value="{{ isset($filter['name']) ? $filter['name'] : old('name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block mt-0-xs" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

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
        <div class="row py-3 mb-1 border-top border-bottom hidden-xs">
            <div class="col-sm-3">
                <strong>Nome</strong>
            </div>
            <div class="col-sm-4">
                <strong>Email</strong>
            </div>
            <div class="col-sm-2">
                <strong>Perfil</strong>
            </div>
            <div class="col-sm-3 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($users as $key => $user)
        <div class="row py-3 mb-1 border-bottom">
            <div class="col-sm-3">
                {{ $user->name }}
            </div>
            <div class="col-sm-4">
                {{ $user->email }}
            </div>
            <div class="col-sm-2">
                <span class="badge badge-secondary">{{ $user->profile }}</span>
            </div>
            <div class="col-12 col-sm-3 text-right text-left-xs mt-1-xs">
                <a href="{{ route('app.user.edit', [ 'id' => $user->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-pencil-alt"></i>
                        <span class="visible-xs">Editar</span>
                    </button>
                </a>
                <a href="{{ route('app.user.passwordGenerate', [ 'id' => $user->id ]) }}" class="not-underlined confirmAction" data-message="Deseja gerar uma nova senha?">
                    <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Gerar nova senha">
                        <i class="fa fa-key"></i>
                        <span class="visible-xs">Senha</span>
                    </button>
                </a>
                <a href="{{ route('app.user.delete', [ 'id' => $user->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar o usuário {{ $user->name }}?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                        <span class="visible-xs">Deletar</span>
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
