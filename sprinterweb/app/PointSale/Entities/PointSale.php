<?php

namespace App\PointSale\Entities;

use App\DocumentToPay\Entities\DocumentToPay;
use App\Warehouses\Entities\WareHouses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PointSale extends Model
{
    protected $table = "puntoventa";
    public $timestamps = false;


    public function documentosPorPagar(){
        return $this->hasMany(DocumentToPay::class);
    }

    public function almacen(){
        return $this->belongsTo(WareHouses::class);
    }

    public static function anular($id,$parametro)
    {
        return static::where('id',$id)
            ->update(['estado' => $parametro]);
    }

    public static function obtener_almacen_sucursal($puntoventa_id){
        return static::select(DB::raw('p.id as idpuntov, p.codigo as codigopuntov, p.descripcion as descripcionpuntov, a.id as idalmacen, a.codigo as codigoalmacen, a.descripcion as descripcionalmacen, a.direccion as direccionalmacen, s.id as idsucursal, s.codigo as codigosucursal, s.descripcion as descripcionsucursal'))
        ->from('puntoventa as p')
        ->join('almacen as a','p.almacen_id','=','a.id')
        ->join('sucursal as s','a.sucursal_id','=','s.id')
        ->where('p.id',$puntoventa_id)
        ->first();
    }

    public static function obtener_almacen(){
        return static::select(DB::raw('puntoventa.id, puntoventa.codigo, puntoventa.descripcion, al.direccion'))
            ->join('almacen as al', 'puntoventa.almacen_id', '=', 'al.id')
            ->where('puntoventa.estado', 1)
            ->get();
    }

    public static function valida_serie($id){
        return static::selectRaw('p.id, p.codigo, p.descripcion, p.umedida_id, u.codigo ume_cod')
            ->from('producto as p')
            ->join('ingresoalmacen_detalle as d', 'd.producto_id', '=', 'p.id')
            ->join('umedida as u', 'p.umedida_id', '=', 'u.id')
            ->where('d.serie', $id)
            ->first();
    }

    public static function selectidcmd_billing($puntoventa_id){  // clase puntoventa, método selectidcmd
        return static::select(DB::raw('p.*, o.codigo as cco_cod, a.sucursal_id, a.direccion, s.codigo as suc_cod, s.direccion as domicilio, c.banco_id, c.moneda_id, t.tipotransaccion_id, t.conkardex, t.movimientotipo_id'))
        ->from('puntoventa as p')
        ->leftJoin('centrocosto as o','p.centrocosto_id','=','o.id')
        ->leftJoin('almacen as a','p.almacen_id','=','a.id')
        ->leftJoin('ctactebanco as c','p.ctactebanco_id','=','c.id')
        ->leftJoin('tipoventa as t', 'p.tipoventa_id','=','t.id')
        ->leftJoin('sucursal as s','a.sucursal_id','=','s.id')
        ->where('p.id',$puntoventa_id)
        ->first();
    }

    public static function selectidcmd_codigo($id){
        return static::selectRaw('p.*, o.codigo cco_cod, a.sucursal_id, a.direccion, s.codigo suc_cod, s.direccion domicilio, c.banco_id, c.moneda_id, t.tipotransaccion_id, t.conkardex, t.movimientotipo_id')
            ->from('puntoventa as p')
            ->leftJoin('centrocosto as o', 'p.centrocosto_id', '=', 'o.id')
            ->leftJoin('almacen as a', 'p.almacen_id', '=', 'a.id')
            ->leftJoin('ctactebanco as c', 'p.ctactebanco_id', '=', 'c.id')
            ->leftJoin('tipoventa as t', 'p.tipoventa_id', '=', 't.id')
            ->leftJoin('sucursal as s', 'a.sucursal_id', '=', 's.id')
            ->where('p.codigo', $id)
            ->first();
    }

    public static function selectidcmd_id($id){
        return static::selectRaw('p.*, o.codigo cco_cod, a.sucursal_id, a.direccion, s.codigo suc_cod, s.direccion domicilio, c.banco_id, c.moneda_id, t.tipotransaccion_id, t.conkardex, t.movimientotipo_id')
            ->from('puntoventa as p')
            ->leftJoin('centrocosto as o', 'p.centrocosto_id', '=', 'o.id')
            ->leftJoin('almacen as a', 'p.almacen_id', '=', 'a.id')
            ->leftJoin('ctactebanco as c', 'p.ctactebanco_id', '=', 'c.id')
            ->leftJoin('tipoventa as t', 'p.tipoventa_id', '=', 't.id')
            ->leftJoin('sucursal as s', 'a.sucursal_id', '=', 's.id')
            ->where('p.id', $id)
            ->first();
    }

    public static function centrocosto($id){
        return static::select('p.centrocosto_id as id', 'c.codigo', 'c.descripcion')
            ->from('puntoventa as p')
            ->join('centrocosto as c', 'p.centrocosto_id', '=', 'c.id')
            ->where('p.id', $id)
            ->first();
    }
}
