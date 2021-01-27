<?php

namespace App\Http\Traits\Controls;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
trait StateMovement
{
    public function setestado($estado, $id)
    {
        $r = array();
        $respuesta = DB::table($this->tabla_cabecera)->where('id',$id)->update(['estado' => $estado]);
        if($respuesta){
            $r['estado'] = 'ok';
            $r['estado_tabla'] = $estado;
            return $r;
        }else{
            return response()->json([
                'errors' => ['error' => 'Error al actualizar el estado'],],500);
        }
    }

}
