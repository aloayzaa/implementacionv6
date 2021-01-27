<?php

namespace App\PointSale\Entities;

use Illuminate\Database\Eloquent\Model;

class PointSaleSeries extends Model
{
    protected $table = "puntoventa_series";
    public $timestamps = false;

    public static function update_data($id, $data){
        return static::where('id', $id)
            ->update($data);
    }

    public static function selectparentcmd($puntoventa_id){ //Clase puntoventa_series - método selectparentcmd -> se forma el cursor  crspuntoventa_series
        return static::select("c.*", "d.codigo", "d.descripcion")
            ->from("puntoventa_series as c")
            ->join("documentocom as d", "c.documentocom_id", "=", "d.id")
            ->where("c.puntoventa_id", $puntoventa_id)
            ->get();
    }

    public static function selectparentcmd_documentocom($puntoventa_id, $documentocom_id){ //Clase puntoventa_series - método selectparentcmd() - módificado para que filtre con documentocom -> se forma el cursor  crspuntoventa_series
        return static::select("c.*", "d.codigo", "d.descripcion")
            ->from("puntoventa_series as c")
            ->join("documentocom as d", "c.documentocom_id", "=", "d.id")
            ->where("c.puntoventa_id", $puntoventa_id)
            ->where("c.documentocom_id", $documentocom_id)
            ->first();
    }
}
