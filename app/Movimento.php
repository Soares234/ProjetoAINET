<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    // Overrides primary key
    protected $primaryKey = 'id';

    // Disables auto increment primary key
    public $incrementing = true;

    // Enables auto timestamps
    public $timestamps = true;

    protected $dates = ['deleted_at'];
//Lista de mass fillables
    protected $fillable = [
        'conta_horas_inicial', 'conta_horas_final', 'data','hora_descolagem', 'hora_aterragem',
        'aeronave','num_diario','num_servico','piloto_id','num_licenca_piloto','validade_licenca_piloto'
        ,'',''
    ];


}
