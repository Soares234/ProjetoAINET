@extends('layouts.app')
@section('content')

    <form action="{{action('AeronaveController@update',$aeronave->matricula)}}" method="POST" class="form-group">

        @include('aeronaves.add-edit-aeronave')
        @method("PUT")
        @csrf

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Editar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/aeronaves">Cancelar</a>
        </div>
    </form>
@endsection
