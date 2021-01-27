<?php

namespace App\Transport\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransportGuide extends Model
{
    protected $table = "guiatransporte";
    public $timestamps = false;

    public static function liberar_guias_transporte_billing($docxpagar_id, $data){

        return static::where('docxpagar_id', $docxpagar_id)
            ->update($data);        
        
    }

    public static function selectidcmd($guiatransporte_id){ // clasic, clase: guiatransporte, método: selectidcmd
        
        return static::select(DB::raw("select g.*, ifnull(r.codigo,'') as ruta_cod, ifnull(r.descripcion, '') as ruta_dsc, ifnull(r.distancia,0) as distancia, ifnull(up.codigo,'') as ubigeop, ifnull(ud.codigo,'') as ubigeod, u.constanciamtc, u.configveh, u.pesobruto, u.cargautil"))
        ->from("guiatransporte as g")
        ->leftJoin("ruta as r", "g.ruta_id", "=", "r.id")
        ->leftJoin("ubigeo as up", "r.ubigeop_id", "=", "up.id")
        ->leftJoin("ubigeo as ud", "r.ubigeod_id", "=", "ud.id")
        ->join("undtransporte as u", "g.tracto_id", "=", "u.id")
        ->where("g.id", $guiatransporte_id)
        ->first();

    }
}
