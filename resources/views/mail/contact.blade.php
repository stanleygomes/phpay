@extends('layouts.email')

@section('body')

<h3>Mensagem enviada no contato do site</h3>

<p><strong>Nome:</strong> {{ $data->name }}</p>
<p><strong>Email:</strong> {{ $data->email }}</p>
<p><strong>Telefone:</strong> {{ $data->phone }}</p>
<p><strong>Mensagem:</strong> {{ $data->message }}</p>

@endsection
