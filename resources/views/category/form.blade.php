@extends('layouts.app')
@section('pageTitle', ($modeEdit === true ? 'Editar' : 'Cadastrar'))
@section('sidebarMenuCategoryActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.category.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} Categoria</h1>
    </div>
</div>

<form class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.category.update', [ 'id' => $category->id ]) : route('app.category.store') }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputName">Nome*</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $category->name : old('name') }}">
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
