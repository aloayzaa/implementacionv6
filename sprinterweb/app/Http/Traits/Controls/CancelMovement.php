<?php

namespace App\Http\Traits\Controls;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

trait CancelMovement
{
    public function t_comprobar_estado(Request $request){
        $tabla = DB::table($this->tabla_cabecera)->where('id',$request->id)->first();

        if (in_array($tabla->estado, $this->t_estados_borrar)) {
            //if ($usuario != 'ADMINISTRADOR'){
            //$mensaje = "Acción no válida. El documento está $tabla->estado";
            //return response()->json(['errors' => array('detalle' => $mensaje)],422);
            //}else{
                if($tabla->estado == "ANULADO"){
                    $r['success'] = "El documento está " . $tabla->estado . ". Desea activarlo?";
                }else{
                    $r['success'] = "El documento está " . $tabla->estado . ". Desea anularlo?";
                }

            //}

        }else{
            if($tabla->estado == "ANULADO"){
                $r['success'] = "El documento está " . $tabla->estado . ". Desea activarlo?";
            }else{
                $r['success'] = "El documento está " . $tabla->estado . ". Desea anularlo?";
            }
        }
        return $r;
    }
    public function t_anular_registro(Request $request)
    {
        $rules = array(
            'motivo' => 'required',
        );
        $message = array(
            'motivo.required' => 'EL motivo es obligatorio',
        );
        $this->validate($request, $rules, $message);

        /*$validar_estado = $this->t_validar_estado($request); //ver frmmovimiento anular
        if($validar_estado != 1){
            return '';
        }*/

        try {
            $privilegio = $this->privilegios();
            if ($this->user == 'ADMINISTRADOR'){
                $r = $this->proceso_movimiento($request);
            }else{
                if ($privilegio->anula == 1){
                    $r = $this->proceso_movimiento($request);
                }else{
                    return response()->json(['errors' => ['error' => 'No cuenta con privilegios para anular este registro']],500);
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

    public function proceso_movimiento($request){
        $model = DB::table($this->tabla_cabecera)->where('id',$request->id)->first();
        $estado = ($model->estado == 'ANULADO') ? 'ACTIVO' : 'ANULADO';
        $respuesta = DB::transaction(function () use ($request, $estado) {
            $respuesta = DB::table($this->tabla_cabecera)->where('id',$request->id)->update(['glosa' => $request->motivo, 'estado' => $estado]);
            return $respuesta;
        });

        if($respuesta){
            $r["success"] = "Estado actualizado Correctamente";
            //$this->contabilizar();
            //$this->centralizar();
            //$this->setestadoref();
            if($repuesta = $this->contabilizar_centralizar_setestadoref($request->id)){ return $repuesta;}
            if($repuesta = $this->centralizar($request->id)){ return $repuesta;}


        }else{
            $r["error"] = "Error al cambiar el estado";
        }
        return $r;
    }

}
