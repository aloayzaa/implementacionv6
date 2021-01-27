<?php

namespace App\MovementTypes\Entities;

use App\Warehouses\Entities\WarehouseIncome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MovementType extends Model
{
    protected $table = "movimientotipo";
    public $timestamps = false;

    public function scopeBuscar($query, $txtBusca)
    {
            if(trim($txtBusca)!="")
            {
                $query->where('descripcion', 'LIKE',"%$txtBusca%")
                ->orWhere('codigo', 'LIKE',"%$txtBusca%")
                ->orWhere('codsunat', 'LIKE',"%$txtBusca%")   ;
            }
    }

    public static function findByCodigoOrDescription($term)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public static function findByCodigoOrDescriptionIng($term)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',1)
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public static function findByCodigoOrDescriptionIngGrupo($term,$var)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',1)
            ->Where('grupo','=',$var)
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public static function findByCodigoOrDescriptionSal($term)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',-1)
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public static function findByCodigoOrDescriptionSalGrupo($term,$var)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',-1)
            ->Where('grupo','=',$var)
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public static function findByCodigoOrDescriptionTrans($term,$factor)
    {
        return static::select('codigo','id','descripcion',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',$factor)
            ->Where('grupo','=','R')
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public static function movimientoVenta($id)
    {
        return static::select('movimientotipo.id','movimientotipo.codigo','movimientotipo.descripcion as descripcion_movimiento','movimientotipo.grupo','movimientotipo.factor','movimientotipo.tipocalculo','movimientotipo.tipocambio','movimientotipo.codsunat','movimientotipo.pidevalor','v.id as id_venta','v.descripcion as descripcion_venta')
            ->join('tipoventa as v', 'v.id', '=', 'movimientotipo.tipoventa_id','left')
            ->where('movimientotipo.id','=',$id)
            ->get()->first();
    }

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

    public static function lista_search($codigo,$descripcion)
    {
        $term=$codigo." ".$descripcion;
        return static::select('codigo','id','descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(50)
            ->get();
    }

    public static function lista_searchfactor($codigo,$descripcion,$parametro)
    {
        $term=$codigo." ".$descripcion;
        return static::select('codigo','id','descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',$parametro)
            ->Where('estado','!=',0)
            ->limit(50)
            ->get();
    }

    public static function lista_searchgrup($codigo,$descripcion,$parametro,$grupo)
    {
        $term=$codigo." ".$descripcion;
        return static::select('codigo','id','descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('factor','=',$parametro)
            ->Where('grupo','=',$grupo)
            ->Where('estado','!=',0)
            ->limit(50)
            ->get();
    }

    public static function filtroDescrip($id)
    {
        return static::select('codigo','descripcion', DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where('id','=',$id)
            ->get() ->first();
    }

    public function pedidoAlmacen(){
        return $this->hasMany(PedidoAlmacen::class);
    }

    public function ingresoAlmacen(){
        return $this->hasMany(WarehouseIncome::class);
    }

    public function inventario(){
        return $this->hasMany(Inventario::class);
    }

    public function documentoActivo(){
        return $this->hasMany(DocumentoActivo::class);
    }

    public function scopeGrupos(){
        return collect([
            ['desc' => 'Compra', 'value' => 'C'],
            ['desc' => 'Venta', 'value' => 'V'],
            ['desc' => 'Transferencia', 'value' => 'T'],
            ['desc' => 'Consumo', 'value' => 'M'],
            ['desc' => 'Transformación', 'value' => 'R'],
            ['desc' => 'Bajas/Desmedros', 'value' => 'B'],
            ['desc' => 'Donación', 'value' => 'D'],
            ['desc' => 'Otro', 'value' => 'O'],
        ]);
    }
    public function scopeMovimientos(){
        return collect([
            ['desc' => 'Ingreso', 'value' => 1],
            ['desc' => 'Salida', 'value' => -1],
        ]);
    }
    public function scopeCalculos(){
        return collect([
            ['desc' => 'Importes', 'value' => 'I'],
            ['desc' => 'Cantidad', 'value' => 'C'],
            ['desc' => 'Ambos', 'value' => 'A'],
        ]);
    }
    public function scopeCambios(){
        return collect([
            ['desc' => 'Compra', 'value' => 'C'],
            ['desc' => 'Venta', 'value' => 'V'],
        ]);
    }

    public static function select3(){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->get();
    }
}
