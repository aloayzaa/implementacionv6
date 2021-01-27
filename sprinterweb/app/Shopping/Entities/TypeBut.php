<?php

namespace App\Shopping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypeBut extends Model
{
    protected $table = "tipocompra";
    public $timestamps = false;

    public static function anular($id,$parametro)
    {
        return static::where('id',$id)
            ->update(['estado' => $parametro]);
    }

    public static function activar($id,$parametro)
    {
        return static::where('id',$id)
            ->update(['estado' => $parametro]);
    }

    public static function findByCodigoOrDescription($term)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public function scopeBuscar($query, $txtBusca)
    {
        if(trim($txtBusca)!="")
        {
            $query->where('descripcion', 'LIKE',"%$txtBusca%")
                ->orWhere('codigo', 'LIKE',"%$txtBusca%")
                ->where('estado','!=',0);
        }
    }
    public function documentosPorPagar(){
        return $this->hasMany(DocumentoPorPagar::class);
    }

    public function tipoTransaccion(){
        return $this->belongsTo(TransactionType::class,'tipotransaccion_id');
    }

    public function subdiario(){
        return $this->belongsTo(Subdiario::class,'subdiario_id');
    }
    public static function lista_search($codigo,$descripcion)
    {
        $term=$codigo." ".$descripcion;
        return static::select('codigo','id','descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(50)
            ->get();
    }
}
