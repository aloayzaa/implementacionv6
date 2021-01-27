<?php

namespace App\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ThirdMark extends Model
{
    protected $table = "tercero_marca";
    public $timestamps = false;

    public static function update_data($tercero_id, $item, $data){
        return static::where('tercero_id', $tercero_id)
            ->where('item', $item)
            ->update($data);
    }

}
