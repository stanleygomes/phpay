@extends('layouts.website')
@section('pageTitle', 'Buscar produtos')

@section('content')

<div class="container">
    <div class="row">

        @include('layouts.components.alert-messages')

        <div class="col-sm-6">
            <h2 class="mt-2">
                <strong>Resultados</strong>
            </h2>
            @if($categorySelected != null)
            <p>Produtos da categoria: <strong>{{ $categorySelected->name }}</strong></p>
            @endif
        </div>
        <div class="col-sm-6">
            <form enctype="multipart/form-data" class="search-form formulary" method="get" action="{{ route('website.product.search') }}">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="category_id" id="inputCategory" required class="form-control onchange-submit">
                                <option value="" {{ old('category_id') === '' ? 'selected' : '' }}>
                                    Escolher categoria
                                </option>
                                @foreach($categories as $key => $category)
                                <option value="{{ $category->id }}" {{ $categorySelected != null ? ($categorySelected->id === $category->id ? 'selected' : '') : (old('category_id') === $category->id ? 'selected' : '') }}>
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
                            <option value="YES" {{ old('featured') === '' ? 'selected' : '' }}>
                                Menor preço
                            </option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mt-3 mb-3">
        @foreach($products as $key => $product)
        <div class="col-md-3">
            <a href="{{ route('website.product.show', [ 'id' => $product->id, 'slug' => $product->slug ]) }}" class="not-underlined">
                <div class="card mb-4 border-0 shadow">
                    <img src="{{ '/uploads/product/' . $product->photo_main_url }}" class="w-100 border bg-loading" />
                    <div class="card-body color">
                        <h4 class="text-dark">
                            <strong>{{ $product->title }}</strong>
                        </h4>
                        <div class="text-dark">{{ $product->category_name }}</div>
                        <div class="text-dark">{{ \App\Helper\Helper::truncateText($product->description_short, 50) }}</div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-3">
                            <i class="fa fa-shopping-cart"></i>
                            Visualizar
                        </button>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

@endsection
