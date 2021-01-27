<?php

namespace App\AccountCte\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MovementCte extends Model
{
    protected $table = "movctacte";
    public $timestamps = false;

    public function scopeCta($query, $ordencompra_id){

        if($ordencompra_id){

         return DB::select("select c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as docrefer, c.glosa, c.total,
							case m.tipo when 'N' then dc.pcuentamn_id else dc.pcuentame_id end as cuenta_id,
							sum(if(m.tipo = 'N', mv.saldomn, mv.saldome)) as saldo
						from movctacte mv
						join docxpagar c on mv.docxpagar_id = c.id
						join ingresoalmacen i on c.ingresoalmacen_id = i.id
						join ordencompra o on i.ordencompra_id = o.id
						join documentocom d on c.documento_id = d.id
						left join documentocom_contab dc on c.tipotransaccion_id = dc.tipotransaccion_id and c.documento_id = dc.documentocom_id
						join moneda m on c.moneda_id = m.id
						where o.id = $ordencompra_id
						group by c.id
						having sum(if(m.tipo = 'N', mv.saldomn, mv.saldome)) > 0");

        /*    return $query->select(
                DB::raw('concat(d.origen, " ", doc.seriedoc, "-", doc.numerodoc) as docrefer'),
                DB::raw('case m.tipo when \'N\' then dc.pcuentamn_id else dc.pcuentame_id end as cuenta_id'),
                'doc.id',
                'doc.glosa',
                'doc.total',
                'i.glosa as glosain',
                DB::raw('sum(if(m.tipo = \'N\', mv.saldomn, mv.saldome)) as saldo'))
                ->from('movctacte as mv')
                ->join('docxpagar as doc', 'mv.docxpagar_id', '=', 'doc.id')
                ->join('ingresoalmacen as i', 'doc.ingresoalmacen_id', '=', 'i.id')
                ->join('ordencompra as o', 'i.ordencompra_id', '=', 'o.id')
                ->join('documentocom as d', 'doc.documento_id', '=', 'd.id')
                ->join('moneda as m', 'doc.moneda_id', '=', 'm.id')
                ->leftJoin('documentocom_contab as dc', function($join){
                    $join->on('doc.tipotransaccion_id', '=', 'dc.tipotransaccion_id')
                        ->on('doc.documento_id', '=', 'dc.documentocom_id');
                })
                ->where('o.id', 128)
                ->groupBy('d.id')
                ->havingRaw('sum(if(m.tipo = \'N\', mv.saldomn, mv.saldome)) > 0 ')  //preguntar porq los saldos deben ser mayor a cero
                ->get();*/
        }

    }

    public function scopeIngresos($query, $ingreso_id){

        if($ingreso_id){
            return $query->select(
                'mv.fechaproceso as fecha',
                'mv.saldomn as documento',
                'm.descripcion as moneda',
                'mv.glosa',
                'mv.saldomn as importe')
                ->from('movctacte as mv')
                ->join('docxpagar as doc', 'mv.docxpagar_id', '=', 'doc.id')
                ->join('moneda as m', 'doc.moneda_id', '=', 'm.id')
                ->where('doc.ingresoalmacen_id', $ingreso_id)
                ->get();
        }

    }

    public static function getsaldo($pId){
        return static::selectRaw("sum(if(c.moneda_id=1,m.saldomn,m.saldome)) saldo")
            ->from('movctacte as m')
            ->join('docxpagar as c', 'm.docxpagar_id', '=', 'c.id')
            ->where('m.docxpagar_id', $pId)
            ->first();
    }

    public static function selectidcmd($docxpagar_id){ 

        return DB::select('CALL getmovctacte(?)', array($docxpagar_id));

    }

}
