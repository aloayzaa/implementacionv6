<?php

namespace App\Suscriptions\Entities;

use App\Accounting\Entities\Estudio;
use Illuminate\Database\Eloquent\Model;

class Suscripcion extends Model
{
    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "suscripcion";
    public $timestamps = false;

    public static function listado($id_empresa)
    {
        return static::select('*', 's.id as suscripcion_id')
            ->from('suscripcion as s')
            ->join('plan as p', 'p.id', '=', 's.pla_id')
            ->join('forma_pago as fp', 'fp.id', '=', 's.fop_id')
            ->orderBy('sus_fechainicio', 'desc')
            ->where('s.emp_id', $id_empresa)
            ->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['sus_estado' => $parametro]);
    }

    public function estudio()
    {
        return $this->belongsTo(Estudio::class, 'est_id');
    }
}
