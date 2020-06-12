@extends('layouts.account')
@section('pageTitle', 'Produtos')
@section('sidebarMenuProductActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-10">
        <h1>Produtos</h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('app.product.create') }}" class="not-underlined">
            <button type="submit" class="btn btn-lg btn-primary btn-block hidden-xs">Cadastrar</button>
            <button type="submit" class="btn btn-lg btn-primary rounded-circle py-3 px-4 shadow-lg float-button visible-xs">
                <i class="fa fa-plus"></i>
            </button>
        </a>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.product.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputTitle">Buscar por título</label>
                <input type="text" id="inputTitle" name="title" class="form-control" placeholder="Tiulo" value="{{ isset($filter['title']) ? $filter['title'] : old('name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block mt-0-xs" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($products) == 0)
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
            <div class="col-sm-2">
                <strong>Código</strong>
            </div>
            <div class="col-sm-3">
                <strong>Título</strong>
            </div>
            <div class="col-sm-2">
                <strong>Preço</strong>
            </div>
            <div class="col-sm-2">
                <strong>Categoria</strong>
            </div>
            <div class="col-sm-3 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($products as $key => $product)
        <div class="row py-3 mb-1 border-bottom">
            <div class="col-sm-2">
                <span class="visible-xs">Cód.:</span>
                {{ $product->code }}
            </div>
            <div class="col-sm-3">
                {{ $product->title }}
            </div>
            <div class="col-sm-2">
                <span class="visible-xs">Preço:</span>
                R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($product->price) }}
            </div>
            <div class="col-sm-2">
                <span class="visible-xs">Categoria:</span>
                {{ $product->category_name }}
            </div>
            <div class="col-12 col-sm-3 text-right text-left-xs mt-1-xs">
                <a href="{{ route('website.product.show', [ 'id' => $product->id, 'slug' => $product->slug ]) }}" class="not-underlined" target="_blank">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar no site">
                        <i class="fa fa-store-alt"></i>
                        <span class="visible-xs">Visualizar</span>
                    </button>
                </a>
                <a href="{{ route('app.product.edit', [ 'id' => $product->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-pencil-alt"></i>
                        <span class="visible-xs">Editar</span>
                    </button>
                </a>
                <a href="{{ route('app.product.delete', [ 'id' => $product->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                        <span class="visible-xs">Deletar</span>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endif

@endsection
