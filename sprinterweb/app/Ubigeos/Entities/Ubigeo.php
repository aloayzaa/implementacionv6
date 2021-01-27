<?php

namespace App\Ubigeos\Entities;

use App\Customers\Entities\Customer;
use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $table = "ubigeo";
    public $timestamps = false;

    public function terceros()
    {
        return $this->hasMany(Customer::class);
    }

    public static function devuelveId($variable)
    {
        return static::select('id', 'descripcion', 'completo')
            ->Where('codigo', '=', "$variable")
            ->get()
            ->first();
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where('descripcion', 'LIKE',"%$term%")
            ->orWhere('codigo', 'LIKE',"%$term%")
            ->get();
    }
}
