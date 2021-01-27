<?php

namespace App\Expenses\Entities;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = "tipogasto";
    public $timestamps = false;

    public static function listar_ordenado()
    {
        return static::orderBy('codigo', 'asc')
            ->get();
    }
}
