<?php

namespace App\BusinessUnit\Entities;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    protected $table = "unegocio";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
