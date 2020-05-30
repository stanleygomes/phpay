<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('pageTitle') - {{ env('APP_NAME') }}</title>

    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="description" content="PHPay" />
    <meta name="theme-color" content="#1976d2">

    <!-- icon -->
    <link rel="icon" type="image/png" href="/img/favicon.png?v={{ env('APP_VERSION') }}" />
    <link rel="apple-touch-icon" href="/img/favicon.png?v={{ env('APP_VERSION') }}">

    <!-- add to homescreen for ios -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="Just Do It" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />

    <!-- PWA -->
    <link rel="manifest" href="/manifest.webmanifest?v={{ env('APP_VERSION') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="/css/bootstrap.min.css?v={{ env('APP_VERSION') }}" />
    <link rel="stylesheet" href="/css/font-awesome.min.css?v={{ env('APP_VERSION') }}" />
    <link rel="stylesheet" href="/css/summernote.min.css?v={{ env('APP_VERSION') }}" />
    <link rel="stylesheet" href="/css/fileinput.min.css?v={{ env('APP_VERSION') }}" />
    <link rel="stylesheet" href="/css/app.css?v={{ env('APP_VERSION') }}" />
</head>

<body>

    <nav class="main-menu navbar navbar-expand-lg navbar-dark bg-white border-bottom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('app.dashboard') }}">
                <img src="/uploads/store/logo/{{ App\Helper\Helper::getStoreData()->logo_url }}" class="logo mr-3" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link @yield('pageAboutActive')" href="{{ route('website.home') }}">
                            <span class="btn btn-sm btn-primary">Voltar à loja</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('pageRegisterActive')" href="{{ route('auth.logout') }}">
                            <span class="btn btn-sm btn-outline-primary">Sair</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="side-menu list-group">
                        <!-- CUSTOMER -->
                        <a href="{{ route('app.user.accountUpdate') }}" class="list-group-item @yield('sidebarMenuUserAccountActive')">Minha conta</a>
                        <a href="{{ route('app.user.index') }}" class="list-group-item @yield('sidebarMenuUserCartActive')">Minhas compras</a>
                        <a href="{{ route('app.wishlistItem.index') }}" class="list-group-item @yield('sidebarMenuWishlistItemActive')">Seus favoritos</a>
                        <a href="{{ route('app.address.index') }}" class="list-group-item @yield('sidebarMenuAddressActive')">Endereços</a>
                        <a href="{{ route('app.user.passwordChange') }}" class="list-group-item @yield('sidebarMenuUserPasswordActive')">Alterar senha</a>

                        <!-- COLABORATOR -->
                        <a href="{{ route('app.contact.index') }}" class="list-group-item @yield('sidebarMenuCartActive')">Pedidos</a>
                        <a href="{{ route('app.category.index') }}" class="list-group-item @yield('sidebarMenuCategoryActive')">Categorias</a>
                        <a href="{{ route('app.product.index') }}" class="list-group-item @yield('sidebarMenuProductActive')">Produtos</a>
                        <a href="{{ route('app.contact.index') }}" class="list-group-item @yield('sidebarMenuContactActive')">Mensagens</a>
                        <a href="{{ route('app.featured.index') }}" class="list-group-item @yield('sidebarMenuFeaturedActive')">Destaques</a>
                        <a href="{{ route('app.featured.index') }}" class="list-group-item @yield('sidebarMenuProductReviewActive')">Avaliações</a>
                        <a href="{{ route('app.productQuestion.index') }}" class="list-group-item @yield('sidebarMenuProductQuestionActive')">Perguntas</a>

                        <!-- ADMIN -->
                        <a href="{{ route('app.config.config') }}" class="list-group-item @yield('sidebarMenuConfigActive') @yield('sidebarMenuStoreActive') @yield('sidebarMenuPaymentMethodsAvailableActive')">Configurações</a>
                        <a href="{{ route('app.user.index') }}" class="list-group-item @yield('sidebarMenuUserActive')">Usuários</a>
                    </div>
                </div>
                <div class="col-sm-9">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <footer class="py-5">
        <div class="container">
            <p class="m-0 text-center">Copyright &copy; {{ App\Helper\Helper::getStoreData()->name }} {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="/js/jquery-3.5.1.slim.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/popper.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/bootstrap.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/jquery.mask.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/summernote.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/fileinput.min.js?v={{ env('APP_VERSION') }}"></script>
    <!-- <script src="/js/lazy.min.js?v={{ env('APP_VERSION') }}"></script> -->
    <script src="/js/app.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/admin.js?v={{ env('APP_VERSION') }}"></script>

    <script>
        (function(i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function() {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', '{{ env("ANALYTICS_CODE") }}', 'auto');
        ga('send', 'pageview');
    </script>

    <script>
        // if ('serviceWorker' in navigator) {
        //     window.addEventListener('load', function() {
        //         navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
        //             console.log('Worker registration successful', registration.scope);
        //         }, function(err) {
        //             console.log('Worker registration failed', err);
        //         }).catch(function(err) {
        //             console.log(err);
        //         });
        //     });
        // } else {
        //     console.log('Service Worker is not supported by browser.');
        // }
    </script>

    <noscript>Please enable JavaScript to continue using this application.</noscript>

</body>

</html>
