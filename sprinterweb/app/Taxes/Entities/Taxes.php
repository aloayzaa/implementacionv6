<?php

namespace App\Taxes\Entities;

use App\Customers\Entities\Customer;
use App\PensionType\Entities\PensionType;
use App\PurchaseDocument\Entities\PurchaseDocument;
use App\PurchaseOrder\Entities\PurchaseOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Taxes extends Model
{
    protected $table = "impuesto";
    public $timestamps = false;

    public function tiporegpension()
    {
        return $this->belongsTo(PensionType::class, 'tiporegpension_id');
    }

    public function documento()
    {
        return $this->belongsTo(PurchaseDocument::class, 'documento_id');
    }

    public function tercero()
    {
        return $this->belongsTo(Customer::class, 'tercero_id');
    }

    public function ordencompra1(){
        return $this->hasMany(PurchaseOrder::class,'impuesto_id');
    }

    public function ordencompra2(){
        return $this->hasMany(PurchaseOrder::class,'impuesto2_id');
    }

    public static function crsimpuesto()
    {
        return static::select('*')
            ->where('valor', '=', 0)
            ->orWhere(function ($query) {
                $query->where('retencion', '=', 0)
                    ->where('iva', '=', 1);
            })->get();
    }

    public static function crsimpuesto2()
    {
        return static::select('*')
            ->where('iva', '=', 0)
            ->where('restabase', '=', 0)
            ->where('retencion', '=', 0)
            //->whereNull('vigentehasta')
            ->get();
    }

    public static function crsimpuesto3()
    {
        return static::select('*')
            ->where(DB::raw("ifnull(tiporegpension_id,0)"), 0)
            ->Where(function ($query) {
                $query->where('valor', '=', 0)
                    ->orWhere('restabase', '=', 1);
            })->get();
    }

    public static function crsimpuesto4()
    {
        return static::select('*')
            ->where('retencion', 1)
            ->orWhere('valor', 0)
            ->Where(function ($query) {
                $query->where('valor', '=', 0)
                    ->orWhere('restabase', '=', 1);
            })->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function select3(){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->get();
    }
}
