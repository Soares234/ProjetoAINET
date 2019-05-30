@extends('layouts.app')
@section('content')

@if (count($users))
    {{--    /*Os parâmetros possíveis são: num_socio,
nome_informal, email, tipo, direcao, quotas_pagas, ativo.*/--}}
    <form action="/socios" method="GET" class="form-group">
        <div class="form-group">
            <label for="name">Filtrar por Nome Informal</label>
            <input
                type="text" class="form-control"
                name="name" id="name"/>
        </div>
        <div class="form-group">
            <label for="name">Filtrar por Nome Informal</label>
            <input
                type="text" class="form-control"
                name="name" id="name"/>
        </div>
{{------------------------------------------------------Zona de checkboxes+Selects--------------------------------------------------------------------------------}}
        <div class="form-group">
            <label for="inputDirecao">Direção</label><br>
            <select
                class="form-control"
                name="direcao" id="inputDirecao">
                <option disabled selected>Selecione um opção</option>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>

        @can('administrate',Auth::user())
            <input type="hidden" name="ativo" value="1">
            <div class="custom-control custom-checkbox">
                <input name="ativo" type="checkbox" class="custom-control-input" id="ativo" value="0"/>
                <label class="custom-control-label" for="ativo">Ver Utilizadores Desativados</label>
            </div>
            <input type="hidden" name="quota_paga" value="1">
            <div class="custom-control custom-checkbox">
                <input name="quota_paga" type="checkbox" class="custom-control-input" id="quota_paga" value="0"/>
                <label class="custom-control-label" for="quota_paga">Ver Sócios Com Contas Por Pagar</label>
            </div>
        @endcan

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/socios">Cancelar</a>
        </div>
    </form>

    @can('administrate',\Illuminate\Support\Facades\Auth::user())
        <a class="btn btn-primary inline" href="/socios/create">Novo Socio</a>
    @endcan
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Fotografia</th>
            <th>Numero de sócio</th>
            <th>Nome Informal</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Tipo de Sócio</th>
            <th>Numero de Licença</th>



        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)

                <tr>
                    <td width=1% height=1%><img src={{asset('storage/fotos/'.$user->foto_url)}} class="img-thumbnail" alt="ImageNotFound" width=60% height=60%></td>
                    <td>{{$user->num_socio}}</td>
                    <td>{{$user->nome_informal}}</td>
                    <td>{{$user->telefone}}</td>
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
                    <td> {{$user->num_licenca}} </td>

                    @can('administrate',\Illuminate\Support\Facades\Auth::user())
                    <td>
                        <a class="btn btn-xs btn-secondary inline" href="/socios/{{$user->id}}">Perfil</a>

                        <a class="btn btn-xs btn-primary inline" href="/socios/{{$user->id}}/edit">Edit</a>

                        <form action="{{ action('SocioController@destroy', $user->id) }}" method="POST" role="form" class="inline">
                            @csrf
                            @method('delete')
                            <input type="hidden" name="socio_id" value="{{$user->id}}">
                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                    @endcan
                </tr>

        @endforeach
</table>
    {{$users->links()}}
    {{-- Paginar os Sócios--}}
    @else
        <h2>Não foram encontrados Sócios</h2>
    @endif
@endsection
