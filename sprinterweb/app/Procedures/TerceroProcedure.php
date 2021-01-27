<?php

namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class TerceroProcedure{

    public function validaDeudaSocio($tercero_id, $fecha, $rpta)
    {
        return DB::select('CALL validadeudasocio(?,?,?)', array($tercero_id, $fecha, $rpta));
    }
}
