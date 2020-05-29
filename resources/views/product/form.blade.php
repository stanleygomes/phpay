@extends('layouts.app')
@section('pageTitle', ($modeEdit === true ? 'Editar' : 'Cadastrar'))
@section('sidebarMenuProductActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.product.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} produto</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.product.update', [ 'id' => $product->id ]) : route('app.product.store') }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputCode">Código*</label>
                <input type="text" id="inputCode" name="code" class="form-control" placeholder="Código" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $product->code : old('code') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputTitle">Título*</label>
                <input type="text" id="inputTitle" name="title" class="form-control" placeholder="Título" required value="{{ $modeEdit === true ? $product->title : old('title') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputPrice">Preço*</label>
                <input type="text" id="inputPrice" name="price" class="form-control mask-money" placeholder="R$ X,XX" required value="{{ $modeEdit === true ? App\Helper\Helper::convertMoneyFromUStoBR($product->price) : old('price') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputTitle">Estoque*</label>
                <input type="number" id="inputTitle" name="stock_qty" class="form-control" placeholder="Estoque" required value="{{ $modeEdit === true ? $product->stock_qty : old('stock_qty') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputCategory">Categoria*</label>
                <select name="category_id" id="inputCategory" required class="form-control">
                    @foreach($categories as $key => $category)
                    <option value="{{ $category->id }}" {{ $modeEdit === true ? ($product->category_id === $category->id ? 'selected' : '') : (old('category_id') === $category->id ? 'selected' : '') }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="inputDescriptionShort">Descrição curta*</label>
                <textarea id="inputDescriptionShort" name="description_short" class="form-control" rows="5" required>{{ $modeEdit === true ? $product->description_short : old('description_short') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="inputDescription">Descrição completa</label>
                <textarea id="inputDescription" name="description" class="form-control summernote" rows="5">{{ $modeEdit === true ? $product->description : old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputPhoto">Adicionar fotos do produto (Ideal: 5 fotos)</label>
                <input class="upload-image" name="photos[]" type="file" multiple>
            </div>
        </div>
        @if($modeEdit === true && $product->photo_main_url != null)
        <div class="col-sm-3">
            <label for="">Foto principal</label>
            <img src="{{ '/uploads/product/' . $product->photo_main_url }}" class="w-100 border" />
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Salvar</button>
        </div>
    </div>

    @if(count($productPhotos) > 0)
    <div class="border-bottom mt-5">
        <h3>Fotos do produto</h3>
    </div>
    <div class="row p-3">
        @foreach($productPhotos as $key => $productPhoto)
        <div class="col-sm-3 border-left border-bottom">
            <div class="row">
                <div class="col-sm-12">
                    <img src="{{ '/uploads/product/' . $productPhoto->photo_url }}" class="w-100 border" />
                </div>
                <div class="col-sm-12 py-2 text-right">
                    <a href="{{ route('app.product.photoMain', [ 'productId' => $product->id, 'photoId' => $productPhoto->id ]) }}" class="not-underlined confirmAction" data-message="Deseja definir essa imagem como principal do produto?">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Definir como imagem principal do produto">
                            <i class="fa fa-image"></i>
                        </button>
                    </a>
                    <a href="{{ route('app.product.photoRemove', [ 'photoId' => $productPhoto->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                            <i class="fa fa-trash"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</form>

@endsection
