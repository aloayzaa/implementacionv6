<?php

namespace App\Families\Entities;

use App\CostByFamily\CostByFamily;
use App\Products\Entities\ProductoSunat;
use App\SalesTypes\Entities\FamilySaleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Familia extends Model
{
    protected $table = "familiapdto";
    public $timestamps = false;

    //no quitar
    public function ccosto_family(){
        return $this->hasMany(CostByFamily::class, 'familiapdto_id');
    }

    public function tipoventa()
    {
        return $this->hasMany(FamilySaleType::class, 'familiapdto_id');
    }

    public function productosunat()
    {
        return $this->belongsTo(ProductoSunat::class,"productosunat_id");
    }

    public function scopeBuscar($query, $txtBusca)
    {
        if (trim($txtBusca) != "") {
            //if ($cont==1) {
            $query->where('descripcion', 'LIKE', "%$txtBusca%")
                ->orWhere('codigo', 'LIKE', "%$txtBusca%")
                ->orWhere('codsunat', 'LIKE', "%$txtBusca%");
            /*}
            else{
                $query->where('descripcion', 'LIKE',"$txtBusca%")
                ->orWhere('codigo', 'LIKE',"$txtBusca%")
                ->orWhere('codsunat', 'LIKE',"$txtBusca%");
            }*/
        }
    }

    public static function filtrar_familias($tam)
    {
        return static::select('*')
            ->where(DB::raw('CHARACTER_LENGTH(codigo)'), '=', $tam)
            ->get();
    }

    public static function ver_familias_sin_tventa($familias)
    {
        return static::select('*')
            ->from('familiapdto')
            ->orderBy('codigo', 'asc')
            ->where(DB::raw('LENGTH(codigo)'), 5)
//            ->whereNotIn('id', $familias)
            ->whereIn('id', $familias)
            ->get();

    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function activar($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function findByCodigoOrDescription($term)
    {
        return static::select('codigo', 'id', 'descripcion', DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'), 'LIKE', "%a%")
            ->Where('estado', '!=', 0)
            ->limit(15)
            ->get();
    }

    public static function findByCodigo($term, $tamanio)
    {
        return static::select('codigo', 'id', 'descripcion', DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'), 'LIKE', "%$term%")
            ->where(DB::raw('CHARACTER_LENGTH(codigo)'), '=', $tamanio)
            ->Where('estado', '!=', 0)
            ->limit(15)
            ->get();
    }

    public static function lista_search($codigo, $descripcion)
    {
        $term = $codigo . " " . $descripcion;
        return static::select('codigo', 'id', 'descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'), 'LIKE', "%$term%")
            ->Where('estado', '!=', 0)
            ->limit(50)
            ->get();
    }

    public static function listado()
    {
        return static::select('*')
            ->orderBy('codigo', 'asc')
            ->get();
    }

    public static function cantidad_registros()
    {
        return static::select(DB::raw('count(*) as cantidad'))
            ->get()
            ->first();
    }

    public static function selectidcmd($id){ // VER CLASIC
 
        return static::select(DB::raw("f.*, ifnull(t.codigo,'') as tor_cod, ifnull(t.descripcion,'') as tor_dsc, ifnull(s.codigo,'') as psu_cod, ifnull(s.descripcion,'') as psu_dsc"))
        ->from("familiapdto as f")
        ->leftJoin("producto_sunat as s", "f.productosunat_id", "=", "s.id")
        ->leftJoin("tipoorden as t", "f.tipoorden_id", "=", "t.id")
        ->where("f.id", $id)
        ->first();
        
    } 

}
