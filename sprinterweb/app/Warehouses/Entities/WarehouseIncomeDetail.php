<?php

namespace App\Warehouses\Entities;

use App\Products\Entities\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarehouseIncomeDetail extends Model
{
   // protected $connection = 'DB_CONNECTION_SPRINTER';
    protected $table = "ingresoalmacen_detalle";
    public $timestamps = false;



    public function producto(){
        return $this->belongsTo(Productos::class);
    }

    public static function saveDetails($parent_id,$item,$data){
        return static::where('parent_id', $parent_id)
            ->where('item',$item)
            ->update($data);
    }

    public static function idCantidad($res1, $ide)
    {
        return static::select(DB::raw('count(*) as idCantidad'))
            ->where('parent_id','=',$ide)
            ->where('item','=',$res1)
            ->get()
            ->first();
    }

    public function scopeData($query, $id)
    {
        if ($id) {
            return $query->select('ingresoalmacen_detalle.parent_id', 'ingresoalmacen_detalle.item', 'ingresoalmacen_detalle.glosa', 'ingresoalmacen_detalle.serie', 'ingresoalmacen_detalle.lote', 'ingresoalmacen_detalle.vencimiento', 'ingresoalmacen_detalle.cantidad', 'ingresoalmacen_detalle.precio', 'ingresoalmacen_detalle.preciomn', 'ingresoalmacen_detalle.preciome', 'ingresoalmacen_detalle.importe', 'ingresoalmacen_detalle.importemn', 'ingresoalmacen_detalle.importe', 'ingresoalmacen_detalle.peso', 'p.id as producto_id', 'p.codigo as codigo_producto', 'p.descripcion as descripcion_producto', 'u.id as umedida_id', 'u.codigo as codigo_umedida', 'c.id as centrocosto_id', 'c.codigo as codigo_ccosto', 'c.descripcion as descripcion_ccosto', 'pr.id as proyecto_id', 'pr.codigo as codigo_proyecto', 'pr.descripcion as descripcion_proyecto', 'a.id as actividad_id', 'a.codigo as codigo_actividad', 'a.descripcion as descripcion_actividad', 'al.id as almacen_id', 'al.codigo as codigo_almacen', 'al.descripcion as descripcion_almacen')
                ->join('producto as p', 'p.id', '=', 'ingresoalmacen_detalle.producto_id', 'left')
                ->join('umedida as u', 'u.id', '=', 'ingresoalmacen_detalle.umedida_id', 'left')
                ->join('centrocosto as c', 'c.id', '=', 'ingresoalmacen_detalle.centrocosto_id', 'left')
                ->join('proyecto as pr', 'pr.id', '=', 'ingresoalmacen_detalle.proyecto_id', 'left')
                ->join('actividad as a', 'a.id', '=', 'ingresoalmacen_detalle.actividad_id', 'left')
                ->join('almacen as al', 'al.id', '=', 'ingresoalmacen_detalle.almacen_id', 'left')
                ->where('ingresoalmacen_detalle.parent_id', '=', $id)
                ->get();
        }
    }

}
