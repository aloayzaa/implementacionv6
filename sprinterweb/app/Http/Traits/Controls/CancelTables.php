<?php

namespace App\Http\Traits\Controls;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

trait CancelTables
{
    public function t_anular_registro(Request $request)
    {
        try {
            $privilegios = $this->privilegios();
            if ($this->user == 'ADMINISTRADOR'){
                $r = $this->proceso_anular($request);
            }else{
                if ($privilegios->anula == 1){
                    $r = $this->proceso_anular($request);
                }else{
                    return response()->json(['errors' => ['error' => 'No cuenta con privilegios para anular este registro']],500);
                }
            }
        } catch (PDOException $e) {
            DB::rollBack();
            $registro = DB::table($this->tabla_cabecera)->where('id', $request->id)->first();
            return response()->json([
                'errors' => ['error' => 'EL registro ' . $registro->codigo . ' está relacionado con otras tablas'],
                'message' => $e->getMessage()],500);
        }
        return $r;
    }

    public function proceso_anular($request){
        $model = DB::table($this->tabla_cabecera)->where('id',$request->id)->first();
        $estado = ($model->estado == 0) ? 1 : 0;
        $respuesta = DB::transaction(function () use ($request, $estado) {
            $respuesta = DB::table($this->tabla_cabecera)->where('id',$request->id)->update(['estado' => $estado]);
            return $respuesta;
        });

        if($respuesta){
            $r["success"] = "Estado actualizado Correctamente";
        }else{
            $r["error"] = "Error al cambiar el estado";
        }

        return $r;
    }

}
