<?php

namespace App\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerAddress extends Model
{
    protected $table = "tercero_direccion";
    public $timestamps = false;

    public static function tercero_sucursal($id)
    {
        return static::select(
            'tercero_direccion.tercero_id',
            'tercero_direccion.item',
            'tercero_direccion.via_nombre',
            'tercero_direccion.ubigeo_id',

            'ub.id as id_ub',
            'ub.codigo as codigo_ub',
            'ub.descripcion as descripcion_ub',
            'ub.completo as completo_ub')

            ->join('ubigeo as ub', 'ub.id', '=', 'tercero_direccion.ubigeo_id', 'left')
            ->where('tercero_direccion.tercero_id', '=', $id)
            ->get();
    }

    public static function idCantidad($tercero_id, $item)
    {
        return static::select(DB::raw('count(*) as cantidad'))
            ->where('tercero_id', '=', $tercero_id)
            ->where('item', '=', $item)
            ->get()
            ->first();
    }

    public static function editar($tercero_id, $ubigeo_id, $direccion)
    {
        return static::where('tercero_id', $tercero_id)
            ->where('ubigeo_id', $ubigeo_id)
            ->update(['via_nombre'=> $direccion]);
    }

    public static function anular($id, $item, $parametro)
    {
        return static::where('id', $id)
            ->where('item', $item)
            ->update(['estado' => $parametro]);
    }

    public static function update_data($tercero_id, $item, $data){
        return static::where('tercero_id', $tercero_id)
            ->where('item', $item)
            ->update($data);
    }

    public static function locales_anexos($tercero_id){
        return static::select('td.*','u.codigo')
            ->from('tercero_direccion as td')
            ->join('ubigeo as u', 'td.ubigeo_id','=','u.id')
            ->where('td.tercero_id', $tercero_id)
            ->get();
    }

    public static function busquedacmd($tercero_id){  // clasic, clase: tercero_direccion, método: busquedacmd
        return static::select(DB::raw("t.item as codigo, t.via_nombre as descripcion, ifnull(u.id,0) ubigeo_id, ifnull(u.descripcion,'') as ubigeo_dsc"))
        ->from("tercero_direccion as t")
        ->leftJoin("ubigeo as u", "t.ubigeo_id", "=", "u.id")
        ->where('t.tercero_id', $tercero_id)
        ->get();
    }
}
