@extends('layouts.account')
@section('pageTitle', 'Perguntas sobre produtos')
@section('sidebarMenuProductQuestionActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-10">
        <h1>Perguntas sobre produtos</h1>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary" method="post" action="{{ route('app.productQuestion.search') }}">
    {!! csrf_field() !!}
    <div class="row mt-4">
        <div class="col-sm-9">
            <div class="form-group">
                <label for="inputName">Buscar por produto</label>
                <input type="text" id="inputName" name="title" class="form-control" placeholder="Nome do produto" value="{{ isset($filter['title']) && isset($filter['title']) ? $filter['title'] : old('title') }}">
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-secondary btn-block mt-0-xs" data-message="Buscando...">Buscar</button>
        </div>
    </div>
</form>

@include('layouts.components.alert-messages')

@if(count($productQuestions) == 0)
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
        <div class="row py-3 mb-2 border-top border-bottom hidden-xs">
            <div class="col-sm-4">
                <strong>Produto</strong>
            </div>
            <div class="col-sm-3">
                <strong>Usuário</strong>
            </div>
            <div class="col-sm-2">
                <strong>Data</strong>
            </div>
            <div class="col-sm-3 text-center">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($productQuestions as $key => $productQuestion)
        <div class="row py-3 mb-1 border rounded mb-2">
            <div class="col-sm-4">
                {{ $productQuestion->product_title }}
            </div>
            <div class="col-sm-3">
                {{ $productQuestion->user_name }}
            </div>
            <div class="col-sm-2">
                {{ $productQuestion->created_at->format('d/m/Y H:i') }}
            </div>
            <div class="col-12 col-sm-3 text-right text-left-xs mt-1-xs">
                @if($loggedUser->profile !== 'CUSTOMER')
                @if($productQuestion->answer == null)
                <a href="{{ route('app.productQuestion.edit', [ 'id' => $productQuestion->id ]) }}" class="not-underlined">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Responder">
                        <i class="fa fa-envelope"></i>
                        <span class="visible-xs">Responder</span>
                    </button>
                </a>
                @endif
                <a href="{{ route('app.productQuestion.delete', [ 'id' => $productQuestion->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                        <span class="visible-xs">Deletar</span>
                    </button>
                </a>
                @endif
            </div>
            <div class="col-sm-12 mt-2">
                <div>
                    <strong>Pergunta</strong>
                </div>
                {{ $productQuestion->question }}
            </div>
            @if($productQuestion->answer != null)
            <div class="col-sm-12">
                <hr />
                <div>
                    <strong>Resposta</strong>
                </div>
                {{ $productQuestion->answer }}
            </div>
            @endif
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $productQuestions->links() }}
        </div>
    </div>
</div>
@endif

@endsection
