@extends('layouts.website')
@section('pageTitle', 'Página inicial')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            @include('layouts.components.category-sidebar')
        </div>
        <div class="col-sm-9">
            <div id="carouselFeatured" class="carousel slide bg-light bg-loading" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach($featureds as $key => $featured)
                    <li data-target="#carouselFeatured" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach($featureds as $key => $featured)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ '/uploads/featured/' . $featured->photo_url }}" class="w-100 d-block shadow bg-white rounded-lg" />
                    </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carouselFeatured" role="button" data-slide="prev">
                    <span class="text-dark carousel-control-icon shadow rounded-circle bg-white py-3 px-4">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                    <!-- <span class="carousel-control-prev-icon carousel-control-icon" aria-hidden="true"></span> -->
                    <span class="sr-only">Próximo</span>
                </a>
                <a class="carousel-control-next" href="#carouselFeatured" role="button" data-slide="next">
                    <span class="text-dark carousel-control-icon shadow rounded-circle bg-white py-3 px-4">
                        <i class="fa fa-chevron-right"></i>
                    </span>
                    <!-- <span class="carousel-control-next-icon" aria-hidden="true"></span> -->
                    <span class="sr-only">Anterior</span>
                </a>
            </div>

            <div class="mt-5 mb-5">
                @if(count($products) > 0)
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <h2>
                            <strong>Produtos em destaque</strong>
                        </h2>
                    </div>
                </div>
                @endif
                <div class="row">
                    @foreach($products as $key => $product)
                    <div class="col-md-4">
                        <a href="{{ route('website.product.show', [ 'id' => $product->id, 'slug' => $product->slug ]) }}" class="not-underlined">
                            <div class="card mb-4 border-0 shadow">
                                <div class="w-100 bg-loading bg-light align-middle text-center card-image-container">
                                    <img src="{{ '/uploads/product/' . $product->photo_main_url }}" class="image align-middle" />
                                </div>
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
        </div>
    </div>
</div>

@endsection
