@extends('layouts.app')
@section('content')

    @if (count($movimentos))

        <div><a class="btn btn-primary" href="/movimentos/create">Novo Voo</a></div>


        <table class="table table-striped">
            <thead>
            <tr>
                <th>Id</th>
                <th>Aeronave</th>
                <th>Data de partida</th>
                <th>Data de chegada</th>
                <th>Natureza</th>
                <th>Confirmado</th>
                <th>Piloto</th>
                <th>Instrutor</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($movimentos as $movimento)
                <tr>
                    <td>{{$movimento->id}}</td>
                    <td>{{$movimento->aeronave}}</td>
                    <td>{{$movimento->hora_descolagem}}</td>
                    <td>{{$movimento->hora_aterragem}}</td>
                    <td>
                        @switch($movimento->natureza)
                            @case("T")
                            {{"Treino"}}
                            @break

                            @case("I")
                            {{"Instrução"}}
                            @break

                            @case("E")
                            {{"Especial"}}
                        @endswitch
                    </td>
                    <td>{{$movimento->confirmado ? "Sim" : "Não"}}</td>
                    <td>{{$movimento->piloto_id}}</td>
                    <td>{{$movimento->instrutor_id}}</td>
                    {{-- Botoes de Editar/Eliminar --}}
                    <td>
                        <a class="btn btn-xs btn-primary inline" href="/movimentos/{{$movimento->id}}/edit">Edit</a>

                        <form action="{{ action('MovimentoController@destroy', $movimento->id) }}" method="POST" role="form" class="inline">
                            @csrf
                            @method('delete')

                            <input type="hidden" name="movimento_id" value="{{$movimento->id}}">
                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>

                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $movimentos->links() }}
        {{-- Paginar os Movimentos--}}
    @else
        <h2>Não foram encontrados Voos</h2>
    @endif
@endsection
