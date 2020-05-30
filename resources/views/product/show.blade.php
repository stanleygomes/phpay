@extends('layouts.website')
@section('pageTitle', $product->title)

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('website.product.home') }}">Página inicial</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('website.product.byCategory', [ 'id' => $category->id ]) }}">{{ $category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div id="carouselFeatured" class="carousel slide bg-light bg-loading" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($productPhotos as $key => $productPhoto)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ '/uploads/product/' . $productPhoto->photo_url }}" class="w-100 d-block shadow bg-white rounded-lg" />
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
                <div class="carousel-indicators carousel-thumbnails">
                    @foreach($productPhotos as $key => $productPhoto)
                    <img src="{{ '/uploads/product/' . $productPhoto->photo_url }}" class="shadow mr-2 bg-white rounded-lg carousel-thumbnail {{ $key === 0 ? 'active' : '' }}" data-target="#carouselFeatured" data-slide-to="{{ $key }}" />
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <h3 class="card-title">{{ $product->title }}</h3>
            <p>{{ $category->name }}</p>
            <h4>
                <strong>R$ {{ \App\Helper\Helper::convertMoneyFromUStoBR($product->price) }}</strong>
            </h4>
            <p class="card-text">
                {{ $product->description_short }}
            </p>
            @if($product->stock_qty < 5) <p class="text-danger">Apenas {{ $product->stock_qty }} unidades em estoque.</p>
                @endif
                <!--
            <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
            4.0 stars
            Formas de pagamento
            Parcelamentos
            -->
                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-sm mt-2 btn-outline-danger">
                            <i class="fa fa-heart"></i>
                            Favorito
                        </button>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="">
                            <button type="button" class="btn btn-lg btn-success">
                                <i class="fa fa-shopping-cart"></i>
                                Comprar
                            </button>
                        </a>
                    </div>
                </div>
        </div>
    </div>

    <div class="card card-outline-secondary my-5 border-0 shadow rounded">
        <div class="card-header py-3 border-0">
            <strong>Descrição completa</strong>
        </div>
        <div class="card-body">
            {!! $product->description !!}
        </div>
    </div>

    @if (count($relatedProducts) > 0)
    <div class="row mt-3 mb-3">
        <div class="col-sm-12 mb-2">
            <h3>
                <strong>Produtos relacionados</strong>
            </h3>
        </div>
        @foreach($relatedProducts as $key => $product)
        <div class="col-md-3">
            <a href="{{ route('website.product.show', [ 'id' => $product->id, 'slug' => $product->slug ]) }}" class="not-underlined">
                <div class="card mb-4 border-0 shadow">
                    <img src="{{ '/uploads/product/' . $product->photo_main_url }}" class="w-100 border bg-loading" />
                    <div class="card-body color">
                        <div class="text-dark">
                            <strong>{{ $product->title }}</strong>
                        </div>
                        <div class="text-dark">{{ $product->category_name }}</div>
                        <div class="text-dark">{{ \App\Helper\Helper::truncateText($product->description_short, 50) }}</div>
                        <h5 class="text-dark mt-2">
                            <strong>R$ {{ \App\Helper\Helper::convertMoneyFromUStoBR($product->price) }}</strong>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-warning mt-3">
                            <!-- <i class="fa fa-shopping-cart"></i> -->
                            Visualizar
                        </button>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endif

    <div class="card card-outline-secondary my-5 border-0 shadow rounded">
        <div class="card-header py-3 border-0">
            <strong>Dúvidas sobre o produto</strong>
        </div>
        <div class="card-body">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
            <small class="text-muted">Posted by Anonymous on 3/1/17</small>
            <hr>
            <form enctype="multipart/form-data" class="formulary mt-5" method="post" action="{{ route('website.contact.send') }}">
                {!! csrf_field() !!}
                <h3>
                    <strong>Ficou alguma dúvida?</strong>
                </h3>

                @include('layouts.components.alert-messages')

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="inputMessage">Escreva aqui*</label>
                            <textarea name="message" id="inputMessage" name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">Deixe uma pergunta</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
