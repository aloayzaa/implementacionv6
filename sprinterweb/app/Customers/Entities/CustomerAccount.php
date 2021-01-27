<?php

namespace App\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class CustomerAccount extends Model
{
    protected $table = "tercero_cuenta";
    public $timestamps = false;

    public function banco(){
        return $this->belongsTo(Bank::class,'banco_id');
    }

    public static function update_data($tercero_id, $item, $data){
        return static::where('tercero_id', $tercero_id)
            ->where('item', $item)
            ->update($data);
    }

    public static function data_depositar_en($tercero_id){
        return static::select('t.*','b.codigo as banco_codigo','m.simbolo as moneda_simbolo')
            ->from('tercero_cuenta as t')
            ->join('banco as b','t.banco_id','=','b.id')
            ->join('moneda as m','t.moneda_id','=','m.id')
            ->where('t.tercero_id',$tercero_id)
            ->get();
    }

    public static function selectparentcmd($tercero_id){ // clasic, clase: tercero_cuenta, método: selectparentcmd
        return static::select(DB::raw("c.*, b.codigo, b.descripcion, m.codigo as moneda, m.descripcion as mon_dsc"))
        ->from("tercero_cuenta as c")
        ->join("banco as b", "c.banco_id", "=", "b.id")
        ->join("moneda as m", "c.moneda_id", "=", "m.id")
        ->where("c.tercero_id", $tercero_id)
        ->first();
    } 

    public static function selectparentcmd_codigo($tercero_codigo){ // clasic, clase: tercero_cuenta, método: selectparentcmd
        return static::select(DB::raw("c.*, b.codigo, b.descripcion, m.codigo as moneda, m.descripcion as mon_dsc"))
        ->from("tercero_cuenta as c")
        ->join("tercero as t", "c.tercero_id", "=", "t.id")
        ->join("banco as b", "c.banco_id", "b.id")
        ->join("moneda as m", "c.moneda_id", "=", "m.id")
        ->where("t.codigo", $tercero_codigo)
        ->first();

    }
}
