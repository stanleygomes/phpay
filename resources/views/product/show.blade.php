@extends('layouts.website')
@section('pageTitle', $product->title)

@section('content')

<div class="container">

    <div class="row">
        <div class="col-sm-12">
            @include('layouts.components.alert-messages')
        </div>
    </div>

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
                    <div class="carousel-item text-center {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ '/uploads/product/' . $productPhoto->photo_url }}" class="bg-white rounded-lg carousel-image" />
                    </div>
                    @endforeach
                </div>
                @if(isset($productPhotos) && count($productPhotos) > 1)
                <a class="carousel-control-prev" href="#carouselFeatured" role="button" data-slide="prev">
                    <span class="text-dark carousel-control-icon shadow rounded-circle bg-white py-3 px-4">
                        <i class="fa fa-chevron-left"></i>
                    </span>
                    <span class="sr-only">Próximo</span>
                </a>
                <a class="carousel-control-next" href="#carouselFeatured" role="button" data-slide="next">
                    <span class="text-dark carousel-control-icon shadow rounded-circle bg-white py-3 px-4">
                        <i class="fa fa-chevron-right"></i>
                    </span>
                    <span class="sr-only">Anterior</span>
                </a>
                @endif
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
            @if($product->stock_qty < 5) <p class="text-danger">Apenas {{ $product->stock_qty }} unidades em estoque.</p> @endif
                <div class="row">
                    <div class="col-sm-6">
                        @if($wishlistItem != null)
                        <a href="{{ route('app.wishlistItem.deleteByProductId', [ 'id' => $product->id ]) }}">
                            <i class="fa fa-heart text-danger icon-30" data-toggle="tooltip" data-placement="top" data-title="Remover dos favoritos"></i>
                        </a>
                        @else
                        <a href="{{ route('app.wishlistItem.create', [ 'id' => $product->id ]) }}">
                            <i class="far fa-heart text-danger icon-30" data-toggle="tooltip" data-placement="top" data-title="Adicionar aos favoritos"></i>
                        </a>
                        @endif
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('website.cart.addProduct', [ 'id' => $product->id]) }}">
                            <button type="button" class="btn btn-lg btn-success">
                                <i class="fa fa-shopping-bag"></i>
                                Adicionar ao carrinho
                            </button>
                        </a>
                    </div>
                </div>
        </div>
    </div>

    <div class="card card-outline-secondary my-5 border rounded">
        <div class="card-header py-3 border-0">
            <h3>
                <strong>Descrição completa</strong>
            </h3>
        </div>
        <div class="card-body">
            {!! $product->description !!}
        </div>
    </div>

    <div class="card card-outline-secondary my-5 border rounded">
        <div class="card-header py-3 border-0">
            <h3>
                <strong>Perguntas sobre o produto</strong>
            </h3>
        </div>
        <div class="card-body">
            @foreach($productQuestions as $key => $productQuestion)
            <p>
                <strong>{{ $productQuestion->question }}</strong>
            </p>
            <p>{{ $productQuestion->answer }}</p>
            <small class="text-muted">{{ $productQuestion->user_name }} em {{ $productQuestion->created_at->format('d/m/Y H:i') }}</small>
            <hr>
            @endforeach
            <form enctype="multipart/form-data" class="formulary mt-5" method="post" action="{{ route('app.productQuestion.store') }}">
                {!! csrf_field() !!}
                <h3>
                    <strong>Ficou alguma dúvida?</strong>
                </h3>

                <input type="hidden" name="product_id" value="{{ $product->id }}" />

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="inputQuestion">Escreva aqui*</label>
                            <textarea name="question" id="inputQuestion" name="question" class="form-control" rows="5" required>{{ old('question') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3 mb-3">
                        <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Enviando pergunta...">Deixe uma pergunta</button>
                    </div>
                </div>
            </form>
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
    @endif

</div>

@endsection
