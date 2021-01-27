<?php

namespace App\Documents\Entities;

use App\Activity\Entities\Activity;
use App\Costs\Entities\Costs;
use App\Customers\Entities\Customer;
use App\DocumentToPay\Entities\DocumentToPay;
use App\AccountingPlan\Entities\AccountingPlan;
use App\OperationType\Entities\OperationType;
use App\Project\Entities\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BankDocumentDetail extends Model
{
    protected $table = "docbanco_detalle";
    public $timestamps = false;

    public function tipooperacion()
    {
        return $this->belongsTo(OperationType::class, 'tipooperacion_id');
    }

    public function centrocosto()
    {
        return $this->belongsTo(Costs::class, 'centrocosto_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }

    public function actividad()
    {
        return $this->belongsTo(Activity::class, 'actividad_id');
    }

    public function pcg()
    {
        return $this->belongsTo(AccountingPlan::class, 'cuenta_id');
    }

    public function tercero()
    {
        return $this->belongsTo(Customer::class, 'tercero_id');
    }

    public static function editar_transferencia($parent_id, $data)
    {
        return static::where('parent_id', $parent_id)
            ->where('item', 0)
            ->orWhere('item', 1)
            ->update($data);
    }

    public static function saveDetails($parent_id, $item, $data)
    {
        return static::where('parent_id', $parent_id)
            ->where('item', $item)
            ->update($data);
    }

    public static function editar_docbanco($docbanco_id){
        return static::select(DB::raw("d.*,
        o.codigo as ope_cod, o.descripcion as ope_dsc, o.pidedocumento, o.esanticipo,
        t.codigo, t.descripcion as nombre, concat(rtrim(dc.codigo),' ',rtrim(r.seriedoc),'-',rtrim(r.numerodoc)) as docrefer, r.fechaproceso,
        r.documento_id, dc.codigo doc_cod, dc.descripcion doc_dsc,
        p.codigo as cuenta, p.descripcion as cuenta_descripcion, p.pide_ccosto, p.pide_op, p.tipo_auxiliar,
        c.codigo as cco_cod, c.descripcion as cco_dsc,
        a.codigo as act_cod, a.descripcion as act_dsc,
        y.codigo as pry_cod, y.descripcion as pry_dsc"))
        ->from('docbanco_detalle as d')
        ->leftJoin('pcg as p', 'd.cuenta_id', '=', 'p.id')
        ->leftJoin('docxpagar as r', 'd.referencia_id', '=', 'r.id')
        ->leftJoin('tercero as t', 'd.tercero_id', '=', 't.id')
        ->leftJoin('centrocosto as c', 'd.centrocosto_id', '=', 'c.id')
        ->leftJoin('documentocom as dc', 'r.documento_id', '=', 'dc.id')
        ->leftJoin('actividad as a', 'd.actividad_id', '=', 'a.id')
        ->leftJoin('proyecto as y', 'd.proyecto_id', '=', 'y.id')
        ->join('tipooperacion as o', 'd.tipooperacion_id', '=', 'o.id')
        ->where('d.parent_id', $docbanco_id)
        ->get();
    }
}
