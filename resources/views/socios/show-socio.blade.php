@extends('layouts.app')
@section('content')

    <form action="{{action('SocioController@show',$user->id)}}" method="get" class="form-group">

        <div class="float-left">
            <img src={{asset('storage/fotos/'.$user->foto_url)}} class="img-thumbnail">
        </div>
        <div class="float-left" >
            <div class="form-control-sm">
            <label for="inputNumSocio">Número de Sócio</label>
            <input
                type="text" class="form-control"
                name="num_socio" id="inputNumSocio"
                 value="{{ $user->num_socio }}" readonly />


            <div class="form-group">
                <label for="inputNomeInformal">Nome Informal</label>
                <input
                    type="text" class="form-control"
                    name="nome_informal" id="inputNomeInformal"
                   value="{{ $user->nome_informal }}" readonly/>

            </div>
         </div>
        </div>

    {{--Fim de zona de INLININH IMAGEM+TEXTO--}}
        <br><br><br><br><br><br>

    <div class="form-group">
        <label for="inputSexo">Sexo</label>
        <input type="text" name="sexo" class="form-control" id="inputSexo" value="{{$user->sexo}}" readonly/>
    </div>
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input type="text" name="email" class="form-control" id="inputEmail" value="{{$user->email}}" readonly/>
        </div>
        <div class="form-group">
            <label for="inputDataNascimento">Data de Nascimento </label>
            <input type="text" class="form-control" name="data_nascimento" id="inputDataNascimento" value="{{ $user->data_nascimento }}" readonly/>

        </div>

        <div class="form-group">
            <label for="inputNIF">NIF</label>
            <input type="text" class="form-control" name="nif" id="inputNIF" value="{{ $user->nif }}" readonly />

        </div>
        <div class="form-group">
            <label for="inputTelefone">Telefone</label>
            <input type="text" class="form-control" name="telefone" id="inputTelefone" value="{{ old('telefone', $user->telefone) }}" readonly/>

        </div>
        <div class="form-group">
            <label for="tipo_socio">Tipo Sócio</label>
            <input type="text" class="form-control" name="caixaTipoSocio" id="tipo_socio"
                   value="@switch ($user->tipo_socio)
                                @case('P'){{'Piloto'}}   @break
                                @case('NP'){{'Não Piloto'}} @break
                                @case('A'){{'Aeromodelista'}} @break
                @endswitch"
                readonly />
        </div>


    </form>
@endsection
