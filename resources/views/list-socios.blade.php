@extends('layouts.app')
@section('content')

@if (count($socios))

    <div><a class="btn btn-primary" href="/socios/create">Novo Socio</a></div>


<table class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Numero de sócio</th>
            <th>Nome Informal</th>
            <th>Email</th>
            <th>Tipo de Sócio</th>
            <th>Direção</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($socios as $socio)
            @if ($socio->quota_paga == 1) {{-- so da para ver socios com a cota paga,sugeito a alterações mais tarde --}}
                <tr>
                    <td>{{$socio->id}}</td>
                    <td>{{$socio->num_socio}}</td>
                    <td>{{$socio->nome_informal}}</td>
                    <td>{{$socio->email}}</td>
                    <td>
                        @switch($socio->tipo_socio)
                            @case("P")
                            {{"Piloto"}}
                            @break

                            @case("NP")
                            {{"Não Piloto"}}
                            @break

                            @case("A")
                            {{"Aeromodelista"}}
                        @endswitch
                    </td>
                    <td>{{$socio->direcao ? "Sim" : "Não"}}</td>
                    <td>
                        <a class="btn btn-xs btn-primary inline" href="/socios/{{$socio->id}}/edit">Edit</a>

                        <form action="{{ action('SocioController@destroy', $socio->id) }}" method="POST" role="form" class="inline">
                            @csrf
                            @method('delete')

                            <input type="hidden" name="socio_id" value="{{$socio->id}}">
                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>

                        </form>
                    </td>
                </tr>
            @endif
        @endforeach
</table>
    @else
        <h2>Não foram encontrados Sócios</h2>
    @endif
@endsection
