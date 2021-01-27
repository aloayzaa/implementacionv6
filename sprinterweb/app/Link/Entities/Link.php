<?php

namespace App\Link\Entities;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $table = "vinculo";
    public $timestamps = false;

    public static function selectparentcmd($tabla, $id){

        return static::select('*')
        ->where('tablaorigen', $tabla)
        ->where('origen_id', $id)
        ->first();


    }
}
