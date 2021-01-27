<?php

namespace App\Nutrient\Entities;

use App\Products\Entities\ProductoNPK;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Nutrient extends Model
{
    protected $table = "nutriente";
    public $timestamps = false;

    public function producto_npk(){
        return $this->hasMany(ProductoNPK::class);
    }
}
