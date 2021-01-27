<?php

namespace App\Documents\Entities;

use Illuminate\Database\Eloquent\Model;

class CommercialCountable extends Model
{
    protected $table = "documentocom_contab";
    public $timestamps = false;


    public function scopeCuenta($query, $tipodoc, $tipotran){
        if($tipodoc){
            return $query->select(
                'pcuentamn_id',
                'pcuentame_id',
                'ccuentamn_id',
                'ccuentame_id'
                )
                ->from('documentocom_contab as doc')
                ->where('doc.documentocom_id', $tipodoc)
                ->where('doc.tipotransaccion_id', $tipotran)
                ->get();
        }
    }
    public static function update_data($documento, $transaccion, $data)
    {
        return static::where('documentocom_id', $documento)
            ->where('tipotransaccion_id', $transaccion)
            ->update($data);
    }
    public static function consulta_document_conta($id){
        return static::select('documentocom_contab.*', 't.codigo', 't.descripcion', 'p1.codigo as cta_pmn', 'p1.descripcion as cta_pmnd', 'p2.codigo as cta_pme', 'p2.descripcion as cta_pmed','p3.codigo as cta_cmn', 'p3.descripcion as cta_cmnd', 'p4.codigo as cta_cme', 'p4.descripcion as cta_cmed')
            ->leftJoin('pcg as p1','documentocom_contab.pcuentamn_id', '=', 'p1.id')
            ->leftJoin('pcg as p2','documentocom_contab.pcuentame_id', '=', 'p2.id')
            ->leftJoin('pcg as p3','documentocom_contab.ccuentamn_id', '=', 'p3.id')
            ->leftJoin('pcg as p4','documentocom_contab.ccuentame_id', '=', 'p4.id')
            ->join('tipotransaccion as t', 'documentocom_contab.tipotransaccion_id', '=', 't.id')
            ->where('documentocom_contab.documentocom_id', $id)
            ->get();
    }
    //Consulta
    //lcSql = 'select pcuentamn_id, pcuentame_id, ccuentamn_id, ccuentame_id from documentocom_contab '+;
    //			'where documentocom_id = ?ptipodoc and tipotransaccion_id = ?pTransac'


}
