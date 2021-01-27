<?php

namespace App\TransactionType\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionType extends Model
{
    protected $table = "tipotransaccion";
    public $timestamps = false;

    public static function consulta_devolver($item){
        return static::select(DB::raw("id, codigo, descripcion"))->whereRaw('estado = 1 '.$item)->get();
    }
}
