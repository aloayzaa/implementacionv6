<?php

namespace App\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserCompany extends Model
{
    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "usuario_empresa";
    public $timestamps = false;

    public static function pertenece_a($usuario_id)
    {
        return static::where('usu_id', $usuario_id)
            ->limit(1)
            ->first();
    }

    public static function idCantidad($id)
    {
        return static::select(DB::raw('count(*) as cantidad'))
            ->where('id', '=', $id)
            ->first();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['uem_estado' => $parametro]);
    }
}
