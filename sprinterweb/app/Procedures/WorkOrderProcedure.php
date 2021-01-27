<?php

namespace App\Procedures;

use Illuminate\Support\Facades\DB;

class WorkOrderProcedure{

    public function setestado($ordentrabajo_id) // clasic, clase: ordentrabajo, método: setestado
    {
        return DB::select('CALL setordentrabajoestado (?)', array($ordentrabajo_id));
    }
}
