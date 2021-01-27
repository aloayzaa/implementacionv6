<?php

namespace App\Warehouses\Entities;

use App\MeasurementUnits\Entities\UnidadesMedida;
use App\Products\Entities\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarehouseOrderDetail extends Model
{
    protected $table = "pedidoalmacen_detalle";
    public $timestamps = false;


    public function producto(){
        return $this->belongsTo(Productos::class);
    }

    public function unidadmedida()
    {
        return $this->belongsTo(UnidadesMedida::class, 'umedida_id');
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
            return $query->select(
                'pedidoalmacen_detalle.parent_id',
                'pedidoalmacen_detalle.item',
                'pedidoalmacen_detalle.glosa',
                'pedidoalmacen_detalle.serie',
                'pedidoalmacen_detalle.lote',
                'pedidoalmacen_detalle.vencimiento',
                'pedidoalmacen_detalle.cantidad',
                'pedidoalmacen_detalle.precio',
                'pedidoalmacen_detalle.producto_id',
                'pedidoalmacen_detalle.umedida_id',
                'pedidoalmacen_detalle.centrocosto_id',
                'p.codigo as codigo_producto',
                'p.descripcion as descripcion_producto',
                'u.codigo as codigo_umedida',
                'c.codigo as codigo_ccosto',
                'c.descripcion as descripcion_ccosto')
                ->join('producto as p', 'p.id', '=', 'pedidoalmacen_detalle.producto_id', 'left')
                ->join('umedida as u', 'u.id', '=', 'pedidoalmacen_detalle.umedida_id', 'left')
                ->join('centrocosto as c', 'c.id', '=', 'pedidoalmacen_detalle.centrocosto_id', 'left')
                ->where('pedidoalmacen_detalle.parent_id', '=', $id)
                ->get();
        }
    }

    public function scopeDataVentas($query, $id)
    {
        if ($id) {
            return $query->select(
                'pedidoalmacen_detalle.parent_id',
                'pedidoalmacen_detalle.item',
                'pedidoalmacen_detalle.glosa',
                'pedidoalmacen_detalle.serie',
                'pedidoalmacen_detalle.lote',
                'pedidoalmacen_detalle.vencimiento',
                'pedidoalmacen_detalle.cantidad',
                'pedidoalmacen_detalle.precio',
                'pedidoalmacen_detalle.descuento',
                'pedidoalmacen_detalle.importe',
                'pedidoalmacen_detalle.producto_id',
                'pedidoalmacen_detalle.umedida_id',
                'pedidoalmacen_detalle.centrocosto_id',
                'p.codigo as codigo_producto',
                'p.descripcion as descripcion_producto',
                'u.codigo as codigo_umedida',
                'c.codigo as codigo_ccosto',
                'c.descripcion as descripcion_ccosto')
                ->join('producto as p', 'p.id', '=', 'pedidoalmacen_detalle.producto_id', 'left')
                ->join('umedida as u', 'u.id', '=', 'pedidoalmacen_detalle.umedida_id', 'left')
                ->join('centrocosto as c', 'c.id', '=', 'pedidoalmacen_detalle.centrocosto_id', 'left')
                ->where('pedidoalmacen_detalle.parent_id', '=', $id)
                ->get();
        }
    }
}
