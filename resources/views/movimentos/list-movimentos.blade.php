@extends('layouts.app')
@section('content')

    @if (count($movimentos))
{{--  //id, aeronave, data_inf, data_sup, natureza, confirmado,
        //piloto, instrutor --}}
        <form action="/movimentos" method="GET" class="form-group">
            <div class="form-group">
                <label for="id">Filtrar por ID viagem</label>
                <input
                    type="number" class="form-control"
                    name="id" id="id"/>
            </div>
            <div class="form-group">
                <label for="aeronave">Filtrar por Aeronave</label>
                <input
                    type="text" class="form-control"
                    name="aeronave" id="aeronave"/>
            </div>

            <div class="form-group ">
                <label for="data_inf">Voos realizados após</label>
                <input
                    type="text" class="form-control"
                    name="data_inf" id="data_inf"/>
            </div>
            <div>
                <label for="data_sup">Voos realizados antes de</label>
            <input
                type="text" class="form-control "
                name="data_sup" id="data_sup"/>
            </div>
            <div class="form-group">
                <label for="natureza">Natureza</label><br>
                <select
                    class="form-control"
                    name="natureza" id="natureza">
                    <option disabled selected>Selecione um opção</option>
                    <option value="T">Treino</option>
                    <option value="I">Instrução</option>
                    <option value="E">Especial</option>
                </select>
            </div>
            <div class="form-group">
                <label for="confirmado">Confirmado</label><br>
                <select
                    class="form-control"
                    name="confirmado" id="confirmado">
                    <option disabled selected>Selecione um opção</option>
                    <option value="T">Sim</option>
                    <option value="I">Não</option>
                </select>
            </div>
            <div class="form-group ">
                <label for="piloto">Nome piloto</label>
                <input
                    type="text" class="form-control"
                    name="piloto" id="piloto"/>
            </div>
            <div class="form-group ">
                <label for="data_inf">Nome instrutor</label>
                <input
                    type="text" class="form-control"
                    name="instrutor" id="instrutor"/>
            </div>
            @if(Auth::user()->tipo_socio=="")


            @endif
            <div class="form-group">
                <button type="submit" class="btn btn-success" name="ok">Filtrar</button>
                <a type="submit" class="btn btn-default" name="cancel" href="/movimentos">Cancelar</a>
            </div>

        </form>

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
                    <td>{{date('d/m/Y',strtotime($movimento->data))}}</td>
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
                    <td class="inline" style="height: 30px; overflow: hidden; text-overflow: inherit; white-space: nowrap;">{{$movimento->observacoes}}</td>
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

    @else
        <h2>Não foram encontrados Voos</h2>
    @endif
@endsection
