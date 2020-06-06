@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('content')

<div class="container cart">
    <div class="row">
        <div class="col-sm-12">
            @include('layouts.components.alert-messages')
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-sm-12">
            @if($finish != null)
            <div class="mb-3">
                <a href="{{ route('website.cart.cart') }}">
                    <i class="fa fa-arrow-left"></i>
                    <strong>Alterar itens do carrinho</strong>
                </a>
            </div>
            @endif

            <div class="col-sm-12">
                <h1>Meu carrinho</h1>
            </div>

            <div class="row mt-4 py-3 border-top">
                <div class="col-sm-6 border-right">
                    <h5>
                        <strong>Dados pessoais</strong>
                    </h5>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <label for="inputName">Nome*</label>
                                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required value="{{ old('name') || $user->name }}">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputCpf">CPF</label>
                                <input type="text" id="inputCpf" name="cpf" class="form-control mask-cpf" placeholder="CPF" value="{{ old('cpf') || $user->cpf }}">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="inputSex">Sexo</label>
                                <select name="sex" id="inputSex" required class="form-control">
                                    <option value="F" {{ (old('profile') === 'F') || ($user->sex === 'F') ? 'selected' : '' }}>
                                        Feminino
                                    </option>
                                    <option value="M" {{ (old('profile') === 'M') || ($user->sex === 'M') ? 'selected' : '' }}>
                                        Masculino
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputEmail">Email*</label>
                                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ old('email') || $user->email }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="inputPhone">Celular*</label>
                                <input type="text" id="inputPhone" name="phone" class="form-control mask-phone" required placeholder="Celular" value="{{ old('phone') || $user->phone }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h5>
                        <strong>Endereço</strong>
                    </h5>
                </div>

            </div>

            <div class="row mt-4 p-3 border-bottom border-top">
                <div class="col-sm-5">
                    <strong>Produto</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Qtd.</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Preço Un.</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Preço total</strong>
                </div>
                @if($finish == null)
                <div class="col-sm-1 text-center">
                    <strong>Opções</strong>
                </div>
                @endif
            </div>

            @foreach($cartItems as $key => $cartItem)
            <div class="row p-3 border-bottom list-items">
                @if($finish == null)
                <div class="col-sm-2">
                    <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                        <img src="{{ '/uploads/product/' . $cartItem->product_photo_url }}" class="thumbnail border" />
                    </a>
                </div>
                @else
                @endif
                <div class="col-sm-3">
                    <a href="{{ route('website.product.show', ['id' => $cartItem->product_id]) }}">
                        <div>
                            <strong>{{ $cartItem->product_title }}</strong>
                        </div>
                    </a>
                    <em>Código: {{ $cartItem->product_code }}</em>
                </div>
                @if($finish != null)
                <div class="col-sm-2"></div>
                @endif
                <div class="col-sm-2">
                    @if($finish == null)
                    <form enctype="multipart/form-data" class="search-form formulary" method="get" action="{{ route('website.cart.updateProduct', ['id' => $cartItem->product_id]) }}">
                        {!! csrf_field() !!}
                        <div class="form-group w-50">
                            <select name="qty" id="inputCategory" required class="form-control onchange-submit">
                                @for($i = 1; $i < $cartItem->stock_qty + 1; $i++)
                                    <option value="{{ $i }}" {{ $cartItem->qty === $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                    </form>
                    @else
                    <strong>{{ $cartItem->qty }}</strong>
                    @endif
                </div>
                <div class="col-sm-2">
                    <h5>
                        <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price) }}</strong>
                    </h5>
                </div>
                <div class="col-sm-2">
                    <h5>
                        <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartItem->product_price * $cartItem->qty) }}</strong>
                    </h5>
                </div>
                <div class="col-sm-1 text-center">
                    @if($finish == null)
                    <a href="{{ route('website.cart.deleteProduct', ['id' => $cartItem->product_id]) }}" class="not-underlined confirmAction" data-message="Deseja deletar?">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Deletar">
                            <i class="fa fa-trash"></i>
                        </button>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach

            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <div class="py-3 border-bottom">
                        <strong>Total</strong>
                    </div>
                    <div class="py-3 border-bottom">
                        <h3>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cart->price_total) }}</h3>
                    </div>
                    <div class="py-3 text-right">
                        @if($finish == null)
                        <a href="{{ route('website.cart.cart', ['finish' => 'finish']) }}">
                            <button type="button" class="btn btn-lg btn-primary">
                                Continuar
                            </button>
                        </a>
                        @else
                        <a href="{{ route('website.cart.cart', ['finish' => 'finish']) }}">
                            <button type="button" class="btn btn-lg btn-success">
                                Prosseguir para o pagamento
                            </button>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
