<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Movimento extends Model {

    // Overrides primary key
    protected $primaryKey = 'id';

    // Disables auto increment primary key
    public $incrementing = true;

    // Enables auto timestamps
    public $timestamps = true;

	//Lista de mass fillables
    protected $fillable = [
        'data','hora_descolagem', 'hora_aterragem','conta_horas_inicio','conta_horas_fim',
        'aeronave','num_diario','num_servico','piloto_id','num_licenca_piloto','validade_licenca_piloto',
        'tipo_licenca_piloto','num_certificado_piloto','validade_certificado_piloto','classe_certificado_piloto',
        'natureza', 'aerodromo_partida','aerodromo_chegada', 'num_aterragens','num_descolagens','num_pessoas',
        'tempo_voo','preco_voo','modo_pagamento','num_recibo','observacoes','confirmado','tipo_instrucao',
        'instrutor_id','num_licenca_instrutor','validade_licenca_instrutor','tipo_licenca_instrutor',
        'num_certificado_instrutor','validade_certificado_instrutor','classe_certificado_instrutor',
        'tipo_conflito','justificacao_conflito'
    ];
}
