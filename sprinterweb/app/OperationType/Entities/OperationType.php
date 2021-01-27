<?php

namespace App\OperationType\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OperationType extends Model
{
    protected $table = "tipooperacion";
    public $timestamps = false;


    public function scopeTipos(){
        return collect([
            ['desc' => 'Ingreso', 'value' => "I"],
            ['desc' => 'Egreso', 'value' => "E"],
            ['desc' => 'Ninguno', 'value' => ""],
        ]);
    }

    public function scopeOrigenes(){
        return collect([
            ['desc' => 'Cobrar', 'value' => "C"],
            ['desc' => 'Pagar', 'value' => "P"],
        ]);
    }

    public static function listaIngresos()
    {
        return static::where('tipo', 'I')->get();
    }

    public static function listaEgresos()
    {
        return static::where('tipo', 'E')->get();
    }

    public static function select3(){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->get();
    }
}
