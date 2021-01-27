<?php

namespace App\CurrentAccountMovement\Entities;

use Illuminate\Database\Eloquent\Model;

class CurrentAccountMovement extends Model
{
    protected $table = "movctacte";
    public $timestamps = false;

    public static function MuestraHistorial($docxpagar_id)
    {
        return static::where('docxpagar_id', $docxpagar_id)
            ->orderBy('id', 'desc')
            ->get();
    }
}
