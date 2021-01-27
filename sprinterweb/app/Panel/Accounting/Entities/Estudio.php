<?php

namespace App\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estudio extends Model
{
    protected $connection = "DB_CONNECTION_PANEL";
    protected $table = "estudio";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['est_estado' => $parametro]);
    }
}
