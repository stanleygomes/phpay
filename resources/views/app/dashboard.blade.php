@extends('layouts.app')
@section('pageTitle', 'Dashboard')
@section('sidebarMenuDashboardActive', 'active')

@section('content')

@include('layouts.components.alert-messages')

<div class="container">
    <div class="">
        <h3>Resumo mensal</h3>
        <p>Dados atualizados em 20/05/2020 10:15. Referente ao mês de <strong>Maio</strong></p>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">
                    <strong>R$ 200,00</strong>
                </h4>
                <hr>
                <p class="mb-0">EM CAIXA</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">
                    <strong>R$ 200,00</strong>
                </h4>
                <hr>
                <p class="mb-0">A RECEBER</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">
                    <strong>R$ 200,00</strong>
                </h4>
                <hr>
                <p class="mb-0">CANCELADO</p>
            </div>
        </div>
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
    <div class="">
        <h3>Últimos pedidos</h3>
        <p>Pedidos ordenados pelo número de pedido</p>
    </div>
    <div class="row p-3 border">
        <div class="col-sm-1">
            <strong>Cód.</strong>
        </div>
        <div class="col-sm-4">
            <strong>Cliente</strong>
        </div>
        <div class="col-sm-2">
            <strong>Preço</strong>
        </div>
        <div class="col-sm-3">
            <strong>Data da compra</strong>
        </div>
        <div class="col-sm-2 text-center">
            <strong>Opções</strong>
        </div>
    </div>
    @foreach([1,2,3,4,5] as $key => $p)
    <div class="row p-2 border-bottom border-left border-right">
        <div class="col-sm-1">
            <strong>#4556</strong>
        </div>
        <div class="col-sm-4">
            Nome do cliente
        </div>
        <div class="col-sm-2">
            R$ 200,00
        </div>
        <div class="col-sm-3">
            {{ date('d/m/Y H:i') }}
        </div>
        <div class="col-sm-2 text-center">
            <a href="{{ route('website.product.show', [ 'id' => 1, 'slug' => '$product->slug' ]) }}" class="not-underlined" target="_blank">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Visualizar o pedido">
                    <i class="fa fa-shopping-cart"></i>
                </button>
            </a>
        </div>
    </div>
    @endforeach
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
