<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AeronavesValores extends Model
{
    protected $table="aeronaves_valores";
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['matricula','unidade_conta_horas','minutos','preco'];
}
