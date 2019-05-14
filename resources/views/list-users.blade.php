@extends('layouts.app')
@section('content')

@if (count($socios))

    <div><a class="btn btn-primary" href="/socios/create">Novo Socio</a></div>


<table class="table table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Sexo</th>
            <th>Data de Nascimento</th>
            <th>Numero de sócio</th>
            <th>NIF</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($socios as $socio)
            @if ($socio->quota_paga == 1) //so da para ver socios com a cota paga,sugeito a alterações mais tarde//
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->sexo}}</td>
                    <td>{{$user-data_nascimento}}</td>
                    <td>{{$user->num_socio}}</td>
                    <td>{{$user->nif}}</td>
                    <td>
                        <a class="btn btn-xs btn-primary" href="/User/{{$socio->id}}/edit">Edit</a>

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
