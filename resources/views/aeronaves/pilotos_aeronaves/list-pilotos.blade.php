@extends('layouts.app')
@section('content')

    @if (count($pilotos_autorizados))

        <label class="display-4" for="tableAutorizados">Lista de pilotos autorizados a voar</label>
        <table class="table table-striped" id="tableAutorizados">
            <thead>
            <tr>
                <th>Matricula</th>
                <th>Piloto ID</th>
                <th>Nome informal do Piloto</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($pilotos_autorizados as $piloto)

                @if ($piloto->deleted_at == null)
                    <tr>
                        <td>{{$piloto->matricula}}</td>
                        <td>{{$piloto->piloto_id}}</td>
                        <td>{{$piloto->nome_informal}}</td>
                        <td>
                            <form action="/aeronaves/{{$matricula}}/pilotos/{{$piloto->piloto_id}}"
                                  method="POST" role="form" class="inline">
                                @csrf
                                @method('delete')


                                <input type="hidden" name="id" value="{{$piloto->id}}">
                                <input type="hidden" name="piloto_id" value="{{$piloto->piloto_id}}">
                                <input type="hidden" name="matricula" value="{{$piloto->matricula}}">
                                <button type="submit" class="btn btn-xs btn-danger">Remover Autorização</button>

                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    @else
        <h2>Não foram encontrados pilotos autorizados a voar esta aeronave</h2>
    @endif


    @if (count($pilotos_nao_autorizados))

        <label class="display-4" for="tableNaoAutorizados">Lista de pilotos não autorizados a voar</label>
        <table class="table table-striped" id="tableNaoAutorizados">
            <thead>
            <tr>
                <th>Piloto ID</th>
                <th>Nome informal do Piloto</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($pilotos_nao_autorizados as $piloto)

                @if ($piloto->deleted_at == null)
                    <tr>
                        <td>{{$piloto->id}}</td>
                        <td>{{$piloto->nome_informal}}</td>
                        <td>
                            <form action="/aeronaves/{{$matricula}}/pilotos/{{$piloto->id}}"
                                  method="POST" role="form" class="inline">
                                @csrf
                                @method('post')

                                <input type="hidden" name="piloto_id" value="{{$piloto->id}}">
                                <input type="hidden" name="matricula" value="{{$matricula}}">
                                <button type="submit" class="btn btn-xs btn-success">Adicionar Autorização</button>

                            </form>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    @else
        <h2>Não foram encontrados pilotos não autorizados a voar esta aeronave</h2>
    @endif
@endsection
