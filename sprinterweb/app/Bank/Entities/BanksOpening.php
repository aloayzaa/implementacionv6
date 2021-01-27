<?php

namespace App\Bank\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class BanksOpening extends Model
{
    protected $table = "docbanco_apertura";
    public $timestamps = false;

    public static function apertura_de_bancos()
    {
        return static::select('*', 't.codigo as codigo_tercero', 't.descripcion as descripcion_tercero', 'da.estado as estado',
            'da.id as id', 'm.simbolo as simbolo_moneda', 'b.descripcion as descripcion_banco', 'c.descripcion as descripcion_ctactebanco',
            'da.nrocheque as nrocheque')
            ->from('docbanco_apertura as da')
            ->leftjoin('tercero as t', 't.id', '=', 'da.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'da.moneda_id')
            ->leftjoin('banco as b', 'b.id', '=', 'da.banco_id')
            ->leftjoin('ctactebanco as c', 'c.id', '=', 'da.ctactebanco_id')
            ->where('periodo_id', Session::get('period_id'))
            ->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }
}
