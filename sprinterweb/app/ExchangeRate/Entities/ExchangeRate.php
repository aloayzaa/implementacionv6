<?php

namespace App\ExchangeRate\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExchangeRate extends Model
{
    protected $table = "tcambio";
    public $timestamps = false;

    public static function ultimo_registro()
    {
        return static::select('t_compra', 't_venta')
            ->orderBy('fecha', 'desc')
            ->limit(1)
            ->first();
    }

    public static function idCantidad($res)
    {
        return static::select(DB::raw('count(*) as idCantidad'))
            ->where('codigo', '=', $res)
            ->first();
    }

    public static function filtravalores($codigo)
    {
        return static::select('t_compra', 't_venta', 'estado', 'codigo', 'id', DB::raw('DATE_FORMAT(fecha, "%d-%m-%Y") as fecha'))
            ->where('codigo', 'like', $codigo . '%')
            ->get();
    }

    public static function devuelveId($variable)
    {
        return static::select('id')
            ->Where('codigo', '=', $variable)
            ->first();
    }

    public static function buscar_cambio($codigo)
    {
        return static::select('*')
            ->Where('codigo', '=', $codigo)
            ->first();
    }
}
