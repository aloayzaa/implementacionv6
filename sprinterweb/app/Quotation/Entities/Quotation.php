<?php

namespace App\Quotation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quotation extends Model
{
    protected $table = "cotizacion";
    public $timestamps = false;

    public static function referencia_facturacion_desde($desde, $hasta, $cotizacion_id, $serie, $numero, $nombre){
        return DB::select("SELECT c.id, concat(d.codigo, '-', c.numero) as documento, c.fechaproceso as fecha,
				case when ifnull(a.atencion,'') = '' then t.descripcion else a.atencion end as nombre, c.moneda_id, c.tcambio, c.ventana, c.glosa
			FROM cotizacion as c
			left join cotizacion_adicional as a on a.parent_id = c.id
			join tercero as t on c.cliente_id = t.id
			join periodo as d on c.periodo_id = d.id
			where c.fechaproceso between '$desde' and '$hasta' and (c.estado in ('APROBADO','FT.PARCIAL') or c.id = $cotizacion_id)
				and d.codigo like '%$serie%' and c.numero like '%$numero%' and t.descripcion like '%$nombre%'");
    }

    public static function referencia_facturacion_hasta($hasta, $cotizacion_id, $serie, $numero, $nombre){
        return DB::select("SELECT c.id, concat(d.codigo, '-', c.numero) as documento, c.fechaproceso as fecha,
				case when ifnull(a.atencion,'') = '' then t.descripcion else a.atencion end as nombre, c.moneda_id, c.tcambio, c.ventana, c.glosa
			FROM cotizacion as c
			left join cotizacion_adicional as a on a.parent_id = c.id
			join tercero as t on c.cliente_id = t.id
			join periodo as d on c.periodo_id = d.id
			where c.fechaproceso <= '$hasta' and (c.estado in ('APROBADO','FT.PARCIAL') or c.id = $cotizacion_id)
				and d.codigo like '%$serie%' and c.numero like '%$numero%' and t.descripcion like '%$nombre%'");
	}
	
	public static function actulizar_cotizacion_billing($cotizacion_id){
		return static::select(DB::raw("c.total-sum(r.total) as saldo"))
		->from('docxpagar as r')
		->join('cotizacion as c', 'r.cotizacion_id', '=', 'c.id')
		->where('r.cotizacion_id', $cotizacion_id)
		->get();

	}

	public static function selectidcmd($id){
		return static::select(DB::raw("o.*, pe.codigo as periodo, c.codigo as cco_cod, c.descripcion as cco_dsc"))
		->from("cotizacion as o") 
		->leftJoin("centrocosto as c", "o.centrocosto_id", "=", "c.id")
		->join("periodo as pe", "o.periodo_id", "=", "pe.id") 
		->where("o.id", $id)
		->first($id);
	}

	public static function estado_eliminar_facturacion($usuario, $id){
		return static::where('id', $id)
		->update(["estado" => "APROBADO", "usuario" => $usuario]);

	}
}
