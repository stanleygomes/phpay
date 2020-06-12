@extends('layouts.account')
@section('pageTitle', 'Métodos de pagamento')
@section('sidebarMenuPaymentMethodsAvailableActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-12 mb-4">
        <a href="{{ route('app.config.config') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-10">
        <h1>Métodos de pagamento</h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('app.paymentMethodsAvailable.create') }}" class="not-underlined">
            <button type="submit" class="btn btn-lg btn-primary btn-block hidden-xs">Cadastrar</button>
            <button type="submit" class="btn btn-lg btn-primary rounded-circle py-3 px-4 shadow-lg float-button visible-xs">
                <i class="fa fa-plus"></i>
            </button>
        </a>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.paymentMethodsAvailable.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputGatewayName">Buscar por nome</label>
                <input type="text" id="inputGatewayName" name="method_name" class="form-control" placeholder="Nome" value="{{ isset($filter['method_name']) ? $filter['method_name'] : old('method_name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block mt-0-xs" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($paymentMethodsAvailables) == 0)
<div class="text-center mt-5">
    <h1>
        <div class="mb-3">
            <i class="fa fa-search"></i>
        </div>
        Não foram encontrados resultados.
    </h1>
</div>
@else
<div class="col-sm-12">
    <div class="mt-3">
        <div class="row py-3 mb-1 border-top border-bottom hidden-xs">
            <div class="col-sm-3">
                <strong>Gateway</strong>
            </div>
            <div class="col-sm-3">
                <strong>Método</strong>
            </div>
            <div class="col-sm-3">
                <strong>Adicional</strong>
            </div>
            <div class="col-sm-3 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($paymentMethodsAvailables as $key => $paymentMethodsAvailable)
        <div class="row py-3 mb-1 border-bottom">
            <div class="col-sm-3">
                {{ $paymentMethodsAvailable->gateway }}
            </div>
            <div class="col-sm-3">
                {{ $paymentMethodsAvailable->method_name }}
            </div>
            <div class="col-sm-3">
                {{ $paymentMethodsAvailable->method_id . ' - ' . $paymentMethodsAvailable->method_type }}
            </div>
            <div class="col-12 col-sm-3 text-right text-left-xs mt-1-xs">
                <a href="{{ route('app.paymentMethodsAvailable.delete', [ 'id' => $paymentMethodsAvailable->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar o endereço {{ $paymentMethodsAvailable->name }}?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                        <span class="visible-xs">Deletar</span>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $paymentMethodsAvailables->links() }}
        </div>
    </div>
</div>
@endif

@endsection
