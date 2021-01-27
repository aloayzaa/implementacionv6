<?php

namespace App\Products\Entities;

use App\Warehouses\Entities\WareHouses;
use Illuminate\Database\Eloquent\Model;

class ProductoUbicacion extends Model
{
    protected $table = "producto_ubicacion";
    public $timestamps = false;

    public function almacen(){
        return $this->belongsTo(WareHouses::class,'almacen_id');
    }

    public static function update_data($id, $data)
    {
        return static::where('id', $id)
            ->update($data);
    }
}
