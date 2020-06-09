@extends('layouts.email')

@section('body')

<h3>Solicitação de cancelamento no site</h3>

<p><strong>Pedido:</strong> #{{ App\Helper\Helper::formatCartId($data->id) }}</p>
<p><strong>Cliente:</strong> {{ $data->user_name }}</p>
<p><strong>Email:</strong> {{ $data->user_email }}</p>
<p><strong>Telefone:</strong> {{ $data->user_phone }}</p>
@if($data->description != null)
<p><strong>Motivo:</strong> {{ $data->description }}</p>
@endif

<br />

Você pode acessar o sistema para visualizar os detalhes da avaliação.

@endsection
