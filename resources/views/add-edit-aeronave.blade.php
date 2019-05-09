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
        name="num_lugares" id="inputNumLugares"
        placeholder="0" value="{{ old('num_lugares', $aeronave->num_lugares) }}" />
    @if ($errors->has('num_lugares'))
        <em>{{ $errors->first('num_lugares') }}</em>
    @endif
</div>

<div class="form-group">
    <label for="inputPrecoHora">Contador de Horas</label>
    <input
        type="number" class="form-control"
        name="conta_horas" id="inputPrecoHora"
        placeholder="0" value="{{ old('conta_horas', $aeronave->conta_horas) }}" />
    @if ($errors->has('conta_horas'))
        <em>{{ $errors->first('conta_horas') }}</em>
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
