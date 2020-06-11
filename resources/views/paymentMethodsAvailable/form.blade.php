@extends('layouts.account')
@section('pageTitle', 'Cadastrar')
@section('sidebarMenuPaymentMethodsAvailableActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.paymentMethodsAvailable.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} método de pagamento</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.paymentMethodsAvailable.update', [ 'id' => $paymentMethodsAvailable->id ]) : route('app.paymentMethodsAvailable.store') }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputGatewayMethod">Método de pagamento*</label>
                <select name="method_code" id="inputGatewayMethod" required class="form-control">
                    @foreach($paymentGatewayMethodsList as $key => $paymentGatewayMethod)
                    <option value="{{ $paymentGatewayMethod['code'] }}" {{ $modeEdit === true ? ($paymentMethodsAvailable->method_code === $paymentGatewayMethod['code'] ? 'selected' : '') : (old('method_code') === $paymentGatewayMethod['code'] ? 'selected' : '') }}>
                        {{ $paymentGatewayMethod['gateway_name'] . ' - ' . $paymentGatewayMethod['method'] }}
                    </option>
                    @endforeach
                </select>
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
