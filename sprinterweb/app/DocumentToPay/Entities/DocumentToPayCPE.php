<?php

namespace App\DocumentToPay\Entities;

use Illuminate\Database\Eloquent\Model;

class DocumentToPayCPE extends Model
{
    protected $table = "docxpagar_cpe";
    public $timestamps = false;

    public static function selectparentcmd($pId){
        return static::selectRaw("e.*, d.codsunat tipodoc, c.seriedoc, c.numerodoc, c.ventana")
            ->from('docxpagar_cpe as e')
            ->join('docxpagar as c', 'e.docxpagar_id', '=', 'c.id')
            ->join('documentocom as d', 'c.documento_id', '=', 'd.id')
            ->where('e.docxpagar_id', $pId)
            ->first();
    }
}
