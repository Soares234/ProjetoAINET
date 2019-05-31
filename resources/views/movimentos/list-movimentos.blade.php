@extends('layouts.app')
@section('content')

    @if (count($movimentos))



        <table class="table table-striped" style="height: 20px; position:relative">
            <thead>
            <tr>
                <th>Id</th>
                <th>Aeronave</th>
                <th>Data</th>
                <th>Data de partida</th>
                <th>Data de chegada</th>
                <th>Tempo de Voo (minutos)</th>
                <th>Natureza</th>
                <th>Piloto</th>
                <th>Aerodromo de partida</th>
                <th>Aerodromo de chegada</th>
                <th>Nº de aterragens</th>
                <th>Nº de descolagens</th>
                <th>Nº de diário</th>
                <th>Nº de Serviço</th>
                <th>Conta-horas inicial</th>
                <th>Conta-horas final</th>
                <th>Nº de passageiros</th>
                <th>Tipo de Instrução</th>
                <th>Instrutor</th>
                <th>Confirmado</th>
                <th>Observações</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($movimentos as $movimento)
                <tr>
                    <td>{{$movimento->id}}</td>
                    <td>{{$movimento->aeronave}}</td>
                    <td>{{$movimento->data}}</td>
                    <td>{{$movimento->hora_descolagem}}</td>
                    <td>{{$movimento->hora_aterragem}}</td>
                    <td>{{$movimento->tempo_voo}}</td>
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
                    <td>{{$movimento->piloto_nome_informal}}</td>
                    <td>{{$movimento->aerodromo_partida}}</td>
                    <td>{{$movimento->aerodromo_chegada}}</td>
                    <td>{{$movimento->num_aterragens}}</td>
                    <td>{{$movimento->num_descolagens}}</td>
                    <td>{{$movimento->num_diario}}</td>
                    <td>{{$movimento->num_servico}}</td>
                    <td>{{$movimento->conta_horas_inicio}}</td>
                    <td>{{$movimento->conta_horas_fim}}</td>
                    <td>{{$movimento->num_pessoas}}</td>
                    <td>
                        @switch($movimento->tipo_instrucao)
                            @case("S")
                            {{"Solo"}}
                            @break

                            @case("D")
                            {{"Duplo Comando"}}
                            @break

                            @default
                            {{"---"}}
                        @endswitch
                    </td>
                    <td>{{$movimento->instrutor_nome_informal}}</td>
                    <td>{{$movimento->confirmado ? "Sim" : "Não"}}</td>
                    <td class="inline"
                        style="height: 30px; overflow: hidden; text-overflow: inherit; white-space: nowrap;">{{$movimento->observacoes}}</td>
                    {{-- Botoes de Editar/Eliminar --}}
                    @if(!$movimento->confirmado)
                        <td>
                            <a class="btn btn-xs btn-primary inline" href="/movimentos/{{$movimento->id}}/edit">Edit</a>

                            <form action="{{ action('MovimentoController@destroy', $movimento->id) }}" method="POST"
                                  role="form" class="inline">
                                @csrf
                                @method('delete')

                                <input type="hidden" name="movimento_id" value="{{$movimento->id}}">
                                <button type="submit" class="btn btn-xs btn-danger">Delete</button>

                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
        {{ $movimentos->links() }}
        {{-- Paginar os Movimentos--}}
    @else
        <h2>Não foram encontrados Voos</h2>
    @endif
@endsection
