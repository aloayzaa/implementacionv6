<?php

namespace App\FamilyAccountingProduct\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FamilyAccountingProduct extends Model
{
    protected $table = "familiapdto_contab";
    public $timestamps = false;
    protected $primaryKey = 'parent_id';

    public static function saveDetails($parent_id, $data){
        return static::where('parent_id', $parent_id)
            ->update($data);
    }

    //no es mio
    public static function getctacomprapdto($id)
    {
        return static::select('familiapdto_contab.parent_id', 'cta60.id as id_cta60', 'cta60.codigo as cta60codigo',
            'cta60.descripcion as cta60descripcion', DB::raw('concat(cta60.codigo," | ",cta60.descripcion) as name60'))
            ->join('pcg as cta60', 'cta60.id', '=', 'familiapdto_contab.cta60_id', 'left')
            ->where('familiapdto_contab.parent_id', '=', $id)
            ->first();
    }
}
