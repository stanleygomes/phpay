@extends('layouts.website')
@section('pageTitle', 'Recuperar senha')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <form enctype="multipart/form-data" class="auth-form mb-5 formulary" method="post" action="{{ route('auth.passwordRequestPost') }}">
                {!! csrf_field() !!}
                <!-- <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72"> -->
                <h1 class="h3 mt-5 mb-3 font-weight-normal">Recuperar senha</h1>

                @include('layouts.components.alert-messages')

                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required autofocus value="{{ old('email') }}">
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Enviando...">Solicitar</button>

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
