@extends('layouts.account')
@section('pageTitle', 'Cadastrar')
@section('sidebarMenuStoreActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.config.config') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} Empresa</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.store.update', [ 'id' => $store->id ]) : route('app.store.store') }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-12 mt-3">
            <h4>
                <strong>Empresa</strong>
            </h4>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="inputName">Nome*</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $store->name : old('name') }}">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="inputRazaoSocial">Razão social (caso aplique)</label>
                <input type="text" id="inputRazaoSocial" name="razao_social" class="form-control" placeholder="Razão social" value="{{ $modeEdit === true ? $store->razao_social : old('razao_social') }}">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="inputCpfCnpj">CPF/CNPJ*</label>
                <input type="text" id="inputCpfCnpj" name="cpf_cnpj" class="form-control mask-cpfcnpj" placeholder="CPF/CNPJ" required value="{{ $modeEdit === true ? $store->cpf_cnpj : old('cpf_cnpj') }}">
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-sm-12 mt-3">
            <h4>
                <strong>Contato</strong>
            </h4>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputEmail">Email*</label>
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ $modeEdit === true ? $store->email : old('email') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputPhone">Telefone*</label>
                <input type="text" id="inputPhone" name="phone" class="form-control mask-phone" placeholder="Telefone" required value="{{ $modeEdit === true ? $store->phone : old('phone') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputWhatsapp">Whatsapp</label>
                <input type="text" id="inputWhatsapp" name="whatsapp" class="form-control mask-phone" placeholder="Whatsapp" value="{{ $modeEdit === true ? $store->whatsapp : old('whatsapp') }}">
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-sm-12 mt-3">
            <h4>
                <strong>Endereço</strong>
            </h4>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label for="inputZipcode">CEP*</label>
                <input type="text" id="inputZipcode" name="zipcode" class="form-control mask-zipcode" placeholder="CEP" required value="{{ $modeEdit === true ? $store->zipcode : old('zipcode') }}">
            </div>
        </div>
        <div class="col-sm-8">
            <div class="form-group">
                <label for="inputStreet">Logradouro*</label>
                <input type="text" id="inputStreet" name="street" class="form-control" placeholder="Logradouro" required value="{{ $modeEdit === true ? $store->street : old('street') }}">
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label for="inputNumber">Nro.*</label>
                <input type="text" id="inputNumber" name="number" class="form-control" placeholder="Nro." required value="{{ $modeEdit === true ? $store->number : old('number') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputComplement">Complemento</label>
                <input type="text" id="inputComplement" name="complement" class="form-control" placeholder="Complemento" value="{{ $modeEdit === true ? $store->complement : old('complement') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputDistrict">Bairro*</label>
                <input type="text" id="inputDistrict" name="district" class="form-control" placeholder="Bairro" required value="{{ $modeEdit === true ? $store->district : old('district') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputCity">Cidade*</label>
                <input type="text" id="inputCity" name="city" class="form-control" placeholder="Cidade" required value="{{ $modeEdit === true ? $store->city : old('city') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputState">Estado*</label>
                @include('layouts.components.states-br', [
                'stateValue' => ($modeEdit === true ? $store->state : old('state')),
                'stateRequired' => 'required',
                'stateId' => 'inputState',
                'stateName' => 'state'
                ])
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="col-sm-12 mt-3">
            <h4>
                <strong>Redes sociais</strong>
            </h4>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputInstagrm">Intagram</label>
                <input type="url" id="inputIntagram" name="instagram_url" class="form-control" placeholder="URL do instagram" value="{{ $modeEdit === true ? $store->instagram_url : old('instagram_url') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputFacebook">Facebook</label>
                <input type="url" id="inputFacebook" name="facebook_url" class="form-control" placeholder="URL do facebook" value="{{ $modeEdit === true ? $store->facebook_url : old('facebook_url') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputYoutube">Youtube</label>
                <input type="url" id="inputYoutube" name="youtube_url" class="form-control" placeholder="URL do youtube" value="{{ $modeEdit === true ? $store->youtube_url : old('youtube_url') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputTwitter">Twitter</label>
                <input type="url" id="inputTwitter" name="twitter_url" class="form-control" placeholder="URL do twitter" value="{{ $modeEdit === true ? $store->twitter_url : old('twitter_url') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 mt-3">
            <h4>
                <strong>Logo</strong>
            </h4>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputInstagrm">Logo da loja (tamanho ideal: 350px / 150px)</label>
                <input class="upload-image" name="logo_url" type="file" class="file">
            </div>
        </div>
        @if($modeEdit === true && $store->logo_url != null)
        <div class="col-sm-3">
            <label for="">Imagem atual</label>
            <img src="{{ '/uploads/store/logo/' . $store->logo_url }}" class="w-100 border" />
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Salvar</button>
        </div>
    </div>
</form>

@endsection
