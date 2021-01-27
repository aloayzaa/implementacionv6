<?php

namespace App\Products\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductoSunat extends Model
{
    protected $table="producto_sunat";
    public $timestamps = false;

    public function producto(){
        return $this->hasMany(Productos::class);
    }
    public static function sunat_products_eight_digits($codigo){
        return static::select('*')
            ->from('producto_sunat')
            ->where('codigo', 'like', $codigo.'%')
            ->orwhere('descripcion', 'like', $codigo.'%')
            ->Where('estado','1')
            ->get();
    }

}
