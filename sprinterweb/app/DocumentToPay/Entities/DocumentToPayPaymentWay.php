<?php

namespace App\DocumentToPay\Entities;

use App\Documents\Entities\BankDocument;
use App\PaymentCondition\Entities\PaymentCondition;
use App\PaymentMethod\Entities\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class DocumentToPayPaymentWay extends Model
{
    protected $table = "docxpagar_formapago";
    public $timestamps = false;

    public function formaPago()
    {
        return $this->belongsTo(PaymentMethod::class, 'formapago_id'); //forma pago
    }

    public function docBanco()
    {
        return $this->belongsTo(BankDocument::class, 'docbanco_id');
    }


    public static function editarRefactorizado($id, $item, $data)
    {
        return static::where('parent_id', $id)
            ->where('item', $item)
            ->update($data);
    }

    public static function selectidcmd($pId){
        return static::selectRaw('d.*, f.codigo as fpg_cod, f.descripcion as fpg_dsc, c.banco_id, c.moneda_id, b.efectivo,
        m.codigo as mpg_cod, m.descripcion as mpg_dsc, db.ventana, d.importe as soles, d.importe as dolares')
            ->from('docxpagar_formapago as d')
            ->leftJoin('docbanco as db', 'd.docbanco_id', '=', 'db.id')
            ->leftJoin('formapago as f', 'd.formapago_id', '=', 'f.id')
            ->leftJoin('ctactebanco as c', 'f.ctactebanco_id', '=', 'c.id')
            ->leftJoin('banco as b', 'c.banco_id', '=', 'b.id')
            ->leftJoin('mediopago as m', 'd.mediopago_id', '=', 'm.id')
            ->where('d.parent_id', $pId)
            ->first();
    }


}
