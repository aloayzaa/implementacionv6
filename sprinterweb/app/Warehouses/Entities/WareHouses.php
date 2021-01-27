<?php

namespace App\Warehouses\Entities;

use App\Products\Entities\ProductoUbicacion;
use App\Subsidiaries\Entities\Subsidiaries;
use Illuminate\Database\Eloquent\Model;

class WareHouses extends Model
{
    protected $table = "almacen";
    public $timestamps = false;

    public function producto_ubicacion(){
        return $this->hasMany(ProductoUbicacion::class);
    }

    public function sucursal(){
        return $this->belongsTo(Subsidiaries::class);
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
