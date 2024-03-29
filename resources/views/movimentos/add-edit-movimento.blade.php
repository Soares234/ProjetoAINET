@extends('layouts.app')
@section('content')
    <form action="/movimentos" method="POST" class="form-group">
        @csrf
        @method("POST")

        <div class="form-group">
            <label for="inputDataVoo">Data do Voo</label>
            <input
                type="text" class="form-control"
                name="data" id="inputData"
                placeholder="01/01/2019" value="{{ old('data',Date( 'd/m/Y',strtotime($movimento->data))) }}"
            />
            @if ($errors->has('data'))
                <em>{{ $errors->first('data') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputHoraDescolagem">Hora de Descolagem</label>
            <input
                type="time" class="form-control"
                name="hora_descolagem" id="inputHoraDescolagem"
                placeholder="00:00" value="{{ old('hora_descolagem', Date('H:i:s',strtotime($movimento->hora_descolagem))) }}"
            />
            @if ($errors->has('hora_descolagem'))
                <em>{{ $errors->first('hora_descolagem') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputHoraAterragem">Hora de Aterragem</label>
            <input
                type="time" class="form-control"
                name="hora_aterragem" id="inputHoraAterragem"
                placeholder="00:00" value="{{ old('hora_aterragem', Date('H:i:s',strtotime($movimento->hora_aterragem))) }}"
            />
            @if ($errors->has('hora_aterragem'))
                <em>{{ $errors->first('hora_aterragem') }}</em>
            @endif
        </div>

        <input type="hidden" name="tempo_voo" />
        <input type="hidden" name="preco_voo"  />
        <div class="form-group">
            <label for="inputAeronave">Aeronave</label>

            <select class="form-control" name="aeronave" id="inputAeronave">

                <option disabled selected>Selecione uma Aeronave</option>

                @foreach($aeronaves as $aeronave)
                    <option {{ old('aeronave', $movimento->aeronave)==$aeronave->matricula ? "selected" : ''}}
                            value={{$aeronave->matricula}} >{{$aeronave->matricula}} - {{$aeronave->marca}} {{$aeronave->modelo}}</option>
                @endforeach
            </select>
            @if ($errors->has('aeronave'))
                <em>{{ $errors->first('aeronave') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputPiloto">Piloto</label>
            <input
                type="number" class="form-control"
                name="piloto_id" id="inputPiloto"
                placeholder="00000" value="{{ old('piloto_id', $movimento->piloto_id) }}"
            />
            @if ($errors->has('piloto_id'))
                <em>{{ $errors->first('piloto_id') }}</em>
            @endif
        </div>
        <div class="form-group">
            <label for="instrutor_id">Instrutor</label>
            <input
                type="number" class="form-control"
                name="instrutor_id" id="instrutor_id"
                placeholder="00000" value="{{ old('instrutor_id', $movimento->instrutor_id) }}"
            />
            @if ($errors->has('instrutor_id'))
                <em>{{ $errors->first('instrutor_id') }}</em>
            @endif
        </div>
        <div class="form-group">
            <label for="num_servico">Número Serviço</label>
            <input
                type="number" class="form-control"
                name="num_servico" id="num_servico"
                placeholder="00000" value="{{ old('num_servico', $movimento->num_servico) }}"
            />
            @if ($errors->has('num_servico'))
                <em>{{ $errors->first('num_servico') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNumDiario">Nº de Entrada no Diário de Borde</label>
            <input
                type="number" class="form-control"
                name="num_diario" id="inputPiloto"
                placeholder="00" value="{{ old('num_diario', $movimento->num_diario) }}"
            />
            @if ($errors->has('num_diario'))
                <em>{{ $errors->first('num_diario') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNaturezaVoo">Natureza do Voo</label>
            <select
                class="form-control"
                name="natureza" id="inputNaturezaVoo">

                <option disabled selected>Selecione um opção</option>
                <option {{ old('natureza', $movimento->natureza)=='T' ? "selected" : ''}} value="T">Treino</option>
                <option {{ old('natureza', $movimento->natureza)=='I' ? "selected" : ''}} value="I">Instrução</option>
                <option {{ old('natureza', $movimento->natureza)=='E' ? "selected" : ''}} value="E">Especial</option>

            </select>
            @if ($errors->has('natureza'))
                <em>{{ $errors->first('natureza') }}</em>
            @endif
        </div>
        <div class="form-group">
            <label for="tipo_instrucao">Tipo de Instrução</label>
            <select
                class="form-control"
                name="tipo_instrucao" id="tipo_inscricao">

                <option disabled selected>Selecione um opção</option>
                <option {{ old('tipo_instrucao', $movimento->natureza)=='D' ? "selected" : ''}} value="D">Duplo Comando</option>
                <option {{ old('tipo_instrucao', $movimento->natureza)=='S' ? "selected" : ''}} value="S">Solo</option>
            </select>
            @if ($errors->has('tipo_instrucao'))
                <em>{{ $errors->first('tipo_instrucao') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputAerodromoPartida">Código do Aerodromo de Partida</label>

            <select class="form-control" name="aerodromo_partida" id="inputAerodromoPartida">

                <option disabled selected>Selecione um Aerodromo</option>

                @foreach($aerodromos as $aerodromo)
                    <option {{ old('aerodromo_partida', $movimento->aerodromo_partida)==$aerodromo->code ? "selected" : ''}}
                            value={{$aerodromo->code}} >{{$aerodromo->code}} - {{$aerodromo->nome}} </option>
                @endforeach

            </select>

            @if ($errors->has('aerodromo_partida'))
                <em>{{ $errors->first('aerodromo_partida') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputAerodromoChegada">Código do Aerodromo de Chegada</label>

            <select class="form-control" name="aerodromo_chegada" id="inputAerodromoChegada">

                <option disabled selected>Selecione um Aerodromo</option>

                @foreach($aerodromos as $aerodromo)
                    <option {{ old('aerodromo_chegada', $movimento->aerodromo_chegada)==$aerodromo->code ? "selected" : ''}}
                            value={{$aerodromo->code}} >{{$aerodromo->code}} - {{$aerodromo->nome}} </option>
                @endforeach

            </select>

            @if ($errors->has('aerodromo_chegada'))
                <em>{{ $errors->first('aerodromo_chegada') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNumDescolagens">Número de Descolagens</label>
            <input
                type="number" class="form-control"
                name="num_descolagens" id="inputNumDescolagens"
                placeholder="1" value="{{ old('num_descolagens', $movimento->num_descolagens) }}"
            />
            @if ($errors->has('num_descolagens'))
                <em>{{ $errors->first('num_descolagens') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNumAterragens">Número de Aterragens</label>
            <input
                type="number" class="form-control"
                name="num_aterragens" id="inputNumAterragens"
                placeholder="1" value="{{ old('num_aterragens', $movimento->num_aterragens) }}"
            />
            @if ($errors->has('num_aterragens'))
                <em>{{ $errors->first('num_aterragens') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNumPessoas">Número de Passageiros</label>
            <input
                type="number" class="form-control"
                name="num_pessoas" id="inputNumPessoas"
                placeholder="1" value="{{ old('num_pessoas', $movimento->num_pessoas) }}"
            />
            @if ($errors->has('num_pessoas'))
                <em>{{ $errors->first('num_pessoas') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputContaHorasInicio">Conta-Horas Inicial</label>
            <input
                type="number" class="form-control"
                name="conta_horas_inicio" id="inputContaHorasInicio"
                placeholder="99999" value="{{ old('conta_horas_inicio', $movimento->conta_horas_inicio) }}"
            />
            @if ($errors->has('conta_horas_inicio'))
                <em>{{ $errors->first('conta_horas_inicio') }}</em>
            @endif

            @if ($errors->has('S') || $errors->has('B'))
                <br>
                <label for="inputConflitoJustificacao">Conflito {{$errors->has('S') ? 'Sobreposição' : 'Buraco'}}
                </label>
                <textarea type="text" class="form-control"
                name="justificacao_conflito" id="inputConflitoJustificacao"
                          value="{{old('justificacao_conflito',$movimento->justificacao_conflito)}}"></textarea>
                <div class="custom-control custom-checkbox custom-control-inline">
                    <input type="checkbox" class="custom-control-input" name="confirmado" id="inputConflitoConfirmado" value={{$errors->has('S') ? 'S' : 'B'}}
                    @if(old('confirmado', $movimento->tipo_conflito) != NULL){{ "checked" }}@endif>

                    <label class="custom-control-label float-left" for="inputConflitoConfirmado">Se quiser confirmar o conflito por favor confirme a checkbox</label>

                </div>

            @endif

        </div>

        <div class="form-group">
            <label for="inputContaHorasFim">Conta-Horas Final</label>
            <input
                type="number" class="form-control"
                name="conta_horas_fim" id="inputContaHorasFim"
                placeholder="99999" value="{{ old('conta_horas_fim', $movimento->conta_horas_fim) }}"
            />
            @if ($errors->has('conta_horas_fim'))
                <em>{{ $errors->first('conta_horas_fim') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputModoPagamento">Modo de Pagamento</label>
            <select
                class="form-control"
                name="modo_pagamento" id="inputModoPagamento">

                <option disabled selected>Selecione um opção</option>
                <option {{ old('modo_pagamento', $movimento->modo_pagamento)=='N' ? "selected" : ''}} value="N">Numerário
                </option>
                <option {{ old('modo_pagamento', $movimento->modo_pagamento)=='M' ? "selected" : ''}} value="M">
                    Multibanco
                </option>
                <option {{ old('modo_pagamento', $movimento->modo_pagamento)=='T' ? "selected" : ''}} value="T">
                    Transferência
                </option>
                <option {{ old('modo_pagamento', $movimento->modo_pagamento)=='P' ? "selected" : ''}} value="P">Pacote de
                    Horas
                </option>

            </select>
            @if ($errors->has('modo_pagamento'))
                <em>{{ $errors->first('modo_pagamento') }}</em>
            @endif
        </div>

        <div class="form-group">
            <label for="inputNumRecibo">Numero do Recibo</label>
            <input
                type="number" class="form-control"
                name="num_recibo" id="inputNumRecibo"
                placeholder="99999" value="{{ old('num_recibo', $movimento->num_recibo) }}"
            />
            @if ($errors->has('num_recibo'))
                <em>{{ $errors->first('num_recibo') }}</em>
            @endif
        </div>
        <div class="form-group">
            <label for="observacoes">Observações</label>
            <textarea
                class="form-control"
                name="observacoes" id="observacoes">{{old('observacoes', $movimento->observacoes) }}</textarea>
            @if ($errors->has('observacoes'))
                <em>{{ $errors->first('observacoes') }}</em>
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
            <a type="submit" class="btn btn-default" name="cancel" href="/movimentos">Cancelar</a>
        </div>
    </form>
@endsection
