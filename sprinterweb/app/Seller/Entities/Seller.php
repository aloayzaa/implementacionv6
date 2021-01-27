<?php

namespace App\Seller\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Seller extends Model
{
    protected $table = "vendedor";
    public $timestamps = false;

    public function tercero(){
        return $this->hasMany(Customer::class);
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }
}
