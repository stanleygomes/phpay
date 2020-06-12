@extends('layouts.account')
@section('pageTitle', 'Dashboard')
@section('sidebarMenuDashboardActive', 'active')

@section('accountContent')

@include('layouts.components.alert-messages')

<div class="container">
    <div class="">
        <h3>Resumo mensal</h3>
        <div class="row">
            <div class="col-sm-6">
                <p>Referente ao mês de <strong>{{ $monthName }}</strong></p>
            </div>
            <div class="col-sm-6 text-right text-left-xs">
                <span>{{ $dateStart }}</span>
                -
                <span>{{ $dateEnd }}</span>
            </div>
        </div>
    </div>
    <div class="row mt-2-xs">
        @foreach($cartsResume as $key => $cartResume)
        <div class="col-sm-4">
            <div class="alert alert-{{ App\Helper\Helper::statusColorCart($cartResume['last_status']) }}" role="alert">
                <h4 class="alert-heading">
                    <strong>R$ {{ App\Helper\Helper::convertMoneyFromUStoBR($cartResume['price_total']) }}</strong>
                </h4>
                <hr>
                <p class="mb-0">{{ $cartResume['last_status'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="container mt-4">
    <div class="">
        <h3>Pedidos diários</h3>
        <p>Número de pedidos por dia</p>
    </div>
    <div class="row p-3">
        <div class="col-sm-12 border rounded">
            <canvas id="myChart" width="400" height="150"></canvas>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="">
        <h3>Últimos pedidos</h3>
        <p>Os pedidos mais recentes</p>
    </div>
    <div class="col-sm-12 py-2">
        <div class="mt-3">
            <div class="row py-3 mb-1 border-top border-bottom hidden-xs">
                <div class="col-sm-2">
                    <strong>Cód.</strong>
                </div>
                <div class="col-sm-4">
                    <strong>Nome</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Data</strong>
                </div>
                <div class="col-sm-2">
                    <strong>Status</strong>
                </div>
                <div class="col-sm-2 text-center">
                    <strong>Opções</strong>
                </div>
            </div>
            @foreach($carts as $key => $cart)
            <div class="row py-3 mb-1 border-bottom">
                <div class="col-sm-2">
                    <strong>#{{ App\Helper\Helper::formatCartId($cart->id) }}</strong>
                </div>
                <div class="col-sm-4">
                    {{ $cart->user_name }}
                </div>
                <div class="col-sm-2">
                    {{ $cart->order_date != null ? $cart->order_date->format('d/m/Y H:i') : '' }}
                </div>
                <div class="col-sm-2">
                    <span class="badge badge-{{ App\Helper\Helper::statusColorCart($cart->last_status) }}">{{ $cart->last_status }}</span>
                </div>
                <div class="col-12 col-sm-2 text-center text-left-xs mt-2-xs">
                    <a href="{{ route('app.cart.show', [ 'id' => $cart->id ]) }}" class="not-underlined">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar pedido">
                            <i class="fa fa-shopping-bag"></i>
                            <span class="visible-xs">Visualizar pedido</span>
                        </button>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('script')

<script src="/js/chartjs.min.js?v={{ env('APP_VERSION') }}"></script>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($cartsByDay['x'] as $x)
                '{{ App\Helper\Helper::getDayFromDateUS($x) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Pedidos / Dia do mês',
                data: [
                    @foreach($cartsByDay['y'] as $y)
                    '{{ $y }}',
                    @endforeach
                ],
                backgroundColor: [
                    @foreach($cartsByDay['y'] as $y)
                    'rgba(54, 162, 235, 0.2)',
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

@endsection
