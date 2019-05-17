@extends('layouts.app')
@section('content')

    <form action="/socios" method="POST" class="form-group">
        <div class="form-group">
            <label for="inputNumSocio">Número de Sócio</label>
            <input
                type="number" class="form-control"
                name="num_socio" id="inputNumSocio"
                placeholder="0000" value="{{ old('num_socio', $socio->num_socio) }}" />
            @if ($errors->has('num_socio'))
                <em>{{ $errors->first('num_socio') }}</em>
            @endif
        </div>
        @csrf

        @include('add-edit-socio')

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/socios">Cancelar</a>
        </div>
    </form>
@endsection
