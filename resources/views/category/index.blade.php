@extends('layouts.app')
@section('pageTitle', 'Categorias')
@section('sidebarMenuCategoryActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-10">
        <h1>Categorias</h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('app.category.create') }}" class="not-underlined">
            <button type="submit" class="btn btn-lg btn-primary btn-block">Cadastrar</button>
        </a>
    </div>
</div>

<form class="search-form formulary" method="post" action="{{ route('app.category.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por nome</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" value="{{ $filter ? $filter['name'] : old('name') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($categories) == 0)
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
        <div class="row p-3 mb-1 border-top border-bottom">
            <div class="col-sm-9">
                <strong>Nome</strong>
            </div>
            <div class="col-sm-3 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($categories as $key => $category)
        <div class="row p-3 mb-1 border-bottom">
            <div class="col-sm-9">
                {{ $category->name }}
            </div>
            <div class="col-sm-3 text-right">
                <a href="{{ route('app.category.edit', [ 'id' => $category->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                </a>
                <a href="{{ route('app.category.delete', [ 'id' => $category->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar o endereço {{ $category->name }}?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endif

@endsection
