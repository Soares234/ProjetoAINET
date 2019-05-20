@extends('layouts.app')
@section('content')

    <form action="/socios" method="POST" class="form-group">

        @csrf

        @include('socios.add-edit-socio')
        <div class="form-group">
            <label for="inputEndereco">Endereço</label>
            <input type="text" class="form-control" name="endereco" id="inputMorada" value="{{ old('endereco', $user->endereco) }}" />
        </div>


        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/socios">Cancelar</a>
        </div>
    </form>
@endsection
