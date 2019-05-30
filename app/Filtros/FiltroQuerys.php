<?php

namespace App\Filtros;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class FiltroQuerys
{

    protected $request;

    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            if (!method_exists($this, $name)) {
                continue;
            }
            if (strlen($value)) {
                $this->$name($value);
            } else {
                continue;
            }
        }
        return $this->builder;
    }


    public function filters()
    {
        return $this->request->all();
    }
}
