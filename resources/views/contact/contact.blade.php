@extends('layouts.website')
@section('pageTitle', 'Fale conosco')

@section('content')

<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-sm-12 col-lg-6">
            <form enctype="multipart/form-data" class="auth-form formulary" method="post" action="{{ route('website.contact.send') }}">
                {!! csrf_field() !!}
                <h1 class="h3 mb-3 font-weight-normal">Deixe-nos uma mensagem abaixo.</h1>
                <p>Responderemos o mais breve possível!</p>

                @include('layouts.components.alert-messages')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="inputMessage">Mensagem*</label>
                            <textarea name="message" id="inputMessage" name="message" class="form-control" rows="5" required autofocus>{{ old('message') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputName">Nome*</label>
                            <input type="text" id="inputName" name="name" class="form-control" placeholder="Seu nome" required value="{{ old('message') }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputEmail">Email*</label>
                            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="inputPhone">Celular</label>
                            <input type="text" id="inputPhone" name="phone" class="form-control mask-phone" placeholder="Seu nome" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block">Enviar</button>
            </form>
        </div>
    </div>
</div>

@endsection
