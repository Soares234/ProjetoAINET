@extends('layouts.app')
@section('content')
@if($user->ativo)
    @can('view',$user)
    <form action="{{action('SocioController@show',$user->id)}}" method="get" class="form-group">
        <div class="float-left">
            <img src={{asset('storage/fotos/'.$user->foto_url)}} class="img-thumbnail" alt="Ups, não encontramos a imagem que estava à procura!">
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
            <input type="text" class="form-control" name="telefone" id="inputTelefone" value="{{ $user->telefone}}" readonly/>

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
        <div class="form-group">
            <label for="inputEndereco">Endereco</label>
            <input type="text" class="form-control" name="endereco" id="inputEndereco" value="{{ $user->endereco }}" readonly/>

        </div>
   @if($user->tipo_socio=='P')
            <div class="form-group">
                <label for="inputNLicenca">Número da Licença</label>
                <input type="text" class="form-control" name="nlicensa" id="inputNLicenca" value="{{ $user->num_licenca }}" readonly />

            </div>
            <div class="form-group">
                <label for="inputTLicensa">Tipo Licença</label>
                <input type="text" class="form-control" name="tlicenca" id="inputTLiceca" value="{{ $user->tipo_licenca }}" readonly />

            </div>
            <div class="form-group custom-control-inline">
                <label for="inputVLicensa" class="custom-control-inline">Validade Licença</label>
                <input type="text" class="form-control" name="vlicenca" id="inputVLicenca" value="{{ $user->validade_licenca }}" readonly />
                @if (file_exists(storage_path('app/docs_piloto/' . 'licenca_'.$user->id.'.pdf')))
                    <a class="form-control custom-control-inline btn btn-outline-primary text-center"
                       name="licenca_pdf" href="{{action('PilotoController@getLicenca',$user->id)}}">Download Licença</a>
                @endif
            </div>
            <div class="form-group">
                <label for="inputNumCert">Numero de certificado</label>
                <input type="text" class="form-control" name="ncert" id="inputNumCert" value="{{ $user->num_certificado }}" readonly />
            </div>
            <div class="form-group">
                <label for="inputClasseCert">Classe certificado</label>
                <input type="text" class="form-control" name="Ccert" id="inputClasseCert" value="{{ $user->classe_certificado }}" readonly />
            </div>
            <div class="form-group custom-control-inline">
                <label for="inputValCert" class="custom-control-inline">Validade Certificado</label>
                <input type="text" class="form-control" name="vCert" id="inputValCert" value="{{ $user->validade_certificado }}" readonly />
                @if (file_exists (storage_path('app/docs_piloto/' . 'certificado_'.$user->id.'.pdf')))
                    <a class="form-control custom-control-inline btn btn-outline-primary text-center"
                    name="certificado_pdf" href="{{action('PilotoController@getCertificado',$user->id)}}">Download Certificado</a>
                @endif
            </div>
{{---------------------------------------------------------------------------------ZONA CHECKBOXES!!!!!!-------------------------------------------------------------------------------------------------------------------------}}
          @if($user->instrutor)
            <div class="custom-control custom-checkbox">
                <input name="instrutor" type="checkbox" class="custom-control-input" id="checkInstrutor"  checked  disabled/>
                <label class="custom-control-label" for="checkInstrutor">É Instrutor</label>
            </div>
            @else
            <div class="custom-control custom-checkbox">
                <input name="direcao" type="checkbox" class="custom-control-input" id="checkAluno" @if($user->aluno) {{'checked'}} @endif disabled/>
                <label class="custom-control-label" for="checkDirecao">É Aluno</label>
            </div>
            @endif
            <div class="custom-control custom-checkbox">
                <input name="licencaConfirmada" type="checkbox" class="custom-control-input" id="licencaConfirmada" @if($user->licenca_confirmada) {{'checked'}} @endif disabled/>
                <label class="custom-control-label" for="licencaConfirmada">Licença Confirmada</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input name="direcao" type="checkbox" class="custom-control-input" id="checkCConfirmado" @if($user->certificado_confirmado) {{'checked'}} @endif disabled/>
                <label class="custom-control-label" for="checkCConfirmado">Certificado Confirmad</label>
            </div>
        @endif



        <div class="custom-control custom-checkbox">
            <input name="quota_paga" type="checkbox" class="custom-control-input" id="CheckQuotas" value="1"
                   @if($user->quota_paga){{ "checked" }} @endif  disabled/>
            <label class="custom-control-label" for="CheckQuotas" >Quotas Pagas</label>
        </div>

        <div class="custom-control custom-checkbox">
            <input name="direcao" type="checkbox" class="custom-control-input" id="checkDirecao" @if($user->direcao) {{'checked'}} @endif disabled/>
            <label class="custom-control-label" for="checkDirecao">É direção</label>
        </div>

        <div class="custom-control custom-checkbox">
            <input name="ativo" type="checkbox" class="custom-control-input" id="checkAtivo" value="1"
            @if($user->ativo) {{ "checked" }} @endif disabled />
            <label class="custom-control-label" for="checkAtivo">Está Ativo</label>
        </div>
        <div class="float-right form-group ">
            <a class="btn btn-success" href="/socios/{{$user->id}}/edit" name="ok">Editar</a>
            <a type="submit" class="btn btn-default" name="cancel" href="/socios">Cancelar</a>
        </div>
        <div class="float-md-left form-group">
            <span>
                <a class="btn btn-outline-secondary " href="/password">Mudar Password</a>
            </span>
        </div>
    </form>
    @endcan
    @endif
@endsection
