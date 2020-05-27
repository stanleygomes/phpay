@extends('layouts.app')
@section('pageTitle', ($modeEdit === true ? 'Editar' : 'Cadastrar'))
@section('sidebarMenuFeaturedActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.featured.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} destaque</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.featured.update', [ 'id' => $featured->id ]) : route('app.featured.store') }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-8">
            <div class="form-group">
                <label for="inputTitle">Título*</label>
                <input type="text" id="inputTitle" name="title" class="form-control" placeholder="Título" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $featured->title : old('title') }}">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="inputPosition">Posição (ordem das imagens)*</label>
                <input type="number" id="inputPosition" name="position" class="form-control" placeholder="Posição" required value="{{ $modeEdit === true ? $featured->position : old('position') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputPhoto">Imagem de destaque (tamanho ideal: 850px / 320px)</label>
                <input class="upload-image" name="photo_url" type="file" class="file">
            </div>
        </div>
        @if($modeEdit === true && $featured->logo_url != null)
        <div class="col-sm-3">
            <label for="">Imagem atual</label>
            <img src="{{ '/uploads/featured/' . $featured->photo_url }}" class="w-100 border" />
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
