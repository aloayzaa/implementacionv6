<?php

namespace App\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ThirdItem extends Model
{
    protected $table = "tercero_rubro";
    public $timestamps = false;

    public static function update_data($tercero_id, $item, $data){
        return static::where('tercero_id', $tercero_id)
            ->where('item', $item)
            ->update($data);
    }
}
