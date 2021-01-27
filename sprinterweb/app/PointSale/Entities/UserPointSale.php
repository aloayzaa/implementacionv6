<?php

namespace App\PointSale\Entities;

use Illuminate\Database\Eloquent\Model;

class UserPointSale extends Model
{
    protected $table = "usuario_puntoventa";
    public $timestamps = false;

    public static function usuario_puntoventa($id){
        return static::select('p.*')
            ->from('usuario_puntoventa as up')
            ->join('puntoventa as p', 'up.usuario_id', '=', 'p.id')
            ->where('up.usuario_id', $id)
            ->orderBy('p.id', 'desc')
            ->get();
    }
}
