<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aeronave extends Model
{

    // Overrides primary key
    protected $primaryKey = 'matricula';

    // Disables auto increment primary key
    public $incrementing = false;

    // Enables auto timestamps
    public $timestamps = true;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'matricula', 'marca', 'modelo', 'num_lugares','preco_hora', 'conta_horas'
    ];
}
