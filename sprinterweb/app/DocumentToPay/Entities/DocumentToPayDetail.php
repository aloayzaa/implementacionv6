<?php

namespace App\DocumentToPay\Entities;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Costs\Entities\Costs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentToPayDetail extends Model
{
    protected $table = "docxpagar_detalle";
    public $timestamps = false;

    public function cuenta()
    {
        return $this->belongsTo(AccountingPlan::class);
    }

    public function centroCosto()
    {
        return $this->belongsTo(Costs::class, 'centrocosto_id');
    }

    public static function editarRefactorizado($id, $item, $data)
    {
        return static::where('parent_id', $id)
            ->where('item', $item)
            ->update($data);
    }

    public static function saveDetails($parent_id, $item, $data){
        return static::where('parent_id', $parent_id)
            ->where('item', $item)
            ->update($data);
    }

    public function scopeDetalle($query, $parent_id)
    {
        if ($parent_id) {
            return $query->select(
                'docxpagar_detalle.parent_id',
                'docxpagar_detalle.item',
                'docxpagar_detalle.referencia_id',
                'docxpagar_detalle.cuenta_id',
                'docxpagar_detalle.esnuevo',
                'docxpagar_detalle.glosa',
                'docxpagar_detalle.centrocosto_id',
                'docxpagar_detalle.importe',

                'c.codigo as codigo_ccosto',
                'c.descripcion as descripcion_ccosto',
                'cuenta.codigo as codigo_cuenta',
                'u.codigo as codigo_umedida')
                ->join('umedida as u', 'u.id', '=', 'docxpagar_detalle.umedida_id', 'left')
                ->join('pcg as cuenta', 'cuenta.id', '=', 'docxpagar_detalle.cuenta_id', 'left')
                ->join('centrocosto as c', 'c.id', '=', 'docxpagar_detalle.centrocosto_id', 'left')
                ->where('docxpagar_detalle.parent_id', '=', $parent_id)
                ->get();
        }
    }

    public static function selectidcmd($pId){
        return static::selectRaw("d.*, d.precio * (1 - (d.descuento / 100)) as preciodscto,
                                p.codigo as cuenta, p.pide_ccosto, p.pide_op, p.tipo_auxiliar,
                                c.codigo as cco_cod, c.descripcion as cco_dsc,
                                a.codigo as act_cod, a.descripcion as act_dsc,
                                y.codigo as pry_cod, y.descripcion as pry_dsc, ta.codigo afeigv_cod, ta.descripcion afeigv_dsc,
                                pr.codigo as prd_cod, pr.esafecto, pr.consumo, u.codigo as ume_cod, pr.tipoproducto, pr.tipomercaderia, pr.utilidad,
                                ifnull(pr.precio1,0) precio1, ifnull(pr.precio2,0) precio2, ifnull(pr.precio3,0) precio3,
                                pr.pidelote, pr.pideserie, pr.umedida_id as um1, pr.ucompra_id as um2, pr.conversion, cast(0 as decimal(15,6)) as stock,
                                r.tercero_id, concat(rtrim(dc.codigo),' ',rtrim(r.seriedoc),'-',rtrim(r.numerodoc)) as docrefer, ot.nromanual as otrabajo")
            ->from('docxpagar_detalle as d')
            ->leftJoin('docxpagar as r', 'd.referencia_id', '=', 'r.id')
            ->leftJoin('ordentrabajo as ot', 'd.ordentrabajo_id', '=', 'ot.id')
            ->leftJoin('pcg as p', 'd.cuenta_id', '=', 'p.id')
            ->leftJoin('producto as pr', 'd.producto_id', '=', 'pr.id')
            ->leftJoin('umedida as u', 'd.umedida_id', '=', 'u.id')
            ->leftJoin('centrocosto as c', 'd.centrocosto_id', '=', 'c.id')
            ->leftJoin('actividad as a', 'd.actividad_id', '=', 'a.id')
            ->leftJoin('proyecto as y', 'd.proyecto_id', '=', 'y.id')
            ->leftJoin('tipoafectaigv as ta', 'd.tipoafectaigv_id', '=', 'ta.id')
            ->leftJoin('documentocom as dc', 'r.documento_id', '=', 'dc.id')
            ->where('d.parent_id', $pId)
            ->get();
    }
}
