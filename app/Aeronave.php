<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aeronave extends Model
{
    protected $fillable = [
        'matricula', 'marca', 'modelo', 'num_lugares','preco_hora', 'conta_horas'
    ];
}
