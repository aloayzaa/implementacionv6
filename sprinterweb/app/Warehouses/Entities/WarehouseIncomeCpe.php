<?php

namespace App\Warehouses\Entities;

use Illuminate\Database\Eloquent\Model;

class WarehouseIncomeCpe extends Model
{
    protected $table = "ingresoalmacen_cpe";
    public $timestamps = false;

    public static function selectparentcmd($id){
        return static::selectRaw("e.*, '09' tipodoc, c.seriedoc, c.numerodoc, c.ventana")
            ->from('ingresoalmacen_cpe as e')
            ->join('ingresoalmacen as c', 'e.ingresoalmacen_id', '=', 'c.id')
            ->where('e.ingresoalmacen_id', $id)
            ->first();
    }
}
