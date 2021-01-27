<?php

namespace App\Customers\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class CustomerContact extends Model
{
    protected $table = "tercero_contacto";
    public $timestamps = false;

    public function tercero()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function update_data($tercero_id, $item, $data){
        return static::where('tercero_id', $tercero_id)
            ->where('item', $item)
            ->update($data);
    }
    public static function cargar_contacto($id){
        return static::select('tercero_id', 'nrodocidentidad', 'nombre')
            ->where('tercero_id',$id)
            ->get();
    }
}
