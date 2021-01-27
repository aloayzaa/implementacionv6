<?php

namespace App\Http\Traits\Controls;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
trait ApproveMovement
{
    public function t_aprobar(Request $request)
    {
        //dd($request->id);
        //$this->t_estados_aprobar
        //$this->tabla_cabecera
        //dd($tabla->estado);
        $tabla = DB::table($this->tabla_cabecera)->where('id',$request->id)->first();

        if (in_array($tabla->estado, $this->t_estados_aprobar)) {
            $mensaje = getMesaage(26);
            return response()->json(['errors' => array('detalle' => $mensaje)],422);
        }
        if ($tabla->estado == 'APROBADO') {
            return $this->setestado('ACTIVO',$request->id);
        } else {
            return $this->setestado('APROBADO',$request->id);
        }
    }

}
