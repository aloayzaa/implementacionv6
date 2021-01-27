<?php

namespace App\Color\Entities;

use App\Products\Entities\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Color extends Model
{
    protected $table = "color";
    public $timestamps = false;

    public function productos(){
        return $this->hasMany(Productos::class);
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }
}
