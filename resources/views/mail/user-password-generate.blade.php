@extends('layouts.email')

@section('body')

<h4>Sua conta</h4>

<p>Olá <strong>{{ $data->name }}</strong>, segue abaixo seus dados de acesso a conta.</p>

<p>Email: <strong>{{ $data->email }}</strong></p>
<p>Senha: <strong>{{ $data->password_plain }}</strong></p>

<p>Para acessar o sistema, clique no link abaixo.</p>

<br>

<a href="{{ route('auth.login') }}">
	<button style="padding:10px 15px;color:#fff;background:#39f;border-radius:2px;border:0">CLIQUE AQUI</button>
</a>

@endsection
