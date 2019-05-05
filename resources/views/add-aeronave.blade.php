@extends('master')
@section('content')

    <form action="/aeronaves/create" method="post" class="form-group">

        @csrf

        <div class="form-group">
            <label for="inputMatricula">Matricula</label>
            <input
                type="text" class="form-control"
                name="name" id="inputMatricula"
                placeholder="Matricula" value="{{ old('matricula', $aeronave->matricula) }}" />
            @if ($errors->has('matricula'))
                <em>{{ $errors->first('matricula') }}</em>
            @endif
        </div>

        @include('add-edit-aeronave')

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/aeronaves">Cancelar</a>
        </div>
    </form>
@endsection