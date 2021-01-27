<?php

namespace App\MeasurementUnits\Entities;

use Illuminate\Database\Eloquent\Model;

class UmedidaConversion extends Model
{
    protected $table="umedida_conversion";
    public $timestamps = false;

    public function unidadesmedida(){
        return $this->belongsTo(UnidadesMedida::class,'umedida_id');
    }
    public static function update_data($id, $umedida, $data)
    {
        return static::where('parent_id', $id)
            ->where('umedida_id', $umedida)
            ->update($data);
    }

}
