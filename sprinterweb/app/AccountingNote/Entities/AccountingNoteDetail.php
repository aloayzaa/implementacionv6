<?php

namespace App\AccountingNote\Entities;

use App\Costs\Entities\Costs;
use App\Customers\Entities\Customer;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Products\Entities\Productos;
use App\Project\Entities\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountingNoteDetail extends Model
{
    protected $table = "notacontable_detalle";
    public $timestamps = false;

    public function notacontable()
    {
        return $this->belongsTo(AccountingNote::class, 'parent_id');
    }

    public function cuenta()
    {
        return $this->belongsTo(AccountingPlan::class, 'cuenta_id');
    }

    public function centrocosto()
    {
        return $this->belongsTo(Costs::class, 'centrocosto_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }

    public function tercero()
    {
        return $this->belongsTo(Customer::class, 'tercero_id');
    }

    public function scopeData($query, $id)
    {
        if($id){
            return $query->select(
                'notacontable_detalle.parent_id',
                'notacontable_detalle.item',
                'notacontable_detalle.cuenta_id',
                'notacontable_detalle.glosa',
                'notacontable_detalle.cargomn',
                'notacontable_detalle.abonomn',
                'notacontable_detalle.cargome',
                'notacontable_detalle.abonome',
                'notacontable_detalle.tercero_id',
                'notacontable_detalle.referencia_id',
                'notacontable_detalle.producto_id',
                'notacontable.tablareferencia',
                'c.codigo as codigo_ccosto',
                'c.descripcion as descripcion_ccosto')
                ->join('tercero as t', 't.id', '=', 'notacontable_detalle.tercero_id', 'left')
                ->join('centrocosto as c', 'c.id', '=', 'notacontable_detalle.centrocosto_id', 'left')
                ->join('pcg as cu', 'cu.id', '=', 'notacontable_detalle.cuenta_id', 'left')
                ->join('notacontable', 'notacontable.id', '=', 'notacontable_detalle.parent_id', 'left')
                ->where('notacontable_detalle.parent_id', '=', $id)
                ->get();
        }
    }

    public static function verDatos($id)
    {
        return static::select('notacontable_detalle.item',
            'notacontable_detalle.glosa',
            'notacontable_detalle.cargomn',
            'notacontable_detalle.abonomn',
            'notacontable_detalle.cargome',
            'notacontable_detalle.abonome',
            'notacontable_detalle.referencia_id',
            't.id as tercero_id',
            't.codigo as codigo_tercero',
            't.descripcion as descripcion_tercero',
            'c.id as centrocosto_id',
            'c.codigo as codigo_ccosto',
            'c.descripcion as descripcion_ccosto',
            'cu.id as cuenta_id',
            'cu.codigo as codigo_cuenta',
            'cu.descripcion as descripcion_cuenta')
            ->join('tercero as t', 't.id', '=', 'notacontable_detalle.tercero_id', 'left')
            ->join('centrocosto as c', 'c.id', '=', 'notacontable_detalle.centrocosto_id', 'left')
            ->join('pcg as cu', 'cu.id', '=', 'notacontable_detalle.cuenta_id', 'left')
            ->where('notacontable_detalle.parent_id', '=', $id)
            ->get();
    }

    public static function editar($parent_id, $item, $data)
    {
        return static::where('parent_id', $parent_id)
            ->where('item', $item)
            ->update($data);
    }

    public static function datos($id){
        return static::selectRaw("d.*, p.codigo as cuenta, p.pide_ccosto, p.pide_op, p.tipo_auxiliar, c.codigo as cco_cod, c.descripcion as cco_dsc,
		t.codigo, t.descripcion as nombre, pr.codigo prd_cod, pr.descripcion prd_dsc,
		concat(rtrim(dc.codigo),' ',rtrim(r.seriedoc),'-',rtrim(r.numerodoc)) as docrefer, ot.nromanual as otrabajo,
		a.codigo as act_cod, a.descripcion as act_dsc, y.codigo as pry_cod, y.descripcion as pry_dsc, ac.codigo as afj_cod, ac.descripcion as afj_dsc")
            ->from('notacontable_detalle as d')
            ->leftjoin('pcg as p', 'd.cuenta_id', '=', 'p.id')
            ->leftjoin('docxpagar as r', 'd.referencia_id', '=', 'r.id')
            ->leftjoin('ordentrabajo as ot', 'd.ordentrabajo_id', '=', 'ot.id')
            ->leftjoin('tercero as t', 'd.tercero_id', '=', 't.id')
            ->leftjoin('producto as pr', 'd.producto_id', '=', 'pr.id')
            ->leftjoin('activo as ac', 'd.activo_id', '=', 'ac.id')
            ->leftjoin('centrocosto as c', 'd.centrocosto_id', '=', 'c.id')
            ->leftjoin('actividad as a', 'd.actividad_id', '=', 'a.id')
            ->leftjoin('proyecto as y', 'd.proyecto_id', '=', 'y.id')
            ->leftjoin('documentocom as dc', 'r.documento_id', '=', 'dc.id')
            ->where('d.parent_id', $id)
            ->get();
    }
}
