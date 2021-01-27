<?php

namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class TcambioProcedure{

    public function setTcambio($fecha)
    {
        return DB::select('CALL settcambio(?)', array($fecha));
    }
}
