@extends('layouts.account')
@section('pageTitle', 'Lista de favoritos')
@section('sidebarMenuWishlistItemActive', 'active')

@section('accountContent')

<div class="row">
    <div class="col-sm-10">
        <h1>Lista de favoritos</h1>
    </div>
</div>

@include('layouts.components.alert-messages')

@if(count($wishlistItems) == 0)
<div class="text-center mt-5">
    <h1>
        <div class="mb-3">
            <i class="fa fa-search"></i>
        </div>
    </h1>
    <h3>
        Sem favoritos ainda. você pode buscar produtos no site para adicionar à sua lista
        <a href="{{ route('website.product.home') }}" target="_blank">aqui</a>.
    </h3>
</div>
@else
<div class="col-sm-12">
    <div class="mt-3">
        <div class="row py-3 mb-1 border-top border-bottom">
            <div class="col-sm-9">
                <strong>Produto</strong>
            </div>
            <div class="col-sm-3 text-center hidden-xs">
                <strong>Opções</strong>
            </div>
        </div>
        @foreach($wishlistItems as $key => $wishlistItem)
        <div class="row py-3 mb-1 border-bottom">
            <div class="col-sm-9">
                {{ $wishlistItem->product_title }}
            </div>
            <div class="col-12 col-sm-3 text-right text-left-xs mt-1-xs">
                <a href="{{ route('website.product.show', [ 'id' => $wishlistItem->product_id, 'slug' => $wishlistItem->product_slug ]) }}" class="not-underlined" target="_blank">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar no site">
                        <i class="fa fa-store-alt"></i>
                        <span class="visible-xs">Visualizar</span>
                    </button>
                </a>
                <a href="{{ route('app.wishlistItem.delete', [ 'id' => $wishlistItem->id ]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                        <i class="fa fa-trash"></i>
                        <span class="visible-xs">Deletar</span>
                    </button>
                </a>
            </div>
        </div>
        @endforeach

        <div class="row mt-5">
            {{ $wishlistItems->links() }}
        </div>
    </div>
</div>
@endif

@endsection
