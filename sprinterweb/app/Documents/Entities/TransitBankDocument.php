<?php

namespace App\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TransitBankDocument extends Model
{
    protected $table = "docbanco_transito";
    public $timestamps = false;

    public function docBanco(){
        return $this->hasMany(BankDocument::class);
    }

    public static function lista_docBancoTransito(){

        $periodoID = Session::get('period_id');

        return static::select(DB::raw('r.id, p.codigo as periodo, r.numero, r.fechaproceso, r.estado as estado, eb.numero vouchere,
         eb.monedae, eb.total totale, eb.bancoe, eb.ctactebancoe, ib.numero voucheri, ib.monedai, ib.total totali, ib.bancoi,
         ib.ctactebancoi,r.ventana '))
            ->from(DB::raw('docbanco_transito r
            join (select e.transferencia_id, e.numero, e.total, m.simbolo monedae, b.descripcion bancoe, c.descripcion ctactebancoe from docbanco e
                    join banco b on e.banco_id=b.id join ctactebanco c on e.ctactebanco_id=c.id
                    join moneda m on e.moneda_id = m.id where e.periodo_id = ' . $periodoID . ' and e.tipo = "E") eb on r.id = eb.transferencia_id
            join (select i.transferencia_id, i.numero, i.total, m.simbolo monedai, b.descripcion bancoi, c.descripcion ctactebancoi from docbanco i
                    join banco b on i.banco_id=b.id join ctactebanco c on i.ctactebanco_id=c.id
                    join moneda m on i.moneda_id = m.id where i.periodo_id = ' . $periodoID . ' and i.tipo = "I") ib on r.id = ib.transferencia_id
            join periodo p on r.periodo_id = p.id
            where r.periodo_id = '. $periodoID))
            ->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function docbanco_transferencia($transferencia_id){
        return static::select('p.codigo', 't.id', 't.numero')
            ->from('docbanco_transito as t')
            ->join('periodo as p','t.periodo_id','=','p.id')
            ->where('t.id',$transferencia_id)
            ->first();
    }
}
