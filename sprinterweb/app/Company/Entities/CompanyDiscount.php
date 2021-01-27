<?php

namespace App\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class CompanyDiscount extends Model
{
    protected $table = "pempresa_dscto";
    public $timestamps = false;

    public static function selectcmd($documento_id){ // ver el clasic
        return static::select(DB::raw('pe.*, d.codigo as tipodoc, p.codigo as cuenta, t.codigo as operacion'))
            ->from('pempresa_dscto as pe')
            ->join('pcg as p', 'pe.cuenta_id', '=', 'p.id')
            ->join('documentocom as d', 'pe.documento_id', '=', 'd.id')
            ->join('tipooperacion as t', 'pe.tipooperacion_id', '=', 't.id')
            ->where('pe.documento_id',$documento_id)
            ->first();
    }
}
