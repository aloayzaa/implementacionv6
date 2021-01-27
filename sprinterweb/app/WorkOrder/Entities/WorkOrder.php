<?php

namespace App\WorkOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class WorkOrder extends Model
{
    protected $table = "ordentrabajo";
    public $timestamps = false;

    public static function ordentrabajo($fechainicio, $fechafin)
    {
        return static::select('*', 't.descripcion as tercero_descripcion', 'op.id as id')
            ->from('ordentrabajo as op')
            ->leftjoin('tercero as t', 't.id', '=', 'op.tercero_id')
            ->whereBetween('fechaproceso', [$fechainicio, $fechafin])
            ->get();
    }

    public static function referencia_facturacion_desde($desde, $hasta, $estado, $cotizacion_id, $numero, $nombre){
        return DB::select("SELECT c.id, c.nromanual as documento, c.fechaproceso as fecha, t.descripcion as nombre,
					ifnull(a.moneda_id,1) moneda_id, c.tcambio, c.ventana, c.glosa
				FROM ordentrabajo as c
				left join cotizacion as a on c.cotizacion_id = a.id
				join tercero as t on c.tercero_id = t.id
				where c.fechaproceso between '$desde' and '$hasta' and (c.estado in " . $estado . " or c.id = $cotizacion_id)
					and c.nromanual like '%$numero%' and t.descripcion like '%$nombre%'");
    }

    public static function referencia_facturacion_hasta($hasta, $estado, $cotizacion_id, $numero, $nombre){
        return DB::select("SELECT c.id, c.nromanual as documento, c.fechaproceso as fecha, t.descripcion as nombre,
					ifnull(a.moneda_id,1) moneda_id, c.tcambio, c.ventana, c.glosa
				FROM ordentrabajo as c
				left join cotizacion as a on c.cotizacion_id = a.id
				join tercero as t on c.tercero_id = t.id
				where c.fechaproceso <= '$hasta' and (c.estado in " . $estado . " or c.id = $cotizacion_id)
					and c.nromanual like '%$numero%' and t.descripcion like '%$nombre%'");
    }

    public static function referencia_facturacion_desde_cotizacion($desde, $hasta, $cotizacion_id, $numero, $nombre, $tercero_id){
        return DB::select("SELECT c.id, c.nromanual as documento, c.fechaproceso as fecha, t.descripcion as nombre,
					ifnull(a.moneda_id,1) moneda_id, c.tcambio, c.ventana, c.glosa
				FROM ordentrabajo as c
				left join cotizacion as a on c.cotizacion_id = a.id
				join tercero as t on c.tercero_id = t.id
				where c.fechaproceso between '$desde' and '$hasta' and c.estado = 'ACTIVO' and (a.id is null or c.cotizacion_id = $cotizacion_id)
					and c.nromanual like '%$numero%' and t.descripcion like '%$nombre%' and c.tercero_id = $tercero_id");
    }

    public static function referencia_facturacion_hasta_cotizacion($hasta, $contizacion_id, $numero, $nombre, $tercero_id){
        return DB::select("SELECT c.id, c.nromanual as documento, c.fechaproceso as fecha, t.descripcion as nombre,
					ifnull(a.moneda_id,1) moneda_id, c.tcambio, c.ventana, c.glosa
				FROM ordentrabajo as c
				left join cotizacion as a on c.cotizacion_id = a.id
				join tercero as t on c.tercero_id = t.id
				where c.fechaproceso <= '$hasta' and c.estado = 'ACTIVO'  and (a.id is null or c.cotizacion_id = $contizacion_id)
					and c.nromanual like '%$numero%' and t.descripcion like '%$nombre%' and c.tercero_id = $tercero_id");
	}
	
	public static function selectidcmd($id){
		
		return static::select(DB::raw("o.*, pe.codigo as periodo, c.codigo as cco_cod, c.descripcion as cco_dsc, convert(concat('OT ', p.codigo, '-', o.numero) using latin1) as otrabajo,
		p.codigo as prd_cod, p.descripcion as prd_dsc, p.umedida_id, p.tipoproducto, t.codigo as ter_cod, t.descripcion as ter_dsc, r.validastock"))
		->from("ordentrabajo as o") 
		->leftJoin("producto as p", "o.producto_id", "=", "p.id")
		->leftJoin("centrocosto as c", "o.centrocosto_id", "=", "c.id")
		->join("tercero as t", "o.tercero_id", "=", "t.id")
		->join("periodo as pe", "o.periodo_id", "=", "pe.id") 
		->leftJoin("tipoorden as r", "o.tipoorden_id", "=", "r.id")
		->where("o.id",$id)
		->first();

	}

	public static function getdetotfactura($id){

		return DB::select('CALL getdetotfactura(?)',array($id));

	}

	public static function estado_eliminar_facturacion($usuario, $id){
		return static::where('id', $id)
		->update(["estado" => "CERRADO", "usuario" => $usuario]);

	}

}