<?php

namespace App\Filtros;

use App\Filtros\FiltroQuerys;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter($query, FiltroQuerys $filters)
    {
        return $filters->apply($query);
    }
}
