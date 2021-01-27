<?php

namespace App\Companies\Entities;

use App\Users\Entities\UserCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Empresa extends Model
{
    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "empresa";
    public $timestamps = false;

    public static function listado()
    {
        return static::select('e.*', DB::raw('count(e.id) as num_actual'))
            ->from('empresa as e')
            ->groupBy('e.id')
            ->orderBy('emp_fecha_registro', 'desc')
            ->get();
    }

    public function usuarioEmpresa()
    {
        return $this->hasMany(UserCompany::class);
    }

    public static function valida_ruc($ruc)
    {
        return static::where('emp_ruc', $ruc)->first();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['emp_estado' => $parametro]);
    }

    public static function companies()
    {
        return static::select('empresa.*')
            ->join('usuario_empresa', 'empresa.id', '=', 'usuario_empresa.emp_acargo_id')
            //->where('usuario_empresa.usu_id', '=', Auth::user()->id)
            ->where('usuario_empresa.usu_id', '=', 294)
            ->get();
    }

    public static function listado_empresas_x_empresaprincipal($id_empresa)
    {
        return static::select('*')
            ->where('emp_primaria_id', $id_empresa)
            ->get();
    }

    public static function filtraempresa_sesion($id)
    {
        return static::select('id')
            ->where('est_id', '=', $id)
            ->get();
    }

}
