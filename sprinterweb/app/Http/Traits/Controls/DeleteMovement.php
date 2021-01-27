<?php

namespace App\Http\Traits\Controls;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

trait DeleteMovement
{

    public function t_eliminar_registro(Request $request)
    {

        $tabla = DB::table($this->tabla_cabecera)->where('id',$request->id)->first();

        if (in_array($tabla->estado, $this->t_estados_borrar)) {
            $mensaje = getMesaage(26);
            return response()->json(['errors' => array('detalle' => $mensaje)],422);
        }

        /*$validar_estado = $this->t_validar_estado($request);  //ver frmmovimiento borrar
        if($validar_estado != 1){
            return '';
        }*/

        try {
            $privilegios = $this->privilegios();
            if ($this->user == 'ADMINISTRADOR'){
                $r = $this->procesos_movimiento($request);
            }else{
                if ($privilegios->borra = 1){
                    $r = $this->procesos_movimiento($request);
                }else{
                    return response()->json(['errors' => ['error' => 'No cuenta con privilegios para eliminar este registro']],500);
                }
            }
        } catch (PDOException $e) {

            DB::rollBack();
            $registro = DB::table($this->tabla_cabecera)->where('id', $request->id)->first();
            return response()->json([
                'errors' => ['error' => 'EL registro ' . $registro->numero . ' está relacionado con otras tablas'],
                'message' => $e->getMessage()],500);
        }
        return $r;

    }

    public function procesos_movimiento($request){
        $respuesta = DB::transaction(function () use ($request) {
            $respuesta = DB::table($this->tabla_cabecera)->where('id',$request->id)->delete();
            return $respuesta;
        });

        if($respuesta){
            $r["success"] = "Registro eliminado Correctamente";
            //$this->contabilizar();
            //$this->setestadoref();
            if($repuesta = $this->contabilizar_centralizar_setestadoref($request->id)){ return $repuesta;}
        }else{
            $r["error"] = "Error al eliminar";
        }
        return $r;
    }

}
