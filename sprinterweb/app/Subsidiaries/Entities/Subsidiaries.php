<?php

namespace App\Subsidiaries\Entities;

use App\Warehouses\Entities\WareHouses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subsidiaries extends Model
{
    protected $table = "sucursal";
    public $timestamps = false;

    public function almacen(){
        return $this->hasMany(WareHouses::class, 'sucursal_id')->select(['id', 'descripcion']);
    }

    public function tercero(){
        return $this->hasMany(Customer::class);
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }
}
