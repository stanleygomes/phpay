@extends('layouts.email')

@section('title', 'Contato')

@section('body')

<h3>Sua mensagem foi respondida.</h3>

<br /><br />

<b>Seu contato</b> <br />

<p><strong>Nome:</strong> {{ $data->name }}</p>
<p><strong>Email:</strong> {{ $data->email }}</p>
<p><strong>Telefone:</strong> {{ $data->phone }}</p>
<p><strong>Mensagem:</strong> {{ $data->message }}</p>

<br />
<br />

<b>Resposta</b> <br />

<p><strong>Mensagem:</strong> {{ $data->reply }}</p>

@endsection
