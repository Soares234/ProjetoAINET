
<div class="form-group">
    <label for="name">Nome Completo</label>
    <input
        type="text" class="form-control"
        name="name" id="name"
        placeholder="Nome" value="{{ old('name', $user->name) }}"/>
    @if ($errors->has('name'))
        <em>{{ $errors->first('name') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputNomeInformal">Nome Informal</label>
    <input
        type="text" class="form-control"
        name="nome_informal" id="inputNomeInformal"
        placeholder="Nome Informal" value="{{ old('nome_informal', $user->nome_informal) }}"/>
    @if ($errors->has('nome_informal'))
        <em>{{ $errors->first('nome_informal') }}</em>
    @endif
</div>
<div class="form-group">
    <label for="inputNome">email</label>
    <input
        type="text" class="form-control"
        name="email" id="inputEmail"
        placeholder="xxxx@yy.zz" value="{{ old('email', $user->email) }}"/>
    @if ($errors->has('email'))
        <em>{{ $errors->first('email') }}</em>
    @endif
</div>

<div class="form-group">
    <div><label for="inputSexo">Sexo</label></div>
    <div class="custom-control custom-radio">
        <input
            type="radio" class="custom-control-input"
            name="sexo" id="inputSexoM"
            value="M" {{old('sexo',$user->sexo)=='M' ? 'checked':''}}>
        <label class="custom-control-label" for="inputSexoM">Masculino</label>
    </div>

    <div class="custom-control custom-radio">
        <input
            type="radio" class="custom-control-input"
            name="sexo" id="inputSexoF"
            value="F" {{old('sexo',$user->sexo)=='F' ? 'checked':'' }}>
        <label class="custom-control-label" for="inputSexoF">Feminino</label>
    </div>
    @if ($errors->has('sexo'))
        <em>{{ $errors->first('sexo') }}</em>
    @endif
</div>


<div class="form-group">
    <label for="data_nascimento">Data de Nascimento</label>
    <input
        type="text" class="form-control"
        name="data_nascimento" id="data_nascimento"
        value="{{ old('data_nascimento', $user->data_nascimento)}}"/>
    @if ($errors->has('data_nascimento'))
        <em>{{ $errors->first('data_nascimento') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputNIF">NIF</label>
    <input
        type="number" class="form-control"
        name="nif" id="inputNIF"
        placeholder="123456789" value="{{ old('nif', $user->nif) }}"/>
    @if ($errors->has('nif'))
        <em>{{ $errors->first('nif') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputTelefone">Telefone</label>
    <input
        type="number" class="form-control"
        name="telefone" id="inputTelefone"
        placeholder="123456789" value="{{ old('telefone', $user->telefone) }}"/>
    @if ($errors->has('telefone'))
        <em>{{ $errors->first('telefone') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputTipoSocio">Tipo de Sócio</label><br>
    <select
        class="form-control"
        name="tipo_socio" id="inputTipoSocio">


        <option disabled selected>Selecione um opção</option>
        <option {{ old('tipo_socio', $user->tipo_socio)=='A' ? "selected" : ''}} value="A">Aeromodelista</option>
        <option {{ old('tipo_socio', $user->tipo_socio)=='NP' ? "selected" : ''}} value="NP"> Não Piloto</option>
        <option {{ old('tipo_socio', $user->tipo_socio)=='P' ? "selected" : ''}} value="P"> Piloto</option>

    </select>
    @if ($errors->has('tipo_socio'))
        <em>{{ $errors->first('tipo_socio') }}</em>
    @endif
</div>



@can('administrate',$user)
    <div class="custom-control custom-checkbox">
        <input name="quota_paga" type="checkbox" class="custom-control-input" id="CheckQuotas" value="1"
        @if(old('quota_paga', $user->quota_paga) == 1 ){{ "checked" }}@endif>
        <label class="custom-control-label" for="CheckQuotas">Quotas Pagas</label>
    </div>
    @if ($errors->has('quota_paga'))
        <em>{{ $errors->first('quota_paga') }}</em>
    @endif
    <div class="custom-control custom-checkbox">
        <input name="direcao" type="checkbox" class="custom-control-input" id="checkDirecao" value="1"
        @if(old('direcao', $user->direcao) == 1 ){{ "checked" }}@endif>
        <label class="custom-control-label" for="checkDirecao">É direção</label>
    </div>
    @if ($errors->has('direcao'))
        <em>{{ $errors->first('direcao') }}</em>
    @endif

    @if ($errors->has('ativo'))
        <em>{{ $errors->first('ativo') }}</em>
    @endif
@endcan
<div class="form-group">
    <label for="endereco">Endereço</label>
    <textarea name="endereco" id="endereco"
           class="form-control">{{ old('endereco', $user->endereco) }}</textarea>
</div>
@if ($errors->has('endereco'))
    <em>{{$errors->first('endereco')}}</em>
@endif


