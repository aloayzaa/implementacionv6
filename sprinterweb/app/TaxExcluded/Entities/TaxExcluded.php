<?php

namespace App\TaxExcluded\Entities;

use Illuminate\Database\Eloquent\Model;

class TaxExcluded extends Model
{
    protected $connection = "DB_CONNECTION_SERVICIOS";
    protected $table = "renexonigv";
    public $timestamps = false;

    public static function buscar_codigo($ruc)
    {
        return static::select('*')
            ->Where('codigo', '=', $ruc)
            ->first();
    }
}
