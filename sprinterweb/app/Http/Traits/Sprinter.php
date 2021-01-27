<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


trait Sprinter
{
    function obtener_cliente()
    {
        $ruc = session('company_ruc');
        if(!Session::all()){
            $ruc = '10707628338';
        }
        DB::setDefaultConnection('DB_CONNECTION_' . $ruc);
        DB::purge('DB_CONNECTION_' . $ruc);
    }
}
