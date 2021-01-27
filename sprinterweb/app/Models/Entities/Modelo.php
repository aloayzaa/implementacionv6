<?php

namespace App\Models\Entities;

use App\Marks\Entities\Marca;
use App\Products\Entities\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Modelo extends Model
{
    protected $table = "modelo";
    public $timestamps = false;

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function producto(){
        return $this->hasMany(Productos::class);
    }

    public static function filtroId($id)
    {
        return static::select('id', 'codigo', 'descripcion', 'nombreComercial', 'estado')
            ->Where('marca_id', '=', $id)
            ->get();
    }

    public static function idCantidad($res)
    {
        return static::select(DB::raw('count(*) as idCantidad'))
            ->where('id', '=', $res)
            ->get()
            ->first();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function select2($term,$marca_id){
        return static::select('id','codigo','descripcion')
            ->where('marca_id',$marca_id)
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }
    public static function update_data($id, $codigo, $data)
    {
        return static::where('marca_id', $id)
            ->where('codigo', $codigo)
            ->update($data);
    }
}
