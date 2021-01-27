<?php

namespace App\AccountingNote\Entities;

use App\Currency\Entities\Currency;
use App\Period\Entities\Period;
use App\Subdiaries\Entities\Subdiaries;
use App\Subsidiaries\Entities\Subsidiaries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class AccountingNote extends Model
{
    protected $table = "notacontable";
    public $timestamps = false;

    public function periodo()
    {
        return $this->belongsTo(Period::class, 'periodo_id');
    }

    public function subdiario()
    {
        return $this->belongsTo(Subdiaries::class, 'subdiario_id');
    }

    public function moneda()
    {
        return $this->belongsTo(Currency::class, 'moneda_id');
    }

    public function notaContableDetalle()
    {
        return $this->hasMany(AccountingNoteDetail::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Subsidiaries::class, 'sucursal_id');
    }


    public function scopeListNotas($query, $period_id){
        if($period_id){
            return $query->select(        //quite un * para ingreso y sucursal
                'n.id as nota_id',
                'n.tcambio as tipocambio',
                'n.fechaproceso',
                'n.numero',
                'n.glosa',
                'n.totalmn',
                'n.totalme',
                'n.estado',
                'p.codigo as codigo_periodo',
                'm.simbolo as simbolo_moneda',
                's.codigo as codigo_subdiario',
                's.descripcion as descripcion_subdiario')
                ->from('notacontable as n')
                ->leftjoin('periodo as p', 'p.id', '=', 'n.periodo_id')
                ->leftjoin('subdiario as s', 's.id', '=', 'n.subdiario_id')
                ->leftjoin('moneda as m', 'm.id', '=', 'n.moneda_id')
                ->where('n.periodo_id',$period_id)
                ->get();
        }
    }


/*    public static function lista_nota_contable($ventana)
    {
        return static::select('*', 'n.tcambio as tipocambio',
            's.codigo as codigo_subdiario',
            's.descripcion as descripcion_subdiario',
            'p.codigo as codigo_periodo',
            'p.descripcion as descripcion_periodo',
            'm.simbolo as simbolo_moneda',
            'n.id as id',
            'n.estado as estado')
            ->from('notacontable as n')
            ->leftjoin('subdiario as s', 's.id', '=', 'n.subdiario_id')
            ->leftjoin('periodo as p', 'p.id', '=', 'n.periodo_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'n.moneda_id')
            ->where('n.ventana', $ventana)
            ->where('n.periodo_id', Session::get('period_id'))
            ->get();
    }*/


    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function list_nota_contable($ventana){
        return static::selectRaw("i.id, concat(p.codigo, '-', i.numero) as voucher, s.descripcion as sucursal,
                                    concat(rtrim(sd.codigo),' ',rtrim(sd.descripcion)) as subdiario,
                                    i.fechaproceso as fecha, i.tcambio, m.simbolo as moneda,
                                    case when i.estado = 'ANULADO' then 0 else i.totalmn end as totalmn,
                                    case when i.estado = 'ANULADO' then 0 else i.totalme end as totalme,
                                    i.glosa, i.ventana, i.estado")
            ->from('notacontable as i')
            ->join('subdiario as sd', 'i.subdiario_id', '=', 'sd.id')
            ->join('sucursal as s', 'i.sucursal_id', '=', 's.id')
            ->join('periodo as p', 'i.periodo_id', '=', 'p.id')
            ->join('moneda as m', 'i.moneda_id', '=', 'm.id')
//            ->where('n.ventana', $ventana)
            ->get();
    }
}
