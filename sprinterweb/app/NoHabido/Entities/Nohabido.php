<?php

namespace App\NoHabido\Entities;

use Illuminate\Database\Eloquent\Model;

class Nohabido extends Model
{
    protected $connection = "DB_CONNECTION_SERVICIOS";
    protected $table = "nohabido";
    public $timestamps = false;

    public static function lista_nohabidos()
    {
        return static::select('*')
            ->limit(5000)
            ->get();
    }

    public static function buscar_codigo($ruc)
    {
        return static::select('*')
            ->Where('codigo', '=', $ruc)
            ->first();
    }
}
