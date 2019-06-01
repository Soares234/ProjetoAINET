@extends('layouts.app')
@section('content')

    <form action="/aeronaves" method="POST" class="form-group">


        @dd($errors)
        @csrf

        <div class="form-group">
            <label for="inputMatricula">Matricula</label>
            <input
                type="text" class="form-control"
                name="matricula" id="inputMatricula"
                placeholder="Matricula" value="{{ old('matricula') }}" />
            @if ($errors->has('matricula'))
                <em>{{ $errors->first('matricula') }}</em>
            @endif
        </div>

        @include('aeronaves.add-edit-aeronave')

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/aeronaves">Cancelar</a>
        </div>
    </form>
@endsection
