<?php

namespace App\PaymentCondition\Entities;

use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentCondition extends Model
{
    protected $table = "condicionpago";
    public $timestamps = false;

    public function documentosPorPagar(){
        return $this->hasMany(DocumentToPay::class);
    }

    public function tercero(){
        return $this->hasMany(Customer::class);
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion')
            ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->get();
    }

}
