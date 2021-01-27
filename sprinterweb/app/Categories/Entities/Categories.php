<?php

namespace App\Categories\Entities;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
   protected $table = "categactivo";
   public  $timestamps = false;

    public static function listado()
    {
        return static::select('*')
            ->orderBy('codigo', 'asc')
            ->get();
    }
}
