<?php

namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class PurchaseOrderProcedure
{

    public function getPedidoCompra($phasta, $pid)
    {
        return DB::select('CALL getpedidocompra(?,?)',
            array($phasta, $pid));
    }

    public function setordencompraestado($pid, $ptabla)
    {
        return DB::select('CALL setordencompraestado (?,?)',
            array($pid, $ptabla));
    }
}
