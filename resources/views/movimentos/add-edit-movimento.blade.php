@extends('layouts.app')
@section('content')
	<form action="/movimentos" method="POST" class="form-group">
        @csrf

        <div class="form-group">
            <label for="inputDataVoo">Data do Voo</label>
            <input
            type="date" class="form-control"
        	name="data" id="inputData"
        	placeholder="01/01/2019" value="{{ old('data', $movimento->data) }}"
        	/>
    		@if ($errors->has('data'))
        		<em>{{ $errors->first('data') }}</em>
   			@endif            
        </div>

		<div class="form-group">
            <label for="inputHoraDescolagem">Hora de Descolagem</label>
            <input
            type="time" class="form-control"
        	name="horaDescolagem" id="inputHoraDescolagem"
        	placeholder="00:00" value="{{ old('horaDescolagem', $movimento->hora_descolagem) }}"
        	/>
    		@if ($errors->has('horaDescolagem'))
        		<em>{{ $errors->first('horaDescolagem') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputHoraAterragem">Hora de Aterragem</label>
            <input
            type="time" class="form-control"
        	name="horaAterragem" id="inputHoraAterragem"
        	placeholder="00:00" value="{{ old('horaAterragem', $movimento->hora_aterragem) }}"
        	/>
    		@if ($errors->has('horaAterragem'))
        		<em>{{ $errors->first('horaAterragem') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputAeronave">Aeronave</label>
            <input
            type="text" class="form-control"
        	name="aeronave" id="inputAeronave"
        	placeholder="X-XXXX" value="{{ old('aeronave', $movimento->aeronave) }}"
        	/>
    		@if ($errors->has('aeronave'))
        		<em>{{ $errors->first('aeronave') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputNumServico">Numero do Serviço</label>
            <input
            type="number" class="form-control"
        	name="numServico" id="inputNumServico"
        	placeholder="00001" value="{{ old('numServico', $movimento->num_servico) }}"
        	/>
    		@if ($errors->has('numServico'))
        		<em>{{ $errors->first('numServico') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputPiloto">Piloto</label>
            <input
            type="number" class="form-control"
        	name="piloto" id="inputPiloto"
        	placeholder="100001" value="{{ old('piloto', $movimento->piloto_id) }}"
        	/>
    		@if ($errors->has('piloto'))
        		<em>{{ $errors->first('piloto') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputNumLicencaPiloto">Numero da Licença do Piloto</label>
            <input
            type="text" class="form-control"
        	name="numLicencaPiloto" id="inputNumLicencaPiloto"
        	placeholder="10001" value="{{ old('numLicencaPiloto', $movimento->num_licenca_piloto) }}"
        	/>
    		@if ($errors->has('numLicencaPiloto'))
        		<em>{{ $errors->first('numLicencaPiloto') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputTipoLicencaPiloto">Tipo da Licença do Piloto</label>
            <select
            class="form-control"
        	name="tipoLicencaPiloto" id="inputTipoLicencaPiloto">

        	<option disabled selected>Selecione um opção</option>

            @foreach($licencas as $licenca)
                <option {{ old('tipoLicencaPiloto', $movimento->tipo_licenca_piloto)==$licenca->code ? "selected" : ''}}
                        value={{$licenca->code}} >{{$licenca->nome}}</option>
            @endforeach

        	</select>
    		@if ($errors->has('tipoLicencaPiloto'))
        		<em>{{ $errors->first('tipoLicencaPiloto') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputValidadeLicencaPiloto">Validade da Licença do Piloto</label>
            <input
            type="date" class="form-control"
        	name="validadeLicencaPiloto" id="inputValidadeLicencaPiloto"
        	placeholder="01/01/2019" value="{{ old('validadeLicencaPiloto', $movimento->validade_licenca_piloto) }}"
        	/>
    		@if ($errors->has('validadeLicencaPiloto'))
        		<em>{{ $errors->first('validadeLicencaPiloto') }}</em>
   			@endif            
        </div>



         <div class="form-group">
            <label for="inputNumCertificadoPiloto">Numero do Certificado do Piloto</label>
            <input
            type="text" class="form-control"
        	name="numCertificadoPiloto" id="inputNumCertificadoPiloto"
        	placeholder="10001" value="{{ old('numCertificadoPiloto', $movimento->num_certificado_piloto) }}"
        	/>
    		@if ($errors->has('numCertificadoPiloto'))
        		<em>{{ $errors->first('numCertificadoPiloto') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputClasseCertificadoPiloto">Classe do Certificado do Piloto</label>
            <select
            class="form-control"
        	name="classeCertificadoPiloto" id="inputClasseCertificadoPiloto">

        	<option disabled selected>Selecione um opção</option>
        	<option {{ old('classeCertificadoPiloto', $movimento->classe_certificado_piloto)=='Class 1' ? "selected" : ''}} value="Class 1">Class 1 Medical Certificate</option>
        	<option {{ old('classeCertificadoPiloto', $movimento->classe_certificado_piloto)=='Class 2' ? "selected" : ''}} value="Class 2">Class 2 Medical Certificate</option>
        	</select>
    		@if ($errors->has('classeCertificadoPiloto'))
        		<em>{{ $errors->first('classeCertificadoPiloto') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputValidadeCertificadoPiloto">Validade do Certificado do Piloto</label>
            <input
            type="date" class="form-control"
        	name="validadeCertificadoPiloto" id="inputValidadeCertificadoPiloto"
        	placeholder="01/01/2019" value="{{ old('validadeCertificadoPiloto', $movimento->validade_certificado_piloto) }}"
        	/>
    		@if ($errors->has('validadeCertificadoPiloto'))
        		<em>{{ $errors->first('validadeCertificadoPiloto') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputNaturezaVoo">Natureza do Voo</label>
            <select
            class="form-control"
        	name="naturezaVoo" id="inputNaturezaVoo">

        	<option disabled selected>Selecione um opção</option>
        	<option {{ old('naturezaVoo', $movimento->natureza)=='T' ? "selected" : ''}} value="T">Treino</option>
        	<option {{ old('naturezaVoo', $movimento->natureza)=='I' ? "selected" : ''}} value="I">Instrução</option>
        	<option {{ old('naturezaVoo', $movimento->natureza)=='E' ? "selected" : ''}} value="E">Especial</option>

        	</select>
    		@if ($errors->has('naturezaVoo'))
        		<em>{{ $errors->first('naturezaVoo') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputAerodromoPartida">Código do Aerodromo de Partida</label>
            <input
            type="text" class="form-control"
        	name="aerodromoPartida" id="inputAerodromoPartida"
        	placeholder="XXXXXX" value="{{ old('aerodromoPartida', $movimento->aerodromo_partida) }}"
        	/>
    		@if ($errors->has('aerodromoPartida'))
        		<em>{{ $errors->first('aerodromoPartida') }}</em>
   			@endif            
        </div>

		<div class="form-group">
            <label for="inputAerodromoChegada">Código do Aerodromo de Chegada</label>
            <input
            type="text" class="form-control"
        	name="aerodromoChegada" id="inputAerodromoChegadaa"
        	placeholder="XXXXXX" value="{{ old('aerodromoChegada', $movimento->aerodromo_chegada) }}"
        	/>
    		@if ($errors->has('aerodromoChegada'))
        		<em>{{ $errors->first('aerodromoChegada') }}</em>
   			@endif            
        </div>

		<div class="form-group">
            <label for="inputNumDescolagens">Número de Descolagens</label>
            <input
            type="number" class="form-control"
        	name="numDescolagens" id="inputNumDescolagens"
        	placeholder="1" value="{{ old('numDescolagens', $movimento->num_descolagens) }}"
        	/>
    		@if ($errors->has('numDescolagens'))
        		<em>{{ $errors->first('numDescolagens') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputNumAterragens">Número de Aterragens</label>
            <input
            type="number" class="form-control"
        	name="numAterragens" id="inputNumAterragens"
        	placeholder="1" value="{{ old('numAterragens', $movimento->num_aterragens) }}"
        	/>
    		@if ($errors->has('numAterragens'))
        		<em>{{ $errors->first('numAterragenss') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputNumPessoas">Número de Passageiros</label>
            <input
            type="number" class="form-control"
        	name="numPessoas" id="inputNumPessoas"
        	placeholder="1" value="{{ old('numPessoas', $movimento->num_pessoas) }}"
        	/>
    		@if ($errors->has('numPessoas'))
        		<em>{{ $errors->first('numPessoas') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputContaHorasInicio">Conta-Horas Inicial</label>
            <input
            type="number" class="form-control"
        	name="contaHorasInicio" id="inputContaHorasInicio"
        	placeholder="99999" value="{{ old('contaHorasInicio', $movimento->conta_horas_inicio) }}"
        	/>
    		@if ($errors->has('contaHorasInicio'))
        		<em>{{ $errors->first('contaHorasInicio') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputContaHorasFim">Conta-Horas Final</label>
            <input
            type="number" class="form-control"
        	name="contaHorasFim" id="inputContaHorasFim"
        	placeholder="99999" value="{{ old('contaHorasFim', $movimento->conta_horas_fim) }}"
        	/>
    		@if ($errors->has('contaHorasFim'))
        		<em>{{ $errors->first('contaHorasFim') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputModoPagamento">Modo de Pagamento</label>
            <select
            class="form-control"
        	name="modoPagamento" id="inputModoPagamento">

        	<option disabled selected>Selecione um opção</option>
        	<option {{ old('naturezaVoo', $movimento->modo_pagamento)=='N' ? "selected" : ''}} value="N">Numerário</option>
        	<option {{ old('naturezaVoo', $movimento->modo_pagamento)=='M' ? "selected" : ''}} value="M">Multibanco</option>
        	<option {{ old('naturezaVoo', $movimento->modo_pagamento)=='T' ? "selected" : ''}} value="T">Transferência</option>
        	<option {{ old('naturezaVoo', $movimento->modo_pagamento)=='P' ? "selected" : ''}} value="P">Pacote de Horas</option>

        	</select>
    		@if ($errors->has('modoPagamento'))
        		<em>{{ $errors->first('modoPagamento') }}</em>
   			@endif            
        </div>

        <div class="form-group">
            <label for="inputNumRecibo">Numero do Recibo</label>
            <input
            type="number" class="form-control"
        	name="numRecibo" id="inputNumRecibo"
        	placeholder="99999" value="{{ old('numRecibo', $movimento->num_recibo) }}"
        	/>
    		@if ($errors->has('numRecibo'))
        		<em>{{ $errors->first('numRecibo') }}</em>
   			@endif            
        </div>
		{{-- Checkbox para marcar, caso o ja tenha sido confirmado (Em implementacao) 
        @can('administrate',$user)
    		<div class="custom-control custom-checkbox">
        		<input name="comfirmado" type="checkbox" class="custom-control-input" id="CheckComfirmado" value="1"
        		@if(old('comfirmado', $movimento->confirmado) == 1 ){{ "checked" }}@endif>
        	<label class="custom-control-label" for="CheckComfirmado" >Confirmado</label>
    		</div>
    		@if ($errors->has('comfirmado'))
        		<em>{{ $errors->first('comfirmado') }}</em>
    		@endif
    	@endcan
		--}}
        <div class="form-group">
            <button type="submit" class="btn btn-success" name="ok">Adicionar</button>
            <a type="submit" class="btn btn-default" name="cancel" href="/movimetos">Cancelar</a>
        </div>
    </form>
@endsection
