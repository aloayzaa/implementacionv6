<?php

namespace App\Warehouses\Entities;

use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\MovementTypes\Entities\MovementType;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarehouseOrder extends Model
{
    protected $table = "pedidoalmacen";
    public $timestamps = false;

    public function periodo(){
        return $this->belongsTo(Period::class);
    }

    public function tercero(){
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function moneda(){
        return $this->belongsTo(Currency::class);
    }

    public function condicionpago(){
        return $this->belongsTo(PaymentCondition::class);
    }

    public function scopeListPedidos($query, $period_id){
        if($period_id){
            return $query->select(        //quite un * para ingreso y sucursal
                'p.id as pedido_id',
                'p.estado as estado_pedido',
                'p.numero',
                'p.fecha',
                'p.glosa',
                'a.codigo as codigo_almacen',
                'a.descripcion as descripcion_almacen',
                't.codigo as codigo_tercero',
                't.descripcion as descripcion_tercero',
                'mov.codigo as codigo_movimiento',
                'mov.descripcion as descripcion_movimiento',
                'ot.id as orden_trabajo')
                ->from('pedidoalmacen as p')
                ->leftjoin('almacen as a','a.id','=','p.almacen_id')
                ->leftjoin('tercero as t','t.id','=','p.tercero_id')
                ->leftjoin('movimientotipo as mov','mov.id','=','p.movimientotipo_id')
                ->leftjoin('ordentrabajo as ot','ot.id','=','p.ordentrabajo_id')
                ->where('p.periodo_id',$period_id)
                ->get();
        }
    }

    public function scopeListPedidosVenta($query, $period_id, $ventana){
        if($period_id){
            return $query->select(        //quite un * para ingreso y sucursal
                'p.id as id',
                'p.numero',
                'p.fecha',
                'p.glosa',
                'p.importe',
                'p.estado as estado_pedido',
                't.codigo as codigo_tercero',
                't.descripcion as descripcion_tercero',
                'm.descripcion as moneda',
                'v.descripcion as pto_venta',
                'tv.descripcion as tipoventa')
                ->from('pedidoalmacen as p')
                ->leftjoin('almacen as a','a.id','=','p.almacen_id')
                ->leftjoin('tercero as t','t.id','=','p.tercero_id')
                ->leftjoin('moneda as m','m.id','=','p.moneda_id')
                ->leftjoin('puntoventa as v','v.id','=','p.puntoventa_id')
                ->leftjoin('tipoventa as tv','tv.id','=','p.tipoventa_id')
                ->where('p.periodo_id', $period_id)
                ->where('p.ventana', $ventana)
                ->get();
        }
    }

    public static function cotizacion_billing($pedidoalmacen_id){ // mov_facturaventa, objeto: txtocompra, método:keyPress

        return static::select("c.id", "c.serie", "c.numero", "c.vendedor_id", "c.ventana")
        ->from("pedidoalmacen as p")
        ->join("cotizacion as c", "p.cotizacion_id", "=", "c.id")
        ->where("p.id", $pedidoalmacen_id)
        ->first();

    } 

}
