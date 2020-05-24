@extends('layouts.app')
@section('pageTitle', ($modeEdit === true ? 'Editar' : 'Cadastrar'))
@section('sidebarMenuUserActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.user.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} usuário</h1>
    </div>
</div>

<form class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.user.update', [ 'id' => $user->id ]) : route('app.user.store') }}">
    {!! csrf_field() !!}

    @include('layouts.alert-messages')

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputName">Nome</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $user->name : old('name') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ $modeEdit === true ? $user->email : old('email') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputProfile">Perfil</label>
                <select name="profile" id="inputProfile" class="form-control">
                    @foreach($profiles as $key => $profile)
                    <option value="{{ $profile }}" {{ $modeEdit === true ? ($user->profile === $profile ? 'selected' : '') : (old('profile') === $profile ? 'selected' : '') }}>
                        {{ $profile }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Salvar</button>
        </div>
    </div>
</form>

@endsection
