<?php

namespace App\PurchaseOrder\Entities;

use App\Customers\Entities\Customer;
use App\Period\Entities\Period;
use App\Taxes\Entities\Taxes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class PurchaseOrder extends Model
{
    protected $table = "ordencompra";
    public $timestamps = false;


    public function getCodigoReferenciaAttribute()
    {
        return "OC-{$this->periodo->codigo}-{$this->numero}";
    }

    public function periodo()
    {
        return $this->belongsTo(Period::class, 'periodo_id');
    }

    public function moneda(){
        return $this->belongsTo(Currency::class, 'moneda_id');
    }

    public function impuesto1_fk(){
        return $this->belongsTo(Taxes::class, 'impuesto_id');
    }

    public function impuesto2_fk(){
        return $this->belongsTo(Taxes::class, 'impuesto2_id');
    }

    public function tercero(){
        return $this->belongsTo(Customer::class)->withDefault();
    }


    public static function listado_orden_compra($periodo){
        return static::select(DB::raw('o.*, DATE_FORMAT(o.fechaproceso,\'%d - %m - %Y\') as fechaproceso, t.descripcion as tercero_descripcion, m.simbolo as moneda_simbolo,
         a.descripcion as almacen_descripcion'))
        ->from('ordencompra as o')
        ->join('tercero as t','o.tercero_id','=','t.id')
        ->join('moneda as m','o.moneda_id','=','m.id')
        ->join('almacen as a','o.almacen_id','=','a.id')
        ->where('o.periodo_id',$periodo)
        ->orderby('o.id','asc')
        ->get();
    }
}
