@extends('layouts.website')
@section('pageTitle', 'Criar uma nova senha')

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-sm-12 col-lg-6">
            <form enctype="multipart/form-data" class="auth-form formulary" method="post" action="{{ route('auth.passwordResetPost') }}">
                {!! csrf_field() !!}
                <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
                <h1 class="h3 mb-3 font-weight-normal">Uma nova senha pra você</h1>
                <p>Por favor, escolha uma senha de pelo menos 8 digitos.</p>

                @include('layouts.components.alert-messages')

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="inputPassword">Nova Senha</label>
                    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="********" minlength="8" autofocus required value="{{ old('password') }}">
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Alterar senha</button>

                <div class="mt-4">
                    <a href="{{ route('auth.login') }}">
                        <span>Login</span>
                    </a>
                    |
                    <a href="{{ route('auth.register') }}">
                        <span>Criar conta</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
