@extends('layouts.app')
@section('pageTitle', 'Alterar senha')
@section('sidebarMenuUserPasswordActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12 mt-3">
        <h1>Alterar senha</h1>
        <p>Por favor, escolha uma senha de pelo menos 8 digitos.</p>
    </div>
</div>

<form class="search-form formulary mt-3" method="post" action="{{ route('app.user.passwordChangePost') }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputPassword">Senha atual</label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="********" minlength="8" autofocus required value="{{ old('password') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputNewPassword">Nova Senha</label>
                <input type="password" id="inputNewPassword" name="password_new" class="form-control" placeholder="********" minlength="8" required value="{{ old('password_new') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Alterar senha</button>
        </div>
    </div>
</form>

@endsection
