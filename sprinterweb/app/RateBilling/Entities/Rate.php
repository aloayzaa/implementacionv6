<?php

namespace App\RateBilling\Entities;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = "tarifario";
    public $timestamps = false;


    public static function getpreciosuc($sucursal, $producto_id, $umedida_id){  // clasic, clase: tarifario, método: getpreciosuc

        if ( $umedida_id == 0 ) {

            return static::select('precio', 'precio2', 'precio3', 'precio4', 'precio5', 'precio6', 'umedida_id')
            ->from('tarifario')
            ->where('sucursal_id', $sucursal)
            ->where('producto_id', $producto_id)
            ->first();

        } else {

            return static::select('precio', 'precio2', 'precio3', 'precio4', 'precio5', 'precio6', 'umedida_id')
            ->from('tarifario')
            ->where('sucursal_id', $sucursal)
            ->where('producto_id', $producto_id)
            ->where('umedida_id', $umedida_id)
            ->first();

        }

    }

}
