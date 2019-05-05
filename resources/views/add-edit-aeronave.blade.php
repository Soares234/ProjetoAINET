<div class="form-group">
    <label for="inputMarca">Marca</label>
    <input
        type="text" class="form-control"
        name="marca" id="inputMarca"
        placeholder="Marca" value="{{ old('marca', $aeronave->marca) }}" />
    @if ($errors->has('marca'))
        <em>{{ $errors->first('marca') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputModelo">Modelo</label>
    <input
        type="text" class="form-control"
        name="modelo" id="inputModelo"
        placeholder="Modelo" value="{{ old('modelo', $aeronave->modelo) }}" />
    @if ($errors->has('modelo'))
        <em>{{ $errors->first('modelo') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputNumLugares">Nº de Lugares</label>
    <input
        type="number" class="form-control"
        name="numLugares" id="inputNumLugares"
        placeholder="0" value="{{ old('numLugares', $aeronave->num_lugares) }}" />
    @if ($errors->has('numLugares'))
        <em>{{ $errors->first('numLugares') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputPrecoHora">Preço/hora</label>
    <input
        type="number" class="form-control"
        name="preco_hora" id="inputPrecoHora"
        placeholder="0" value="{{ old('preco_hora', $aeronave->preco_hora) }}" />
    @if ($errors->has('preco_hora'))
        <em>{{ $errors->first('preco_hora') }}</em>
    @endif
</div>



{{--
<div class="form-group">
    <label for="inputType">Type</label>
    <select name="type" id="inputType" class="form-control">
        <option disabled selected> -- select an option -- </option>
        <option {{ $user->type=='0' ? "selected" : "" }} value="0">Administrator</option>
        <option {{ $user->type=='1' ? "selected" : "" }} value="1">Publisher</option>
        <option {{ $user->type=='2' ? "selected" : "" }} value="2">Client</option>
    </select>
    @if ($errors->has('type'))
        <em>{{ $errors->first('type') }}</em>
    @endif
</div>
<div class="form-group">
    <label for="inputEmail">Email</label>
    <input
        type="email" class="form-control"
        name="email" id="inputEmail"
        placeholder="Email address" value="{{ old('email', $user->email) }}"/>
    @if ($errors->has('email'))
        <em>{{ $errors->first('email') }}</em>
    @endif
</div>
--}}
