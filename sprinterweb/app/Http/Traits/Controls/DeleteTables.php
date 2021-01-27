<?php

namespace App\Http\Traits\Controls;

use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDOException;

trait DeleteTables
{

    public function t_eliminar_registro(Request $request)
    {
        try {
            $privilegios = $this->privilegios();
            if ($this->user == 'ADMINISTRADOR'){
                $r = $this->proceso_eliminar($request);
            }else{
                if ($privilegios->borra == 1){
                    $r = $this->proceso_eliminar($request);
                }else{
                    return response()->json(['errors' => ['error' => 'No cuenta con privilegios para eliminar este registro']],500);
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

    public function eliminar_tablas_hijas(){
        return '';
    }

    public function proceso_eliminar($request){
        $modelo = DB::transaction(function () use ($request) {
            $modelo = DB::table($this->tabla_cabecera)->find($request->id);
            DB::table($this->tabla_cabecera)->where('id',$request->id)->delete();
            return $modelo;
        });

        if($modelo){
            $this->eliminar_tablas_hijas($modelo);
            $r["success"] = "Registro eliminado Correctamente";
        }else{
            $r["error"] = "Error al eliminar";
        }
        return $r;
    }

}
