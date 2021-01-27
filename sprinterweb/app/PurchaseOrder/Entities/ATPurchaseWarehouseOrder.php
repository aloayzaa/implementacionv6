<?php

namespace App\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ATPurchaseWarehouseOrder extends Model
{
    protected $table = "pedidoalmacen_atcompra";
    public $timestamps = false;

    public static function referencia_orden_compra($ordencompra_id){
        return static::select(DB::raw("pa.id, pa.fecha, concat('PE ', pe.codigo,'-',right(concat('00000',pa.numero),5)) as documento, pa.glosa"))
            ->from('pedidoalmacen_atcompra as pat')
            ->join('pedidoalmacen as pa','pat.parent_id','=','pa.id')
            ->join('ordencompra as o','pat.ordencompra_id','=','o.id')
            ->join('periodo as pe','pa.periodo_id','=','pe.id')
            ->where('pat.ordencompra_id',$ordencompra_id)
            ->get();
    }

}
