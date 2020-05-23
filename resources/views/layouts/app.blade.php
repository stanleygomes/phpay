<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>PHPay - @yield('pageTitle')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/app.css" />
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
                        <a class="nav-link @yield('pageAboutActive')" href="{{ route('website.about') }}">Loja</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @yield('pageRegisterActive')" href="{{ route('auth.register') }}">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="list-group">
                        <a href="#" class="list-group-item">Dados da loja</a>
                        <a href="#" class="list-group-item">Compras</a>
                        <a href="#" class="list-group-item">Category 3</a>
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

    <script src="/js/jquery-3.5.1.slim.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>

</body>

</html>
