<?php

namespace App\CostActivities\Entities;

use Illuminate\Database\Eloquent\Model;

class CostActivities extends Model
{
    protected $table = "actividad";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
