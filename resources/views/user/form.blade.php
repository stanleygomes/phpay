@extends('layouts.app')
@section('pageTitle', ($modeEdit === true ? 'Editar' : 'Cadastrar'))
@section(($modeAccount === true ? 'sidebarMenuUserAccountActive' : 'sidebarMenuUserActive'), 'active')

@section('content')

<div class="row">
    @if($modeAccount === false)
    <div class="col-sm-12">
        <a href="{{ route('app.user.index') }}">
            <i class="fa fa-arrow-left"></i>
            Voltar
        </a>
    </div>
    @endif
    <div class="col-sm-12 mt-3">
        <h1>{{ $modeEdit === true ? 'Editar' : 'Cadastrar' }} usuário</h1>
    </div>
</div>

<form class="search-form formulary mt-3" method="post" action="{{ $modeAccount === true ? route('app.user.accountUpdatePost') : ($modeEdit === true ? route('app.user.update', [ 'id' => $user->id ]) : route('app.user.store')) }}">
    {!! csrf_field() !!}

    @include('layouts.alert-messages')

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputName">Nome*</label>
                <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required {{ $modeEdit === true ? '' : 'autofocus' }} value="{{ $modeEdit === true ? $user->name : old('name') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputEmail">Email*</label>
                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ $modeEdit === true ? $user->email : old('email') }}">
            </div>
        </div>
        @if($modeAccount === false)
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputProfile">Perfil*</label>
                <select name="profile" id="inputProfile" required class="form-control">
                    @foreach($profiles as $key => $profile)
                    <option value="{{ $profile }}" {{ $modeEdit === true ? ($user->profile === $profile ? 'selected' : '') : (old('profile') === $profile ? 'selected' : '') }}>
                        {{ $profile }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <input type="hidden" name="cpf" value="{{ $modeEdit === true ? $user->cpf : old('cpf') }}">
        <input type="hidden" name="born_at" value="{{ $modeEdit === true ? ($user->born_at ? $user->born_at->format('d/m/Y') : '') : old('born_at') }}">
        <input type="hidden" name="phone" value="{{ $modeEdit === true ? $user->phone : old('phone') }}">
        <input type="hidden" name="sex" value="{{ $modeEdit === true ? $user->sex : old('sex') }}">
        @else
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputPhone">Celular*</label>
                <input type="text" id="inputPhone" name="phone" class="form-control mask-phone" required placeholder="Celular" value="{{ $modeEdit === true ? $user->phone : old('phone') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputCpf">CPF</label>
                <input type="text" id="inputCpf" name="cpf" class="form-control mask-cpf" placeholder="CPF" value="{{ $modeEdit === true ? $user->cpf : old('cpf') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputBornAt">Data de nascimento</label>
                <input type="text" id="inputBornAt" name="born_at" class="form-control mask-date" placeholder="dd/mm/aaaa" value="{{ $modeEdit === true ? ($user->born_at ? $user->born_at->format('d/m/Y') : '') : old('born_at') }}">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="inputSex">Sexo</label>
                <select name="sex" id="inputSex" required class="form-control">
                    <option value="F" {{ $modeEdit === true ? ($user->sex === 'F' ? 'selected' : '') : (old('profile') === 'F' ? 'selected' : '') }}>
                        Feminino
                    </option>
                    <option value="M" {{ $modeEdit === true ? ($user->sex === 'M' ? 'selected' : '') : (old('profile') === 'M' ? 'selected' : '') }}>
                        Masculino
                    </option>
                </select>
            </div>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-sm-3">
            <button type="submit" class="btn btn-lg btn-primary btn-block" data-message="Salvando...">Salvar</button>
        </div>
    </div>
</form>

@endsection
