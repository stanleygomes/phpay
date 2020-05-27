@extends('layouts.app')
@section('pageTitle', 'Configurações da loja')
@section('sidebarMenuConfigActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-10">
        <h1>Configurações da loja</h1>
    </div>
</div>

@include('layouts.components.alert-messages')

<div class="col-sm-12">
    <div class="mt-3">
        <div class="row p-3 mb-1 border-top border-bottom">
            <div class="col-sm-9">
                <strong>Nome</strong>
            </div>
        </div>
        <div class="row p-3 border-bottom">
            <div class="col-sm-9">
                Dados da loja
            </div>
            <div class="col-sm-3 text-right">
                <a href="{{ route('app.store.edit', [ 'id' => 1 ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Configurar">
                        ATUALIZAR
                    </button>
                </a>
            </div>
        </div>
        <div class="row p-3 border-bottom">
            <div class="col-sm-9">
                Formas de pagamento
            </div>
            <div class="col-sm-3 text-right">
                <a href="{{ route('app.paymentMethodsAvailable.index') }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Configurar">
                        CONFIGURAR
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
