@extends('layouts.email')

@section('body')

<h3>Avaliação de compra no site</h3>

<p><strong>Nota:</strong> {{ $data->evaluation }}</p>
@if($data->description != null)
<p><strong>Mensagem:</strong> {{ $data->description }}</p>
@endif

<br />

Você pode acessar o sistema para visualizar os detalhes da avaliação.

@endsection
