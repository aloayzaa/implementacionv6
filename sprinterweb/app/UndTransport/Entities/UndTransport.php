<?php

namespace App\UndTransport\Entities;

use Illuminate\Database\Eloquent\Model;

class UndTransport extends Model
{
    protected $table = "undtransporte";
    public $timestamps = false;

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->whereRaw("(descripcion LIKE '%".$term."%' or codigo LIKE '%".$term."%')")
            ->get();
    }

    public static function selectvincmd($undtransporte_id){
        
        return static::select(DB::raw("u.id, u.codigo, u.descripcion, u.anio, u.aniomodelo, u.nromotor, u.nrovin, u.nrochasis, u.version, u.placa, v.*, 
        ifnull(b.descripcion,'') as tipocombustible,m.codigo as mca_cod, m.descripcion as mca_dsc, d.codigo as mod_cod, d.descripcion as mod_dsc, 
        d.nombrecomercial as mod_com, c.codigo as col_cod, c.descripcion as col_dsc, u.observaciones,
        t.codigo as tca_cod, t.descripcion as tca_dsc, p.codigo as pais_cod, p.descripcion as pais_dsc, tv.codigo as cat_cod, tv.descripcion as cat_dsc"))
        ->from("undtransporte as u")
        ->leftJoin("nacionalidad as p", "u.pais_id", "=", "p.id")
        ->leftJoin("marca as m", "u.marca_id", "=", "m.id")
        ->leftJoin("modelo as d", "u.modelo_id", "=", "d.id")
        ->leftJoin("modelovehiculo as v", "u.modelo_id", "=", "v.modelo_id")
        ->leftJoin("color as c", "u.color_id", "=", "c.id")
        ->leftJoin("tipocarroceria as t", "v.tipocarroceria_id", "=", "t.id")
        ->leftJoin("tipovehiculo as tv", "v.tipovehiculo_id", "=", "tv.id")
        ->leftJoin("tipocombustible as b", "v.tipocombustible_id", "=", "b.id")
        ->where("u.nrovin", $undtransporte_id)
        ->first();

    }
}
