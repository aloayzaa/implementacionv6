<?php

namespace App\Deductions\Entities;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $table = "tipodetraccion";
    public $timestamps = false;


    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->whereRaw("(descripcion LIKE '%".$term."%' or codigo LIKE '%".$term."%')")
            ->get();
    }

}
