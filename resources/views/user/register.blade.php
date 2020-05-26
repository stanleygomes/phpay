@extends('layouts.website')
@section('pageTitle', 'Criar conta')

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-sm-12 col-lg-6">
            <form class="auth-form formulary" method="post" action="/app/sa">
                {!! csrf_field() !!}
                <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
                <h1 class="h3 mb-3 font-weight-normal">Criar conta</h1>

                @include('layouts.components.alert-messages')

                <div class="form-group">
                    <label for="inputName">Nome</label>
                    <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required autofocus value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label for="inputPassword">Senha</label>
                    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="********" required value="{{ old('password') }}">
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Cadastrando...">Cadastrar</button>

                <div class="mt-4">
                    <a href="{{ route('auth.passwordRequest') }}">
                        <span>Recuperar senha</span>
                    </a>
                    |
                    <a href="{{ route('auth.login') }}">
                        <span>Entrar</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
