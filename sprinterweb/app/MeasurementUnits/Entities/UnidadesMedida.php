<?php

namespace App\MeasurementUnits\Entities;

use App\Warehouses\Entities\WarehouseOrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnidadesMedida extends Model
{
    protected $table="umedida";
    public $timestamps = false;

    public function pedidoAlmacenDetalle(){
        return $this->hasMany(WarehouseOrderDetail::class);
    }
    public function ordenCompraDetalle(){
        return $this->hasMany(OrdenCompraDetalle::class);
    }
    public function documentosxPagarDetalle(){
        return $this->hasMany(DocumentoPorPagarDetalle::class);
    }

    public function confirmaServicioDetalle(){
        return $this->hasMany(DetalleConfirmServicio::class);
    }

    public function producto(){
        return $this->hasMany(Productos::class);
    }
    public function scopeBuscar($query, $txtBusca)
    {
            if(trim($txtBusca)!="")
            {
                $query->where('descripcion', 'LIKE',"%$txtBusca%")
                ->orWhere('codigo', 'LIKE',"%$txtBusca%");
            }
    }

    public static function anular($id,$parametro)
    {
        return static::where('id',$id)
            ->update(['estado' => $parametro]);
    }

    public static function activar($id,$parametro)
    {
        return static::where('id',$id)
            ->update(['estado' => $parametro]);
    }

    public static function filtroId($id)
    {
        return static::select('codigo','descripcion')
            ->Where('id','=',$id)
            ->get();
    }

    public static function lista_search($codigo,$descripcion)
    {
        $term=$codigo." ".$descripcion;
        return static::select('codigo','id','descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(50)
            ->get();
    }

    public static function devuelveId($variable)
    {
        return static::select('id')
        ->Where('codigo','=',"$variable")
        ->get();
    }

    public static function cantidad_registros()
    {
        return static::select(DB::raw('count(*) as cantidad'))
            ->get()
            ->first();
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
        ->where('descripcion', 'LIKE',"%$term%")
        ->orWhere('codigo', 'LIKE',"%$term%")
        ->get();
    }

    public static function generar_unidad($id){
        return static::select(DB::raw('umedida.id, umedida.codigo, umedida.descripcion'))
            ->join('producto as p', 'p.umedida_id', '=', 'umedida.id')
            ->where('p.umedida_id', $id)
            ->first();
    }
}
