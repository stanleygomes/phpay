@extends('layouts.email')

@section('body')

<h4>Recuperacao de senha</h4>

<p>Alguém, talvez você solicitou a recuperação de senha. Caso tenha sido você, por favor, acesse o link abaixo.</p>

<br>

<a href="{{ route('auth.passwordReset', ['token' => $data->token]) }}">
    <button style="padding:10px 15px;color:#fff;background:#39f;border-radius:2px;border:0">Clique aqui</button>
</a>

<p>Se não, por favor, ignore esta mensagem.</p>

@endsection
