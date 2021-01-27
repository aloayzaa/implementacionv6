<?php

namespace App\Documents\Entities;

use App\Bank\Entities\Bank;
use App\BankCurrentAccount\Entities\BankCurrentAccount;
use App\Currency\Entities\Currency;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BankDocument extends Model
{
    protected $table = "docbanco";
    public $timestamps = false;

    public function moneda()
    {
        return $this->belongsTo(Currency::class, 'moneda_id');
    }

    public function cuentacorriente()
    {
        return $this->belongsTo(BankCurrentAccount::class, 'ctactebanco_id');
    }

    public function banco()
    {
        return $this->belongsTo(Bank::class, 'banco_id');
    }

    public function periodo()
    {
        return $this->belongsTo(Period::class, 'periodo_id');
    }

    public function mediopago()
    {
        return $this->belongsTo(PaymentType::class, 'mediopago_id');
    }

    public static function lista_banco($ventana)
    {
        return DB::select("select i.id as id, i.tipo as tipo_operacion, i.estado as estado, b.descripcion as descripcion_banco, p.codigo as periodo, i.*, s.descripcion as sucursal,
		b.descripcion as banco, cb.descripcion as ctactebanco, DATE_FORMAT(i.fechaproceso,'%d - %m - %Y') as fechaproceso,
		m.simbolo as simbolo_moneda, cb.descripcion as descripcion_ctactebanco,
		case i.tipo when 'I' then total else 0 end as ingreso,
		case i.tipo when 'E' then total else 0 end as egreso
		from docbanco i
		join sucursal s on i.sucursal_id=s.id
		join periodo p on i.periodo_id=p.id
		join banco b on i.banco_id=b.id
		join ctactebanco cb on i.ctactebanco_id=cb.id
		join moneda m on i.moneda_id=m.id
		where i.periodo_id =" . Session::get('period_id') . " and i.ventana = '$ventana'");
    }

}
