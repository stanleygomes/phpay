@extends('layouts.email')

@section('title', 'Contato')

@section('body')

<h3>Pergunta sobre produto no site</h3>

<h4>Produto</h4>
<p><strong>Código:</strong> {{ $data->code }}</p>
<p><strong>Título:</strong> {{ $data->title }}</p>

<br>

<h4>Cliente</h4>
<p><strong>Nome:</strong> {{ $data->name }}</p>
<p><strong>Email:</strong> {{ $data->email }}</p>

<br>

<h4>Pergunta</h4>
<p><strong>Mensagem:</strong> {{ $data->question }}</p>

@endsection
