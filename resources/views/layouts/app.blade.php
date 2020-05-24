<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>PHPay - @yield('pageTitle')</title>

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
            <a class="navbar-brand" href="{{ route('website.home') }}">Minha conta</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link @yield('pageAboutActive')" href="{{ route('website.home') }}">Voltar à loja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('pageRegisterActive')" href="{{ route('auth.logout') }}">Sair</a>
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
                        <a href="{{ route('app.user.accountUpdate') }}" class="list-group-item @yield('sidebarMenuUserAccountActive')">Minha conta</a>
                        <a href="{{ route('app.user.index') }}" class="list-group-item @yield('sidebarMenuUserAccount1Active')">Minhas compras</a>
                        <a href="{{ route('app.address.index') }}" class="list-group-item @yield('sidebarMenuUserAddressActive')">Endereços</a>
                        <a href="{{ route('app.user.passwordChange') }}" class="list-group-item @yield('sidebarMenuUserPasswordActive')">Alterar senha</a>

                        <a href="{{ route('app.contact.index') }}" class="list-group-item @yield('sidebarMenuContactActive')">Mensagens</a>
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

</body>

</html>
