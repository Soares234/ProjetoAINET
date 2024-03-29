@extends('layouts.app')
@section('content')

@if (count($aeronaves))

    <div><a class="btn btn-primary" href="/aeronaves/create">Nova Aeronave</a></div>


<table class="table table-striped">
    <thead>
        <tr>
            <th>Matricula</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Nº de lugares</th>
            <th>Conta-horas</th>
            <th>Preço/hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($aeronaves as $aeronave)
            @if ($aeronave->deleted_at == null)
                <tr>
                    <td>{{$aeronave->matricula}}</td>
                    <td>{{$aeronave->marca}}</td>
                    <td>{{$aeronave->modelo}}</td>
                    <td>{{$aeronave->num_lugares}}</td>
                    <td>{{$aeronave->conta_horas}}</td>
                    <td>{{$aeronave->preco_hora}}</td>
                    <td>
                        <a class="btn btn-xs btn-secondary inline" href="/aeronaves/{{$aeronave->matricula}}/pilotos">Autorizações de Voo</a>

                        <a class="btn btn-xs btn-primary" href="/aeronaves/{{$aeronave->matricula}}/edit">Edit</a>

                        <form action="{{ action('AeronaveController@destroy', $aeronave->matricula) }}" method="POST" role="form" class="inline">
                            @csrf
                            @method('delete')

                            <input type="hidden" name="aeronave_matricula" value="{{$aeronave->matricula}}">
                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>

                        </form>
                    </td>
                </tr>
            @endif
        @endforeach
</table>
    @else
        <h2>Não foram encontradas Aeronaves</h2>
    @endif
@endsection
