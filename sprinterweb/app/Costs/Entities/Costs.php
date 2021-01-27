<?php

namespace App\Costs\Entities;



use App\CostByFamily\CostByFamily;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Costs extends Model
{
    protected $table = "centrocosto";
    public $timestamps = false;

    //no quitar

    public function ccostoxfamilia(){
        return $this->hasMany(CostByFamily::class, 'centrocosto_id');
    }


    public static function ultimo_codigo()
    {
        return static::select('codigo')
            ->orderBy('codigo', 'desc')
            ->get()
            ->first();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
