<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>PHPay - @yield('pageTitle')</title>

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
    <link rel="stylesheet" href="/css/app.css?v={{ env('APP_VERSION') }}" />
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('website.home') }}">Minha Loja</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="w-100 text-center">
                <input class="form-control form-control-dark w-50 align-middle" type="text" placeholder="Search" aria-label="Search">
            </div>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link @yield('pageCartActive')" href="{{ route('website.about') }}">Carrinho</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('pageLoginActive')" href="{{ route('auth.login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('pageRegisterActive')" href="{{ route('auth.register') }}">Cadastrar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-content">
        @yield('content')
    </div>

    <footer class="py-5 bg-light">
        <div class="container">
            <hr />
            <div class="row">
                <div class="col-sm-6">
                    <div>
                        <strong>Minha loja LTDA</strong>
                    </div>
                    <div>
                        <span>CNPJ: 17.000.000/0001-10</span>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.about') }}">
                            Sobre nós
                        </a>
                    </div>
                    <hr />
                    <div class="mt-1">
                        Rua Fulano de tal, 200 - Sala 10 <br />
                        Centro - Uberlandia/MG
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('website.privacy') }}">
                            Termos de uso e privacidade
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 text-right">
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
            <p class="m-0 text-center">Copyright &copy; PHPay {{ date('Y') }}</p>
        </div>
    </footer>

    <script src="/js/jquery-3.5.1.slim.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/popper.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/bootstrap.min.js?v={{ env('APP_VERSION') }}"></script>
    <script src="/js/jquery.mask.min.js?v={{ env('APP_VERSION') }}"></script>
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
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                    console.log('Worker registration successful', registration.scope);
                }, function(err) {
                    console.log('Worker registration failed', err);
                }).catch(function(err) {
                    console.log(err);
                });
            });
        } else {
            console.log('Service Worker is not supported by browser.');
        }
    </script>

    <noscript>Please enable JavaScript to continue using this application.</noscript>

</body>

</html>
