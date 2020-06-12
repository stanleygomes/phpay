@extends('layouts.account')
@section('pageTitle', 'Destaques')
@section('sidebarMenuFeaturedActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-10">
        <h1>Destaques</h1>
    </div>
    <div class="col-sm-2">
        <a href="{{ route('app.featured.create') }}" class="not-underlined">
            <button type="submit" class="btn btn-lg btn-primary btn-block hidden-xs">Cadastrar</button>
            <button type="submit" class="btn btn-lg btn-primary rounded-circle py-3 px-4 shadow-lg float-button visible-xs">
                <i class="fa fa-plus"></i>
            </button>
        </a>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.featured.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputTitle">Buscar por título</label>
                <input type="text" id="inputTitle" name="title" class="form-control" placeholder="Título" value="{{ isset($filter['title']) ? $filter['title'] : old('title') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block mt-0-xs" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($featureds) == 0)
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
                <strong>Imagem</strong>
            </div>
            <div class="col-sm-4">
                <strong>Título</strong>
            </div>
            <div class="col-sm-3">
                <strong>Posição</strong>
            </div>
            <div class="col-sm-3 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($featureds as $key => $featured)
        <div class="row py-3 mb-1 border-bottom">
            <div class="col-sm-2">
                <img src="{{ '/uploads/featured/' . $featured->photo_url }}" class="w-100 border" />
            </div>
            <div class="col-sm-4 mt-1-xs">
                {{ $featured->title }}
            </div>
            <div class="col-sm-3">
                <span class="visible-xs">Posição:</span>
                {{ $featured->position }}
            </div>
            <div class="col-12 col-sm-3 text-right text-left-xs mt-1-xs">
                <a href="{{ route('app.featured.edit', [ 'id' => $featured->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
                        <i class="fa fa-pencil-alt"></i>
                        <span class="visible-xs">Editar</span>
                    </button>
                </a>
                <a href="{{ route('app.featured.delete', [ 'id' => $featured->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                        <span class="visible-xs">Deletar</span>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $featureds->links() }}
        </div>
    </div>
</div>
@endif

@endsection
