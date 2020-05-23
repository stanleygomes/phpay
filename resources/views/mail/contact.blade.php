@extends('layouts.email')

@section('title', 'Contato')

@section('body')

<h3>Mensagem enviada no contato do site</h3>

<p><strong>NOME:</strong> {{ $data->name }}</p>
<p><strong>EMAIL:</strong> {{ $data->email }}</p>
<p><strong>TELEFONE:</strong> {{ $data->phone }}</p>
<p><strong>MENSAGEM:</strong> {{ $data->message }}</p>

<br>

@endsection
