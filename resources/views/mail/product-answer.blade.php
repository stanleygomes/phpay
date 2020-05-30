@extends('layouts.email')

@section('title', 'Contato')

@section('body')

<h3>Resposta sobre sua pergunta de produto no site</h3>

<h4>Produto</h4>
<p><strong>Código:</strong> {{ $data->code }}</p>
<p><strong>Título:</strong> {{ $data->title }}</p>

<br>

<h4>Pergunta</h4>
<p><strong>Mensagem:</strong> {{ $data->question }}</p>

<br>

<h4>Resposta</h4>
<p><strong>Mensagem:</strong> {{ $data->answer }}</p>

@endsection
