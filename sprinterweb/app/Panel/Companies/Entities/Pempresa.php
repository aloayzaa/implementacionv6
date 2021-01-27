<?php

namespace App\Panel\Companies\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pempresa extends Model
{
    protected $table = "pempresa";
    public $timestamps = false;

    public static function get_parametro($param){
        return static::select('valor')
        ->where('parametro',$param)
        ->first();
    }
    public static function existe($parametro)
    {
        return static::select(DB::raw('count(*) as coincidencias'))
            ->where('parametro','=',$parametro)
            ->get()->first();
    }
    public static function actualizar($parametro,$valor,$usuario){
        return static::where('parametro',$parametro)
            ->update(['valor' => $valor,'usuario' => $usuario]);
    }
}
