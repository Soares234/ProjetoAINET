<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AeronavesPilotos extends Model
{

    protected $table="aeronaves_pilotos";
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['matricula','piloto_id'];
}
