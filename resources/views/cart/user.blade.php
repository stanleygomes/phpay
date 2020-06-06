<div class="col-sm-5">
    <div class="form-group">
        <label for="inputName">Nome*</label>
        <input type="text" id="inputName" name="name" class="form-control" placeholder="Nome" required value="{{ old('name') || $user->name }}">
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label for="inputCpf">CPF</label>
        <input type="text" id="inputCpf" name="cpf" class="form-control mask-cpf" placeholder="CPF" value="{{ old('cpf') || $user->cpf }}">
    </div>
</div>
<div class="col-sm-3">
    <div class="form-group">
        <label for="inputSex">Sexo</label>
        <select name="sex" id="inputSex" required class="form-control">
            <option value="F" {{ (old('profile') === 'F') || ($user->sex === 'F') ? 'selected' : '' }}>
                Feminino
            </option>
            <option value="M" {{ (old('profile') === 'M') || ($user->sex === 'M') ? 'selected' : '' }}>
                Masculino
            </option>
        </select>
    </div>
</div>
<div class="col-sm-6">
    <div class="form-group">
        <label for="inputEmail">Email*</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email" required value="{{ old('email') || $user->email }}">
    </div>
</div>
<div class="col-sm-4">
    <div class="form-group">
        <label for="inputPhone">Celular*</label>
        <input type="text" id="inputPhone" name="phone" class="form-control mask-phone" required placeholder="Celular" value="{{ old('phone') || $user->phone }}">
    </div>
</div>
