<?php

namespace App\Period\Entities;

use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Period extends Model
{
    protected $table = "periodo";
    public $timestamps = false;

    public function docxpagar(){
        return $this->hasMany(DocumentToPay::class);
    }

    public static function lista_search($codigo, $descripcion)
    {
        $term = $codigo . " " . $descripcion;
        return static::select('*')
            ->Where(DB::raw('concat(codigo," ",descripcion)'), 'LIKE', "%$term%")
            ->Where('estado', '!=', 0)
            ->get();
    }

    public static function lista_search_x_anio($anio)
    {
        return static::select('*')
            ->Where(DB::raw('YEAR(inicio)'), '=', $anio)
            ->Where(DB::raw('YEAR(final)'), '=', $anio)
            ->Where('estado', '!=', 0)
            ->limit(50)
            ->get();
    }

    public static function abrir_cerrar($id, $modulo, $parametro)
    {
        return static::where('id', $id)
            ->update([$modulo => $parametro]);
    }

    public static function obtener_periodo($finicio, $ffin)
    {
        return static::select('*')
            ->where('inicio', '>=', $finicio)
            ->where('final', '<=', $ffin)
            ->Where('estado', '!=', 0)
            ->first();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
