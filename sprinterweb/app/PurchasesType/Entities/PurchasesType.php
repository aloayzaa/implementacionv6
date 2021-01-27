<?php

namespace App\PurchasesType\Entities;

use Illuminate\Database\Eloquent\Model;

class PurchasesType extends Model
{
    protected $table = "tipocompra";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
