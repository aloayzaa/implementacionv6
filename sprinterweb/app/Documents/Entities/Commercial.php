<?php

namespace App\Documents\Entities;

use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Commercial extends Model
{
    protected $table = "documentocom";
    public $timestamps = false;


    public function documentosPorPagar(){
        return $this->hasMany(DocumentToPay::class);
    }

    public static function opening()
    {
        return static::where('id', 2)
            ->orWhere('id', 4)
            ->orWhere('id', 6)
            ->orWhere('id', 8)
            ->orWhere('id', 9)
            ->orWhere('id', 13)
            ->orWhere('id', 14)
            ->orWhere('id', 15)
            ->orWhere('id', 16)
            ->orWhere('id', 21)
            ->orWhere('id', 22)
            ->get();
    }

    public static function crsdocumentocom($formulario)
    {
        return DB::select("select * from documentocom where id in (select documentocom_id from frmconfig_td where formulario='" . $formulario . "')");
    }

    public static function crsdocumentocom2($formulario){
        return DB::select("select * from documentocom where id in (select documentocom_id from frmconfig_td where formulario='".$formulario."')");
    }

    public static function select3(){
        return static::select('id','codigo','descripcion')
            ->where('estado', 1)
            ->get();
    }

    public static function selectfrmcmd($formulario){ //MOV_FACTURAVENTA - método Load - crsdocumentocom
        return DB::select("select * from documentocom where id in (select documentocom_id from frmconfig_td where formulario = '$formulario')");
    }
}
