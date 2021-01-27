<?php

namespace App\DocumentToPay\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentToPayWork extends Model
{
    protected $table = "docxpagar_otrabajo";
    protected $primaryKey = "parent_id";
    public $timestamps = false;

    public static function liberar_orden_trabajo_billing($docxpagar_id){
        return static::where('parent_id', $docxpagar_id)
        ->delete();
    }

    public function saveDetails($parent_id, $item, $data){

        return static::where('parent_id', $parent_id)
            ->where('item', $item)
            ->update($data);

    }

    public static function selectparentcmd($parent_id){ // VER CLASIC 
        return DB::select("select d.*, r.numero, r.nromanual, r.glosa, r.ventana
        from docxpagar_otrabajo d 
        join ordentrabajo r on d.ordentrabajo_id = r.id
        where d.parent_id = $parent_id
        union all 
        select d.*, r.numero, concat('CO ', right(concat('000',r.serie),3), '-', right(concat('00000',r.numero),5)) as nromanual, r.glosa, r.ventana
        from docxpagar_otrabajo d 
        join cotizacion r on d.cotizacion_id = r.id
        join periodo p on r.periodo_id = p.id
        where d.parent_id = $parent_id");
    }

}
