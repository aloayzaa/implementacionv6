<?php

namespace App\Marks\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Marca extends Model
{
    protected $table = "marca";
    public $timestamps = false;

    public function modelo()
    {
        return $this->hasMany(Model::class);
    }

    public function producto(){
        return $this->hasMany(Productos::class);
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
