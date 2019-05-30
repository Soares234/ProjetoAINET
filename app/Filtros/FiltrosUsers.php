<?php

namespace App\Filtros;

use App\Filtros\FiltroQuerys;
use Illuminate\Database\Eloquent\Builder;

class FiltrosUsers extends FiltroQuerys
{

    public function nrSocio($nr = '')
    {
        return $this->builder->where('num_socio', '=',$nr);
    }

    /**
     * @param string $name
     * @return Builder
     */
    public function nome($name = '')
    {
        return $this->builder->where('nome_informal','like','%'.$name.'%');
    }

    /**
     * @param string $email
     * @return Builder
     */
    public function email($email = ''){
        return $this->builder->where('email','=',$email);
    }

    /**
     * @param string $tipo
     * @return Builder
     */
    public function tSocio($tipo = ''){
        return $this->builder->where('tipo_socio','=',$tipo);
    }

    /**
     * @param string $direcao
     * @return Builder
     */
    public function direcao($direcao = ''){
        return $this->builder->where('direcao','=',$direcao);
    }

    /**
     * @param string $ativo
     * @return Builder
     */
    public function ativo($ativo = ''){
        return $this->builder->where('ativo','=',$ativo);
    }

    /**
     * @param string $quota
     * @return Builder
     */
    public function quotaPaga($quota = ''){
        return $this->builder->where('quota_paga','=',$quota);
    }

}
