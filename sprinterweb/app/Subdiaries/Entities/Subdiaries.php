<?php

namespace App\Subdiaries\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subdiaries extends Model
{
    protected $table = "subdiario";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
    public static function select3(){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->get();
    }
}
