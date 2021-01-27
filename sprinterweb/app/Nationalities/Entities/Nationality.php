<?php

namespace App\Nationalities\Entities;

use App\Customers\Entities\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Nationality extends Model
{
    protected $table = "nacionalidad";
    public $timestamps = false;

    public function tercero(){
        return $this->hasMany(Customer::class);
    }
    public static function findByCodigoOrDescription($term)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
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
    public function terceros(){
        return $this->hasMany(Customer::class);
    }

    public function documentosxPagarNoDomiciliado(){
        return $this->hasMany(DocxPagarNoDomiciliado::class);
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }
}
