@extends('layouts.website')
@section('pageTitle', 'Efetue o login')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <form enctype="multipart/form-data" class="auth-form mb-5 formulary" method="post" action="{{ route('auth.loginPost') }}">
                {!! csrf_field() !!}
                <!-- <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72"> -->
                <h1 class="h3 mt-5 mb-3 font-weight-normal">Por favor, efetue o login</h1>

                @include('layouts.components.alert-messages')

                <input type="hidden" name="redir" value="{{ $redir }}" />
                <input type="hidden" name="redirMessage" value="{{ $redirMessage }}" />

                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required autofocus value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="inputPassword">Senha</label>
                    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="********" minlength="8" required value="{{ old('password') }}">
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Um momento...">Entrar</button>

                <div class="mt-4">
                    <a href="{{ route('auth.passwordRequest') }}">
                        <span>Recuperar senha</span>
                    </a>
                    |
                    <a href="{{ route('auth.register') }}?redir={{ $redir }}&redirMessage={{ $redir }}">
                        <span>Criar conta</span>
                    </a>
                </div>
            </form>
        </div>
        <div class="col-sm-6 text-center">
            <img src="/img/illustration-login.png" class="w-100 d-block align-middle" />
            <div>
                <h3>Ainda não tem conta?</h3>
                <p>Clica no botão abaixo.</p>
                <a href="{{ route('auth.register') }}?redir={{ $redir }}&redirMessage={{ $redir }}">
                    <button type="submit" class="btn btn-lg btn-success" data-message="Um momento...">Criar uma conta</button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
