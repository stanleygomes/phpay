@extends('layouts.website')
@section('pageTitle', 'Buscar produtos')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.components.alert-messages')
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <h2 class="mt-2">
                <strong>Resultados</strong>
            </h2>
            @if($filter != null && isset($filter['title']))
            <p>Você buscou por: <strong>{{ $filter['title'] }}</strong></p>
            @endif
        </div>
        <div class="col-sm-6">
            <form enctype="multipart/form-data" class="search-form formulary" method="get" action="{{ route('website.product.search') }}">
                {!! csrf_field() !!}
                <div class="row">
                    <input type="hidden" name="title" value="{{ $filter != null && isset($filter['title']) ? $filter['title'] : '' }}" />
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="category_id" id="inputCategory" required class="form-control onchange-submit">
                                <option value="" {{ $filter == null || !isset($filter['category_id']) ? 'selected 1' : '' }}>
                                    Escolher categoria
                                </option>
                                @foreach($categories as $key => $category)
                                <option value="{{ $category->id }}" {{ $filter != null && isset($filter['category_id']) && $filter['category_id'] == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <select name="order_by" id="inputOrderBy" required class="form-control onchange-submit">
                            <option value="" {{ old('featured') === '' ? 'selected' : '' }}>
                                Ordenar resultados
                            </option>
                            <option value="title#asc" {{ $filter != null && isset($filter['order_by']) && $filter['order_by'] == 'title#asc' ? 'selected' : '' }}>
                                Alfabética
                            </option>
                            <option value="price#asc" {{ $filter != null && isset($filter['order_by']) && $filter['order_by'] == 'price#asc' ? 'selected' : '' }}>
                                Menor preço
                            </option>
                            <option value="price#desc" {{ $filter != null && isset($filter['order_by']) && $filter['order_by'] == 'price#desc' ? 'selected' : '' }}>
                                Maior preço
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row text-center">
        @if(count($products) === 0)
        <div class="col-sm-12 text-center mt-5">
            <h1>
                <div class="mb-3">
                    <i class="fa fa-search"></i>
                </div>
                Não foram encontrados resultados.
            </h1>
        </div>
        @endif
    </div>
    <div class="row mt-3 mb-3">
        @foreach($products as $key => $product)
        <div class="col-md-3">
            <a href="{{ route('website.product.show', [ 'id' => $product->id, 'slug' => $product->slug ]) }}" class="not-underlined">
                <div class="card mb-4 border rounded">
                    <div class="w-100 bg-loading bg-light align-middle text-center card-image-container rounded">
                        <img src="{{ '/uploads/product/' . $product->photo_main_url }}" class="image align-middle" />
                    </div>
                    <div class="card-body color">
                        <h4 class="text-dark">
                            <strong>{{ $product->title }}</strong>
                        </h4>
                        <div class="text-dark"><em>{{ $product->category_name }}</em></div>
                        <div class="text-dark mt-2">{{ \App\Helper\Helper::truncateText($product->description_short, 50) }}</div>
                        <h5 class="text-dark mt-2">
                            <strong>R$ {{ \App\Helper\Helper::convertMoneyFromUStoBR($product->price) }}</strong>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-3">
                            Visualizar
                        </button>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    <div class="row mt-3 mb-3">
        <div class="col-sm-12">
            {{ $products->links() }}
        </div>
    </div>
</div>

@endsection
