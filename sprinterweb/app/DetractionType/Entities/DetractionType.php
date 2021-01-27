<?php

namespace App\DetractionType\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetractionType extends Model
{
    protected $table = "tipodetraccion";
    public $timestamps = false;

    public static function findByCodigoOrDescription($term)
    {
        return static::select('codigo','valor','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
//            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->orderBy('codigo','asc')
            ->orderBy('descripcion','asc')
            ->limit(15)
            ->get();
    }
}
