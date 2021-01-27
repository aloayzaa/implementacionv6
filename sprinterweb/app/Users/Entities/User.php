<?php

namespace App\Users\Entities;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'DB_CONNECTION_PANEL';
    protected $table = "usuario";
    public $timestamps = false;

    protected $fillable = ['usu_usuario', 'usu_clave'];
    protected $hidden = ['usu_clave', 'remember_token'];

    public static function lista_usuarios_por_empresa($id_empresa)
    {
        return static::select('*', 'u.id as id', 'u.usu_estado as usu_estado')
            ->from('usuario as u')
            ->join('usuario_empresa as ue', 'u.id', '=', 'ue.usu_id')
            ->join('empresa as em', 'em.id', '=', 'ue.emp_pertenece_id')
            ->where('em.id', $id_empresa)
            ->groupBy('ue.usu_id')
            ->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['usu_estado' => $parametro]);
    }
}
