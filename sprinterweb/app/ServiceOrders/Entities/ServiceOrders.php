<?php

namespace App\ServiceOrders\Entities;

use App\Customers\Entities\Customer;
use App\Period\Entities\Period;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ServiceOrders extends Model
{
    protected $table = "ordencompra";
    public $timestamps = false;

    public function tercero()
    {
        return $this->belongsTo(Customer::class, 'tercero_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Period::class, 'periodo_id');
    }

    public static function lista_ordenservicios()
    {
        return static::select('o.*', DB::raw('convert(concat(p.codigo, "-", RIGHT(concat("0000", o.numero),5)) using latin1) as numope'),
            't.descripcion as descripcion_tercero', 'm.simbolo as simbolo_moneda', 'o.id as id')
            ->from('ordencompra as o')
            ->leftjoin('tercero as t', 't.id', '=', 'o.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'o.moneda_id')
            ->leftjoin('periodo as p', 'p.id', '=', 'o.periodo_id')
//            ->where('periodo_id', Session::get('period_id'))
            ->where('o.tipo', 'S')
            ->get();
    }
}
