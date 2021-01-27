<?php

namespace App\Warehouses\Entities;


use App\DocumentToPay\Entities\DocumentToPay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class WarehouseIncomePending extends Model
{
    protected $table = "ingresoalmacen_exr";
    public $timestamps = false;


    public function docxpagar(){
        return $this->belongsTo(DocumentToPay::class);
    }

    public function ScopeData($query, $id){
        $existencias = DB::select("select d.*,
        concat(dc.codigo, ' ', c.seriedoc, '-', c.numerodoc) documento,
        c.fechadoc,
        m.simbolo moneda,
        c.glosa, c.total,
        CAST(0 as decimal(1,0)) as flag
		from ingresoalmacen_exr d
		join docxpagar c on d.docxpagar_id = c.id
		join documentocom dc on c.documento_id = dc.id
		join moneda m on c.moneda_id = m.id
		where d.parent_id = $id");

        return collect($existencias);
    }

}
