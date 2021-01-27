<?php

namespace App\ServiceOrders\Entities;

use App\Activity\Entities\Activity;
use App\Costs\Entities\Costs;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\Products\Entities\Productos;
use App\Project\Entities\Project;
use App\WorkOrder\Entities\WorkOrder;
use Illuminate\Database\Eloquent\Model;

class ServiceOrdersDetail extends Model
{
    protected $table = "ordencompra_detalle";
    public $timestamps = false;

    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }

    public function unidadmedida()
    {
        return $this->belongsTo(UnidadesMedida::class, 'umedida_id');
    }

    public function centrocosto()
    {
        return $this->belongsTo(Costs::class, 'centrocosto_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Project::class, 'proyecto_id');
    }

    public function activdad()
    {
        return $this->belongsTo(Activity::class, 'actividad_id');
    }

    public function ordentrabajo()
    {
        return $this->belongsTo(WorkOrder::class, 'ordentrabajo_id');
    }

    public static function saveDetails($parent_id, $item, $data)
    {
        return static::where('parent_id', $parent_id)
            ->where('item', $item)
            ->update($data);
    }

    public static function dataCarritoPurcharseOrder($parent_id)
    {
        return static::select('od.*', 'p.codigo as productocodigo', 'p.descripcion as productodescripcion', 'um.codigo as umedidacodigo', 'p.tipoproducto as productotipo', 'p.percepcion as productopercepcion', 'p.volumen as productovolumen')
            ->from('ordencompra_detalle as od')
            ->join('producto as p', 'od.producto_id', '=', 'p.id')
            ->join('umedida as um', 'od.umedida_id', '=', 'um.id')
            ->where('od.parent_id', $parent_id)
            ->get();
    }
}
