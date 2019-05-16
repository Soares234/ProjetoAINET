@extends('layouts.app')
@section('content')

    <form action="/socios" method="POST" class="form-group">

        @csrf

        @include('add-edit-socio')

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/aeronaves">Cancelar</a>
        </div>
    </form>
@endsection
