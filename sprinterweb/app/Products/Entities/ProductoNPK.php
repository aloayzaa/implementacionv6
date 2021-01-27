<?php

namespace App\Products\Entities;

use App\Nutrient\Entities\Nutrient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ProductoNPK extends Model
{
    protected $table = "producto_npk";
    public $timestamps = false;

    public function nutriente(){
        return $this->belongsTo(Nutrient::class,'nutriente_id');
    }

    public static function update_data($id, $data)
    {
        return static::where('id', $id)
            ->update($data);
    }
}
