@extends('layouts.app')
@section('content')

    <form action="{{ action('AeronaveController@update', $aeronave->matricula) }}" method="POST" class="form-group">

        @method('PUT')
        @csrf

        @include('aeronaves.add-edit-aeronave')

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Editar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/aeronaves">Cancelar</a>
        </div>
    </form>
@endsection
