@extends('layouts.app')
@section('content')

    <form action="{{ action('SocioController@update', $user->id) }}" method="POST" class="form-group"
          enctype="multipart/form-data">

        @method("PUT")
        @csrf
        @include('socios.add-edit-socio')
    @if($user->tipo_socio=="P")
            <div class="form-group">
                <label for="num_licenca">Número de Licença</label>
                <input
                    type="text" class="form-control"
                    name="num_licenca" id="num_licenca"
                    value="{{ old('num_licenca', $user->num_licenca) }}"/>
                @if ($errors->has('name'))
                    <em>{{ $errors->first('num_licenca') }}</em>
                @endif
            </div>
            <div class="form-group">
                <label for="tipo_licenca">Tipo de Licenca</label><br>
                <select
                    class="form-control"
                    name="tipo_licenca" id="tipo_licenca" >
                    <option disabled selected>Selecione um opção</option>
                        @foreach($tipos_licencas as $tipo_licenca)
                        <option {{old('tipo_licenca',$user->tipo_licenca)==$tipo_licenca->code?"selected":''}}value="{{$tipo_licenca->code}}">{{$tipo_licenca->nome}}</option>
                        @endforeach

                </select>
            </div>

                <div class="form-group">
                <label for="name">Numero Certificado</label>
                <input
                    type="text" class="form-control"
                    name="num_certificado" id="num_certificado"
                     value="{{ old('num_certificado', $user->num_certificado) }}"/>
            </div>
                <div class="form-group">
                    <label for="classe_certificado">Classe Certificado</label><br>
                    <select
                        class="form-control"
                        name="classe_certificado" id="classe_certificado" >
                        <option disabled selected>Selecione um opção</option>
                        @foreach($classes_certificados as $classe_certificado)
                            <option {{old('classe_certificado',$user->classe_certificado)==$classe_certificado->code?"selected":''}}value="{{$classe_certificado->code}}">{{$classe_certificado->nome}}</option>
                        @endforeach
                    </select>
                </div>





    @endif
        <div class="form-group">
            <label for="image">Imagem perfil</label>
            <input type="file" class="form-control" name="file_foto" id="image" accept="image/*"/>
            @if ($errors->has('file_foto'))
                <em>{{ $errors->first('file_foto') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNumSocio">Número de Sócio</label>
            <input
                type="number" class="form-control"
                name="num_socio" id="inputNumSocio"
                placeholder="0000" value="{{ old('num_socio', $user->num_socio) }}"/>
            @if ($errors->has('num_socio'))
                <em>{{ $errors->first('num_socio') }}</em>
            @endif
            <div class="custom-control custom-checkbox">
                <input name="ativo" type="checkbox" class="custom-control-input" id="checkAtivo" value="1"
                @if(old('ativo', $user->ativo) == 1 ){{ "checked" }}@endif>
                <label class="custom-control-label" for="checkAtivo">Está Ativo</label>
            </div>
        </div>
        <div class="form-group custom-control-inline">
            <label for="validade_certificado" class="custom-control-inline">Validade Certificado</label>
            <input type="text" class="form-control" name="validade_certificado" id="validade_certificado" value="{{$user->validade_certificado}}" />
                <a class="form-control custom-control-inline btn btn-outline-primary text-center"
                   name="certificado_pdf" href='/pilotos/{{$user->id}}/certicado'>Download Certificado</a>
        </div>
        <div class="form-group custom-control-inline">
            <label for="validade_licenca" class="custom-control-inline">Validade Licensa</label>
            <input type="text" class="form-control" name="validade_licenca" id="validade_licenca" value="{{ $user->validade_licenca }}" />
            <a class="form-control custom-control-inline btn btn-outline-primary text-center"
               name="licensas_pdf" href='/pilotos/{{$user->id}}/licenca'>Download Licensa</a>

        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Editar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/socios">Cancelar</a>
        </div>

    </form>
@endsection
