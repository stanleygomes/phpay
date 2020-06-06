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
    <link rel="stylesheet" href="/css/bs-stepper.min.css?v={{ env('APP_VERSION') }}" />
    <link rel="stylesheet" href="/css/app.css?v={{ env('APP_VERSION') }}" />
</head>

<body>

    <nav class="main-menu navbar navbar-expand-lg bg-white border-bottom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('website.home') }}">
                <img src="/uploads/store/logo/{{ App\Helper\Helper::getStoreData()->logo_url }}" class="logo mr-3" />
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="w-100 text-center">
                <form enctype="multipart/form-data" class="search-form formulary" method="get" action="{{ route('website.product.search') }}">
                    {!! csrf_field() !!}
                    <input type="hidden" name="category_id" value="{{ isset($filter) && isset($filter['category_id']) ? $filter['category_id'] : '' }}" />
                    <input type="hidden" name="order_by" value="{{ isset($filter) && isset($filter['order_by']) ? $filter['order_by'] : '' }}" />
                    <input class="form-control w-100 align-middle" name="title" type="text" placeholder="Buscar produtos" aria-label="Search" value="{{ isset($filter) && isset($filter['title']) ? $filter['title'] : '' }}" />
                </form>
            </div>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto px-3">
                    <li class="nav-item">
                        <a class="nav-link ml-4 mr-2" href="{{ route('app.wishlistItem.index') }}">
                            <i class="fa fa-heart button-icon icon-30"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mr-4 position-relative" href="{{ route('website.cart.cart') }}">
                            <i class="fa fa-shopping-bag button-icon position-relative icon-30"></i>
                            <strong class="text-dark bg-warning text-center badge-cart-item-count">{{ App\Helper\Helper::getCartItemCount() }}</strong>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('pageLoginActive')" href="{{ route('auth.login') }}">
                            <span class="btn btn-sm btn-primary">Minha&nbsp;conta</span>
                        </a>
                    </li>
                    @if(Auth::user() == null)
                    <li class="nav-item">
                        <a class="nav-link @yield('pageRegisterActive')" href="{{ route('auth.register') }}">
                            <span class="btn btn-sm btn-outline-primary">Cadastrar</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-content">
        @yield('content')
    </div>

    <footer class="py-5 bg-light main-footer">
        <div class="container">
            <hr />
            <div class="row">
                <div class="col-sm-6">
                    <div>
                        <strong>{{ App\Helper\Helper::getStoreData()->name }}</strong>
                    </div>
                    <div>
                        <span>CNPJ: {{ App\Helper\Helper::getStoreData()->cpf_cnpj }}</span>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.about') }}">
                            Sobre nós
                        </a>
                    </div>
                    <hr />
                    <div class="mt-1">
                        {{ App\Helper\Helper::getStoreData()->formatAddress }}
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.privacy') }}">
                            Termos de uso e privacidade
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="mt-2">
                        @if(App\Helper\Helper::getStoreData()->instagram_url != null)
                        <a href="{{ App\Helper\Helper::getStoreData()->instagram_url }}">
                            <i class="social-icon ml-2 fab fa-instagram text-dark"></i>
                        </a>
                        @endif
                        @if(App\Helper\Helper::getStoreData()->facebook_url != null)
                        <a href="{{ App\Helper\Helper::getStoreData()->facebook_url }}">
                            <i class="social-icon ml-2 fab fa-facebook-square text-dark"></i>
                        </a>
                        @endif
                        @if(App\Helper\Helper::getStoreData()->youtube_url != null)
                        <a href="{{ App\Helper\Helper::getStoreData()->youtube_url }}">
                            <i class="social-icon ml-2 fab fa-youtube-square text-dark"></i>
                        </a>
                        @endif
                        @if(App\Helper\Helper::getStoreData()->twitter_url != null)
                        <a href="{{ App\Helper\Helper::getStoreData()->twitter_url }}">
                            <i class="social-icon ml-2 fab fa-twitter-square text-dark"></i>
                        </a>
                        @endif
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.delivery') }}">
                            Entregas
                        </a>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.returning') }}">
                            Devoluções e cancelamentos
                        </a>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.faq') }}">
                            Dúvidas frequentes
                        </a>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.contact.form') }}">
                            Fale conosco
                        </a>
                    </div>
                </div>
            </div>
            <p class="m-0 text-center">Copyright &copy; {{ App\Helper\Helper::getStoreData()->name }} {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="/js/jquery-3.5.1.slim.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/popper.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/bootstrap.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/jquery.mask.min.js?v={{ env('APP_VERSION') }}"></script>
    <!-- <script src="/js/lazy.min.js?v={{ env('APP_VERSION') }}"></script> -->
    <script src="/js/app.js?v={{ env('APP_VERSION') }}"></script>

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
