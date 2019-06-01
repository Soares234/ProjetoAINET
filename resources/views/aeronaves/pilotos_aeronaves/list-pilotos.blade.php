@extends('layouts.app')
@section('content')

    @if (count($pilotos_autorizados))

        <table class="table table-striped">
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
                            <form action="/aeronaves/{{$piloto->matricula}}/pilotos/{{$piloto->piloto_id}}"
                                  method="POST" role="form" class="inline">
                                @csrf
                                @method('delete')

                                <input type="hidden" name="piloto_id" value="{{$piloto->piloto_id}}">
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
@endsection
