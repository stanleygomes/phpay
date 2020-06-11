@extends('layouts.website')
@section('pageTitle', 'Meu carrinho')
@section('pageCartActive', 'active')

@section('style')
<link rel="stylesheet" href="/css/summernote.min.css?v={{ env('APP_VERSION') }}" />
<link rel="stylesheet" href="/css/fileinput.min.css?v={{ env('APP_VERSION') }}" />
@endsection

@section('content')

<div class="container pb-5">
    <div class="row">
        <div class="col-sm-3">
            <div class="side-menu list-group">
                <!-- ADMIN -->
                @if(Auth::user()->profile === 'ADMIN')
                <div class="col-sm-12">
                    <h4>
                        <strong>Administração</strong>
                    </h4>
                </div>

                <a href="{{ route('app.dashboard') }}" class="list-group-item @yield('sidebarMenuDashboardActive')">Dashboard</a>
                <a href="{{ route('app.config.config') }}" class="list-group-item @yield('sidebarMenuConfigActive') @yield('sidebarMenuStoreActive') @yield('sidebarMenuPaymentMethodsAvailableActive')">Configurações</a>
                <a href="{{ route('app.user.index') }}" class="list-group-item @yield('sidebarMenuUserActive')">Usuários</a>
                @endif

                <!-- COLABORATOR -->
                @if(Auth::user()->profile === 'ADMIN' || Auth::user()->profile === 'COLABORATOR')
                <div class="col-sm-12 mt-4">
                    <h4>
                        <strong>Gerenciar</strong>
                    </h4>
                </div>

                <a href="{{ route('app.cart.index') }}" class="list-group-item @yield('sidebarMenuCartActive')">Pedidos</a>
                <a href="{{ route('app.category.index') }}" class="list-group-item @yield('sidebarMenuCategoryActive')">Categorias</a>
                <a href="{{ route('app.product.index') }}" class="list-group-item @yield('sidebarMenuProductActive')">Produtos</a>
                <a href="{{ route('app.contact.index') }}" class="list-group-item @yield('sidebarMenuContactActive')">Mensagens</a>
                <a href="{{ route('app.featured.index') }}" class="list-group-item @yield('sidebarMenuFeaturedActive')">Destaques</a>
                <a href="{{ route('app.cartReview.index') }}" class="list-group-item @yield('sidebarMenuCartReviewActive')">Avaliações</a>
                <a href="{{ route('app.productQuestion.index') }}" class="list-group-item @yield('sidebarMenuProductQuestionActive')">Perguntas</a>
                @endif

                <!-- CUSTOMER -->
                <div class="col-sm-12 mt-4">
                    <h4>
                        <strong>Minha conta</strong>
                    </h4>
                </div>

                <a href="{{ route('app.user.accountUpdate') }}" class="list-group-item @yield('sidebarMenuUserAccountActive')">Minha conta</a>
                <a href="{{ route('app.cart.index') }}" class="list-group-item @yield('sidebarMenuCartActive')">Minhas compras</a>
                <a href="{{ route('app.wishlistItem.index') }}" class="list-group-item @yield('sidebarMenuWishlistItemActive')">Meus favoritos</a>
                <a href="{{ route('app.address.index') }}" class="list-group-item @yield('sidebarMenuAddressActive')">Endereços</a>
                <a href="{{ route('app.user.passwordChange') }}" class="list-group-item @yield('sidebarMenuUserPasswordActive')">Alterar senha</a>
            </div>
        </div>
        <div class="col-sm-9">
            @yield('accountContent')
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="/js/summernote.min.js?v={{ env('APP_VERSION') }}"></script>
<script src="/js/fileinput.min.js?v={{ env('APP_VERSION') }}"></script>
<script src="/js/admin.js?v={{ env('APP_VERSION') }}"></script>
@endsection
