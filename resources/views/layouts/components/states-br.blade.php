<select id="{{ isset($stateId) ? $stateId : 'inputState' }}" name="{{ isset($stateName) ? $stateName : 'state' }}" {{ isset($stateRequired) ? 'required' : '' }} class="form-control">
    <option value="" {{ $stateValue == '' ? 'selected' : ''}}>Selecionar estado</option>
    <option value="AC" {{ $stateValue == 'AC' ? 'selected' : ''}}>Acre</option>
    <option value="AL" {{ $stateValue == 'AL' ? 'selected' : ''}}>Alagoas</option>
    <option value="AP" {{ $stateValue == 'AP' ? 'selected' : ''}}>Amapá</option>
    <option value="AM" {{ $stateValue == 'AM' ? 'selected' : ''}}>Amazonas</option>
    <option value="BA" {{ $stateValue == 'BA' ? 'selected' : ''}}>Bahia</option>
    <option value="CE" {{ $stateValue == 'CE' ? 'selected' : ''}}>Ceará</option>
    <option value="DF" {{ $stateValue == 'DF' ? 'selected' : ''}}>Distrito Federal</option>
    <option value="GO" {{ $stateValue == 'GO' ? 'selected' : ''}}>Goiás</option>
    <option value="ES" {{ $stateValue == 'ES' ? 'selected' : ''}}>Espírito Santo</option>
    <option value="MA" {{ $stateValue == 'MA' ? 'selected' : ''}}>Maranhão</option>
    <option value="MT" {{ $stateValue == 'MT' ? 'selected' : ''}}>Mato Grosso</option>
    <option value="MS" {{ $stateValue == 'MS' ? 'selected' : ''}}>Mato Grosso do Sul</option>
    <option value="MG" {{ $stateValue == 'MG' ? 'selected' : ''}}>Minas Gerais</option>
    <option value="PA" {{ $stateValue == 'PA' ? 'selected' : ''}}>Pará</option>
    <option value="PB" {{ $stateValue == 'PB' ? 'selected' : ''}}>Paraiba</option>
    <option value="PR" {{ $stateValue == 'PR' ? 'selected' : ''}}>Paraná</option>
    <option value="PE" {{ $stateValue == 'PE' ? 'selected' : ''}}>Pernambuco</option>
    <option value="PI" {{ $stateValue == 'PI' ? 'selected' : ''}}>Piauí</option>
    <option value="RJ" {{ $stateValue == 'RJ' ? 'selected' : ''}}>Rio de Janeiro</option>
    <option value="RN" {{ $stateValue == 'RN' ? 'selected' : ''}}>Rio Grande do Norte</option>
    <option value="RS" {{ $stateValue == 'RS' ? 'selected' : ''}}>Rio Grande do Sul</option>
    <option value="RO" {{ $stateValue == 'RO' ? 'selected' : ''}}>Rondônia</option>
    <option value="RR" {{ $stateValue == 'RR' ? 'selected' : ''}}>Roraima</option>
    <option value="SP" {{ $stateValue == 'SP' ? 'selected' : ''}}>São Paulo</option>
    <option value="SC" {{ $stateValue == 'SC' ? 'selected' : ''}}>Santa Catarina</option>
    <option value="SE" {{ $stateValue == 'SE' ? 'selected' : ''}}>Sergipe</option>
    <option value="TO" {{ $stateValue == 'TO' ? 'selected' : ''}}>Tocantins</option>
</select>
