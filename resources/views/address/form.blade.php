@extends('layouts.account')
@section('pageTitle', 'Cadastrar')
@section('sidebarMenuAddressActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.address.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} endereço</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.address.update', [ 'id' => $address->id ]) : route('app.address.store') }}">
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
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Salvar</button>
        </div>
    </div>
</form>

@endsection
