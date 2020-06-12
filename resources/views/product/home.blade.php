@extends('layouts.website')
@section('pageTitle', 'Página inicial')

@section('content')

<div class="container">
    <div class="row">
        <!-- <div class="col-sm-3">
            <button class="btn btn-outline-primary navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCategory" aria-controls="navbarCategory" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars text-dark"></i>
            </button>
        </div> -->
        <!-- <div class="collapse navbar-collapse" id="navbarCategory"> -->
        <!-- </div> -->

        <div class="col-sm-12">
            <div class="mb-2">
                @include('layouts.components.alert-messages')
            </div>
            <div id="carouselFeatured" class="carousel slide bg-light bg-loading" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach($featureds as $key => $featured)
                    <li data-target="#carouselFeatured" data-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach($featureds as $key => $featured)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <a href="{{ $featured->link }}">
                            <img src="{{ '/uploads/featured/' . $featured->photo_url }}" class="w-100 d-block shadow bg-white rounded-lg" />
                        </a>
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
            <div class="mt-4">
                <div class="col-sm-12 mb-3">
                    <h3>Categorias</h3>
                </div>
                <div>
                    @include('layouts.components.category-navbar')
                </div>
            </div>
            <div class="mt-3 mb-5">
                @if(count($products) > 0)
                <div class="col-sm-12 mb-3">
                    <h3>Produtos <span class="hidden-xs">em destaque</span></h3>
                </div>
                @endif
                <div class="row">
                    @foreach($products as $key => $product)
                    <div class="col-md-4">
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
                                    <!--
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-3">
                                        Visualizar
                                    </button>
                                    -->
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('website.product.search') }}" class="mt-3 visible-xs">
                        <button class="btn btn-primary">
                            Ver mais produtos
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @endsection
