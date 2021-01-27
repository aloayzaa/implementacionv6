<?php

namespace App\Tarifariosuc\Entities;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Ratsuc extends Model
{
    protected $table = "tarifario";
    public $timestamps = false;

    public static  function selectparentcmd(){   // clase tarifario cursor crstarifario - clasic
        return static::select(DB::raw("t.*, s.codigo as suc_cod, s.descripcion as suc_dsc, p.codigo, p.descripcion, u.codigo as ume_cod, m.codigo as moneda,
		ifnull(ma.codigo,'')as marca_cod, ifnull(ma.descripcion,'')as marca_dsc"))
            ->from("tarifario as t")
            ->join("sucursal as s", "t.sucursal_id","=","s.id")
            ->join("producto as p", "t.producto_id","=","p.id")
            ->leftJoin("marca as ma","p.marca_id","=","ma.id")
            ->join("umedida as u", "t.umedida_id","=","u.id")
            ->join("moneda as m", "t.moneda_id","=","m.id")
            ->get();

    }

    public static  function editar_tarifario_sucursal($id){   // clase tarifario cursor crstarifario - clasic
        return static::select(DB::raw("t.*, p.id as producto_id, p.codigo as producto_codigo, p.descripcion as producto_descripcion"))
            ->from('tarifario as t')
            ->join("producto as p", "t.producto_id","=","p.id")
            ->where('t.id', $id)
            ->first();
    }
}
