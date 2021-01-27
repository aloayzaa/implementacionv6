<?php

namespace App\Seller\Entities;

use App\Marks\Entities\Marca;
use Illuminate\Database\Eloquent\Model;

class CommissionSeller extends Model
{
    protected $table = 'vendedor_comision';
    public $timestamps = false;

    public function marca(){
        return $this->belongsTo(Marca::class,'marca_id');
    }

    public static function update_data($id, $item, $data)
    {
        return static::where('parent_id', $id)
            ->where('item', $item)
            ->update($data);
    }


}
