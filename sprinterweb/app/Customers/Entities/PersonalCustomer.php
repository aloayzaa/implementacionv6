<?php

namespace App\Customers\Entities;

use Illuminate\Database\Eloquent\Model;

class PersonalCustomer extends Model
{
    protected $table = "tercero_personal";
    public $timestamps = false;

    public static function cobrador()
    {
        return static::select('*')
            ->from('tercero_personal as tp')
            ->join('tercero as t', 't.id', '=', 'tp.tercero_id')
            ->get();
    }
}
