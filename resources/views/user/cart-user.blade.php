@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container cart">

    <div class="hidden-xs">
        @include('layouts.components.cart-stepper', ['step' => 'user'])
    </div>

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-12">
            <h2>Por favor, confirme seus dados</h2>
        </div>
    </div>
    <form enctype="multipart/form-data" class="search-form formulary mt-3 mb-5" method="post" action="{{ route('website.cart.userUpdate') }}">
        {!! csrf_field() !!}

        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <label for="inputName">Nome*</label>
                    <input type="text" id="inputName" autofocus name="name" class="form-control" placeholder="Nome" required value="{{ $user->name }}">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="inputCpf">CPF</label>
                    <input type="text" id="inputCpf" name="cpf" class="form-control mask-cpf" placeholder="CPF" value="{{ $user->cpf }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="inputSex">Sexo</label>
                    <select name="sex" id="inputSex" required class="form-control">
                        <option value="F" {{ $user->sex === 'F' ? 'selected' : '' }}>
                            Feminino
                        </option>
                        <option value="M" {{ $user->sex === 'M' ? 'selected' : '' }}>
                            Masculino
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="inputEmail">Email*</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ $user->email }}">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="inputPhone">Celular*</label>
                    <input type="text" id="inputPhone" name="phone" class="form-control mask-phone" required placeholder="Celular" value="{{ $user->phone }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="w-100-xs float-right text-right">
                    <button type="submit" class="btn btn-lg btn-primary mt-3 btn-block" data-message="Salvando...">Continuar</button>
                </div>
                <div class="w-100-xs float-left">
                    <a href="{{ route('website.cart.cart') }}" class="not-underlined">
                        <button type="button" class="btn btn-lg btn-outline-primary btn-block mt-3">
                            <i class="fa fa-chevron-left"></i>
                            Voltar ao carrinho
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
