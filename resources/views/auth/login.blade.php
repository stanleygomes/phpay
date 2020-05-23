@extends('layouts.website')
@section('pageTitle', 'Efetue o login')

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-sm-12 col-lg-6">
            <form class="auth-form formulary" method="post" action="">
                {!! csrf_field() !!}
                <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
                <h1 class="h3 mb-3 font-weight-normal">Por favor, efetue o login</h1>

                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {!! session('error') !!}
                </div>
                @endif

                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required autofocus>
                </div>

                <div class="form-group">
                    <label for="inputPassword">Senha</label>
                    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="********" required>
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block">Entrar</button>

                <div class="mt-4">
                    <a href="{{ route('auth.passwordRequest') }}">
                        <span>Recuperar senha</span>
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