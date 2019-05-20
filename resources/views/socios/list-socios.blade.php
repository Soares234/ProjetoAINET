@extends('layouts.app')
@section('content')

@if (count($users))

    <div><a class="btn btn-primary" href="/socios/create">Novo Socio</a></div>


<table class="table table-striped">
    <thead>
        <tr>
            <th>Imagem</th>
            <th>Numero de sócio</th>
            <th>Nome Informal</th>
            <th>Email</th>
            <th>Tipo de Sócio</th>
            <th>Direção</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            @if ($user->quota_paga == 1) {{-- so da para ver socios com a cota paga,sugeito a alterações mais tarde --}}
                <tr>
                    <td width=1% height=1%><img src={{asset('storage/fotos/'.$user->foto_url)}} class="img-thumbnail" alt="ImageNotFound"></td>
                    <td>{{$user->num_socio}}</td>
                    <td>{{$user->nome_informal}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        @switch($user->tipo_socio)
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
                    <td>{{$user->direcao ? "Sim" : "Não"}}</td>
                    <td>
                        <a class="btn btn-xs btn-primary inline" href="/socios/{{$user->id}}/edit">Edit</a>

                        <form action="{{ action('SocioController@destroy', $user->id) }}" method="POST" role="form" class="inline">
                            @csrf
                            @method('delete')

                            <input type="hidden" name="socio_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>

                        </form>
                    </td>
                </tr>
            @endif
        @endforeach
</table>
    {{ $users->links() }}
    {{-- Paginar os Sócios--}}
    @else
        <h2>Não foram encontrados Sócios</h2>
    @endif
@endsection
