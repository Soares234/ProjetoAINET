<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nome_informal' , 'sexo', 'data_nascimento', 'nif' ,
        'telefone', 'tipo_socio', 'quota_paga', 'direcao', 'ativo', 'num_socio','email_verified_at','tipo_licenca',
        'num_licenca','validade_licenca','licenca_confirmada','classe_certificado','num_certificado','certificado_confirmado',
        'validade_certificado','endereco'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
