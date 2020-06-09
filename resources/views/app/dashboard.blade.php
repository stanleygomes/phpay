@extends('layouts.app')
@section('pageTitle', 'Dashboard')
@section('sidebarMenuDashboardActive', 'active')

@section('content')

@include('layouts.components.alert-messages')

<div class="container">
    <div class="">
        <h3>Resumo mensal</h3>
        <div class="row">
            <div class="col-sm-6">
                <p>Referente ao mês de <strong>{{ $monthName }}</strong></p>
            </div>
            <div class="col-sm-6 text-right">
                <span>{{ $dateStart }}</span>
                -
                <span>{{ $dateEnd }}</span>
            </div>
        </div>
    </div>
    <div class="row">
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
    <div class="row p-3 border rounded">
        <div class="col-sm-12">
            <canvas id="myChart" width="400" height="150"></canvas>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="col-sm-12">
        <div class="mt-3">
            <div class="row p-3 mb-1 border-top border-bottom">
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
            <div class="row p-3 mb-1 border-bottom">
                <div class="col-sm-2">
                    <strong>#{{ App\Helper\Helper::formatCartId($cart->id) }}</strong>
                </div>
                <div class="col-sm-4">
                    {{ $cart->user_name }}
                </div>
                <div class="col-sm-2">
                    {{ $cart->date_order != null ? $cart->date_order->format('d/m/Y H:i') : '' }}
                </div>
                <div class="col-sm-2">
                    <span class="badge badge-{{ App\Helper\Helper::statusColorCart($cart->last_status) }}">{{ $cart->last_status }}</span>
                </div>
                <div class="col-sm-2 text-center">
                    <a href="{{ route('app.cart.show', [ 'id' => $cart->id ]) }}" class="not-underlined">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar pedido">
                            <i class="fa fa-shopping-bag"></i>
                        </button>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!--
<div class="container mt-4">
    <div class="row p-3 border rounded">
        <div class="col-sm-6">
            <div class="">
                <h3>Pedidos por forma de pagamento</h3>
                <p>Número de pedidos por dia</p>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="">
                <h3>Pedidos por categoria</h3>
                <p>Número de pedidos por dia</p>
            </div>
            <canvas id="myChart2" width="400" height="250"></canvas>
        </div>
    </div>
</div>
-->

@endsection

@section('script')

<script src="/js/chartjs.min.js?v={{ env('APP_VERSION') }}"></script>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
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

    var ctx2 = document.getElementById('myChart2').getContext('2d');

    // For a pie chart
    var myPieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            datasets: [{
                data: [10, 20, 30],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ]
            }],

            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [
                'Red',
                'Yellow',
                'Blue'
            ]
        },
        options: {}
    });
</script>

@endsection
