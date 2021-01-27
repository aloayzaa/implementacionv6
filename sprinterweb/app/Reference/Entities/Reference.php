<?php

namespace App\Reference\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reference extends Model
{
    protected $table = "referencia";
    public $timestamps = false;

    public static function idCantidadRefactorizado($parent_id, $docxpagar_id)
    {
        return static::select(DB::raw('count(*) as idCantidad'))
            ->where('parent_id', '=', $parent_id)
            ->where('referencia_id', '=', $docxpagar_id)
            ->get()
            ->first();
    }

    public static function editarReferencia($parent_id, $referencia_id, $data)
    {
        return static::where('parent_id', $parent_id)
            ->where('referencia_id', $referencia_id)
            ->update($data);
    }

    public static function selectidcmd_cb($pId){
        return static::selectRaw("d.*, concat(rtrim(dc.codigo),' ',rtrim(r.seriedoc),'-',rtrim(r.numerodoc)) as docrefer, dc.codsunat tipodoc, r.seriedoc, r.numerodoc, r.fechadoc, 
        dc.descripcion as denominacion, r.ventana")
            ->from('referencia as d')
            ->leftJoin('docxpagar as r', 'd.referencia_id', '=', 'r.id')
            ->leftJoin('documentocom as dc', 'r.documento_id', '=', 'dc.id')
            ->where('d.parent_id', $pId)
            ->first();
    }

    public static function verificar_eliminar_facturacion($docxpagar_id){
        return static::select("id")
        ->from("referencia")
        ->where("referencia_id", $docxpagar_id)
        ->where("aplicar", 0)
        ->first();
    }
}
