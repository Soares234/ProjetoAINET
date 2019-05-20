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
                placeholder="0000" value="{{ old('num_socio', $user->num_socio) }}" readonly />
            @if ($errors->has('num_socio'))
                <em>{{ $errors->first('num_socio') }}</em>
            @endif

            <div class="form-group">
                <label for="inputNomeInformal">Nome Informal</label>
                <input
                    type="text" class="form-control"
                    name="nome_informal" id="inputNomeInformal"
                    placeholder="Nome Informal" value="{{ old('nome_informal', $user->nome_informal) }}" readonly/>
                @if ($errors->has('nome_informal'))
                    <em>{{ $errors->first('nome') }}</em>
                @endif
            </div>
         </div>
        </div>

    {{--Fim de zona de INLININH IMAGEM+TEXTO--}}
        <br><br><br><br><br><br>

    <div class="form-group">
        <label for="inputSexo">Sexo</label>
        <input type="text" name="sexo" class="form-control" id="inputSexo" value="{{old('sexo',$user->sexo)}}" readonly/>
    </div>
        <div class="form-group">
            <label for="inputDataNascimento">Data de Nascimento: </label>
            <input
                type="text" class="form-control"
                name="data_nascimento" id="inputDataNascimento"
                placeholder="01/01/2019" value="{{ old('data_nascimento', $user->data_nascimento) }}" readonly/>
            @if ($errors->has('data_nascimento'))
                <em>{{ $errors->first('data_nascimento') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNIF">NIF:<br></label>
            <input
                type="text" class="form-control"
                name="nif" id="inputNIF"
                placeholder="123456789" value="{{ $user->nif }}" readonly />
            @if ($errors->has('nif'))
                <em>{{ $errors->first('nif') }}</em>
            @endif
        </div>
        <div class="form-group">
            <label for="inputTelefone">Telefone</label>
            <input
                type="text" class="form-control"
                name="telefone" id="inputTelefone"
                value="{{ old('telefone', $user->telefone) }}" readonly/>
            @if ($errors->has('telefone'))
                <em>{{ $errors->first('telefone') }}</em>
            @endif
        </div>
        <div class="form-group">
            <label for="tipo_socio">Tipo Sócio</label>
            <input
                type="text" class="form-control"
                name="caixaTipoSocio" id="tipo_socio"
                 value="@switch ($user->tipo_socio)
                                @case('P'){{'Piloto'}}   @break
                                @case('NP'){{'Não Piloto'}} @break
                                @case('A'){{'Aeromodelista'}} @break
                @endswitch"
                readonly />
            @if ($errors->has('telefone'))
                <em>{{ $errors->first('telefone') }}</em>
            @endif
        </div>


    </form>
@endsection
