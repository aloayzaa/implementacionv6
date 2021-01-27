<?php

namespace App\SalesTypes\Entities;

use App\AccountingPlan\Entities\AccountingPlan;
use App\DocumentToPay\Entities\DocumentToPay;
use App\MovementTypes\Entities\MovementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaleType extends Model
{
    protected $table = "tipoventa";
    public $timestamps = false;

    public function accountSale()
    {
        return $this->belongsToMany(AccountingPlan::class, 'tipoventaxfamilia', 'tipoventa_id', 'cuenta_id');
    }

    public function documentosPorPagar(){
        return $this->hasMany(DocumentToPay::class);
    }

    public function tipomovimiento(){
        return $this->belongsTo(MovementType::class,'movimientotipo_id');
    }

    //por checkear las que estan en español
    public function documentosPorCobrar(){
        return $this->hasMany(DocumentoPorCobrar::class);
    }

    public function tipoTransaccion(){
        return $this->belongsTo(TransactionType::class,'tipotransaccion_id');
    }

    public function subdiario(){
        return $this->belongsTo(Subdiario::class,'subdiario_id');
    }

    public function tipoDocumento(){
        return $this->belongsTo(DocumentoCompra::class,'documento_id');
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
                $query->where('descripcion', 'LIKE',"%$txtBusca%");
            }
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

    public static function get_id($codigo){
        return static::select('id')
        ->where('codigo',$codigo)
        ->first();
    }

}
