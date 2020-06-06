@extends('layouts.app')
@section('pageTitle', ($modeEdit === true ? 'Editar' : 'Cadastrar'))
@section('sidebarMenuCartReviewActive', 'active')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('app.cart.show', ['id' => $cartId]) }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} avaliação</h1>
        <p>Deixe sua opinião sobre seu pedido</p>
    </div>
</div>

<form enctype="multipart/form-data" class="search-form formulary mt-3" method="post" action="{{ $modeEdit === true ? route('app.cartReview.update', [ 'id' => $cartReview->id ]) : route('app.cartReview.store', [ 'cart_id' => $cartId ]) }}">
    {!! csrf_field() !!}

    @include('layouts.components.alert-messages')

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label for="inputEvaluation">Perfil*</label>
                <select name="evaluation" id="inputEvaluation" required class="form-control">
                    @foreach([1, 2, 3, 4, 5] as $key => $evaluation)
                    <option value="{{ $evaluation }}" {{ $modeEdit === true ? ($cartReview->evaluation === $evaluation ? 'selected' : '') : (old('evaluation') === $evaluation ? 'selected' : '') }}>
                        Nota {{ $evaluation }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="inputDescription">Mais algum detalhe?</label>
                <textarea id="inputDescription" name="description" class="form-control" rows="5">{{ $modeEdit === true ? $cartReview->description : old('description') }}</textarea>
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
