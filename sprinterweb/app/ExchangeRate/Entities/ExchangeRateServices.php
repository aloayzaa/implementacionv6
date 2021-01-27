<?php

namespace App\ExchangeRate\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExchangeRateServices extends Model
{
    protected $connection = 'DB_CONNECTION_SERVICIOS';
    protected $table = "tcambio";
    public $timestamps = false;

    public static function filtravalores($valor)
    {
        return static::select('codigo', 'fecha', 't_compra', 't_venta')
            ->where('codigo', 'like', $valor . '%')
            ->get();
    }

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

    public function scopeCantidadCodigos($query, $codigo){
        return $query->select(DB::raw('count(*) as idCantidad'))
            ->where('codigo', '=', $codigo)
            ->get();
    }

    public static function devuelveIds($variable)
    {
        return static::select('id')
            ->Where('codigo','=',$variable)
            ->get();
    }
}
