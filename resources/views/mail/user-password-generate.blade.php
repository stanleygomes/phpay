@extends('layouts.email')

@section('body')

<h4>SUA CONTA</h4>

<p>Olá <strong>{{ $user->name }}</strong>, sua senha foi redefinida.
A senha de acesso foi gerada pelo sistema e segue abaixo.</p>

<p>Email: <strong>{{ $user->email }}</strong></p>
<p>Senha: <strong>{{ $password }}</strong></p>

<p>Para acessar o sistema, clique no link abaixo.</p>

<br>

<a href="{{ route('auth.index') }}">
	<button style="padding:10px 15px;color:#fff;background:#39f;border-radius:2px;border:0">CLIQUE AQUI</button>
</a>

<br><br>

<p>Será necessário utilizar um email (este no qual você recebeu a notificação) e uma senha que você deve ter recebido anteriormente. Caso tenha problemas com a senha, clique <a href="{{ route('password.request') }}">aqui</a>.</p>

@endsection
