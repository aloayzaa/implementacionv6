<?php

namespace App\ProductGroup\Entities;

use App\Products\Entities\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductGroup extends Model
{
    protected $table = "grupoproducto";
    public $timestamps = false;

    public function producto(){
        $this->hasMany(Productos::class,'grupoproducto_id');
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }
}
