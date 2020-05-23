@extends('layouts.website')
@section('pageTitle', 'Página inicial')

@section('content')

<div class="title m-b-md">
    PHPay
</div>

<div class="links">
    <a href="{{ route('mercadoPago.preview') }}">Mercado Pago</a>
    <a href="">Pagseguro</a>
    <a href="">PagarMe</a>
</div>

@endsection
