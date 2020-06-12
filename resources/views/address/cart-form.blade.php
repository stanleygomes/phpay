@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container cart mb-5">
    <div class="hidden-xs">
        @include('layouts.components.cart-stepper', ['step' => 'address'])
    </div>

    <div class="row">
        <div class="col-sm-12 mt-3">
            <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} endereço</h1>
        </div>
    </div>

    <form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('website.cart.addressUpdate', ['id' => $address->id]) : route('website.cart.addressStore') }}">
        {!! csrf_field() !!}

        @include('layouts.components.alert-messages')

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="inputName">Nome para identificar o endereço*</label>
                    <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $address->name : old('name') }}">
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="inputZipcode">CEP*</label>
                    <input type="text" id="inputZipcode" name="zipcode" class="form-control mask-zipcode" placeholder="CEP" required value="{{ $modeEdit === true ? $address->zipcode : old('zipcode') }}">
                </div>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="inputStreet">Logradouro*</label>
                    <input type="text" id="inputStreet" name="street" class="form-control" placeholder="Logradouro" required value="{{ $modeEdit === true ? $address->street : old('street') }}">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="inputNumber">Nro.*</label>
                    <input type="text" id="inputNumber" name="number" class="form-control" placeholder="Nro." required value="{{ $modeEdit === true ? $address->number : old('number') }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="inputComplement">Complemento</label>
                    <input type="text" id="inputComplement" name="complement" class="form-control" placeholder="Complemento" value="{{ $modeEdit === true ? $address->complement : old('complement') }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="inputDistrict">Bairro*</label>
                    <input type="text" id="inputDistrict" name="district" class="form-control" placeholder="Bairro" required value="{{ $modeEdit === true ? $address->district : old('district') }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="inputCity">Cidade*</label>
                    <input type="text" id="inputCity" name="city" class="form-control" placeholder="Cidade" required value="{{ $modeEdit === true ? $address->city : old('city') }}">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="inputState">Estado*</label>
                    @include('layouts.components.states-br', [
                    'stateValue' => ($modeEdit === true ? $address->state : old('state')),
                    'stateRequired' => 'required',
                    'stateId' => 'inputState',
                    'stateName' => 'state'
                    ])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="w-100-xs float-right">
                    <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Salvar</button>
                </div>
                <div class="w-100-xs float-left">
                    <a href="{{ route('website.cart.address') }}" class="not-underlined">
                        <button type="button" class="btn btn-lg btn-outline-primary btn-block mt-3">
                            <i class="fa fa-chevron-left"></i>
                            Voltar aos endereços
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
