<?php

namespace App\DocComunicacion;

use Illuminate\Database\Eloquent\Model;

class DocComunicacionDetalle extends Model
{
    protected $table = "doccomunicacion_detalle";
    public $timestamps = false;

    public static function saveDetails($parent_id,$item,$data){
        return static::where('parent_id', $parent_id)
            ->where('item',$item)
            ->update($data);
    }

    public static function anular_facturacion($docxpagar_id){
        return static::select("item")
        ->from("doccomunicacion_detalle as d")
        ->join("doccomunicacion as c", "d.parent_id", "=", "c.id")
        ->where("d.referencia_id", $docxpagar_id)
        ->whereIn("c.estado", ['ACEPTADO','EN PROCESO'])
        ->first();
    }

    public static function baja($referencia_id){
        return static::select("c.id")
        ->from("doccomunicacion_detalle as d")
        ->join("doccomunicacion as c", "d.parent_id", "=", "c.id")
        ->where("d.referencia_id", $referencia_id)
        ->whereIn("c.tipo", ['RA','RR'])
        ->first();
    }

}
