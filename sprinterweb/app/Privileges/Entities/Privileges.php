<?php

namespace App\Privileges\Entities;

use Illuminate\Database\Eloquent\Model;

class Privileges extends Model
{
    protected $table = "privilegio";
    public $timestamps = false;

    public static function update_data($id, $menu, $data)
    {
        return static::where('usuario_id', $id)
            ->where('menu_id', $menu)
            ->update($data);
    }
    public static function condiciones($variable, $usuario){;
        return static::select('m.descripcion','p.crea', 'p.edita', 'p.anula', 'p.borra', 'p.consulta', 'p.imprime')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado',1)
            ->where('u.codigo', $usuario)
            ->whereRaw("length(m.codigo) >5 and length(m.codigo) <= 8 and m.codigo LIKE '".$variable."%'")
            ->orderBy('m.codigo', 'asc')
            ->get();
    }

    public static function cargar_padre($variable){
        return static::select('descripcion')
            ->from('menu')
            ->whereRaw('length(codigo) >2 and length(codigo) <=5 and codigo LIKE "'.$variable.'%"')
            ->orderBy('codigo', 'asc')
            ->get();
    }

/*    public static function condiciones($variable, $usuario, $limit){;
        return static::select('m.descripcion','p.crea', 'p.edita', 'p.anula', 'p.borra', 'p.consulta', 'p.imprime')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado',1)
            ->where('u.usuario', $usuario)
            ->whereRaw("length(m.codigo) >5 and length(m.codigo) <= 8 and m.codigo LIKE '".$variable."%'")
            ->offset(0)
            ->limit($limit)
            ->orderBy('m.codigo', 'asc')
            ->get();
    }

    public static function especial($variable, $usuario, $limit){
        return static::select('m.descripcion','p.crea', 'p.edita', 'p.anula', 'p.borra', 'p.consulta', 'p.imprime')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado',1)
            ->where('u.usuario', $usuario)
            ->whereRaw("length(m.codigo) > 8 and length(m.codigo) <=11 and m.codigo LIKE '".$variable."%'")
            ->offset(0)
            ->limit($limit)
            ->orderBy('m.codigo', 'asc')
            ->get();
    }

    public static function cargar_padre($variable, $condicion){
        return static::select('descripcion')
            ->from('menu')
            ->whereRaw('descripcion <> "'.$condicion.'" and length(codigo) >2 and length(codigo) <=5 and codigo LIKE "'.$variable.'%"')
            ->orderBy('codigo', 'asc')
            ->get();
    }*/

    public static function modulos($variable, $usuario){
        return static::select('m.descripcion','m.codigo','p.*')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado', 1)
            ->where('u.codigo', $usuario)
            ->whereRaw("m.codigo LIKE '".$variable."%'")
            ->orderBy('m.codigo','asc')
            ->get();
    }

    public static function data($variable, $usuario){
        return static::select('m.descripcion', 'p.crea', 'p.edita', 'p.anula', 'p.borra', 'p.consulta', 'p.imprime')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('u.codigo', $usuario)
            ->whereRaw("m.estado = 1 AND m.codigo = '".$variable."'")
            ->first();
    }

    public static function data_menu($variable, $usuario){
        return static::select('m.descripcion','p.consulta', 'p.crea')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('u.codigo', $usuario)
            ->whereRaw("m.estado = 1 AND m.codigo LIKE '".$variable."%'")
            ->orderBy('m.codigo','asc')
            ->get();
    }
    /*public static function administrador(){
        return static::select('descripcion','codigo')
            ->from('menu')
            ->whereRaw("length(codigo) >= 8")
            ->orderBy('codigo', 'asc')
            ->get();
    }
    public static function administrador2($variable){
        return static::select('m.descripcion','m.codigo', 'p.*')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado', 1)
            ->whereRaw("length(m.codigo) >= 8")
            ->whereRaw("m.codigo LIKE '".$variable."%'")
            ->orderBy('m.codigo', 'asc')
            ->get();
    }
    public static function menu(){
        return static::select('m.codigo','p.*')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado', 1)
            ->orderBy('m.codigo','asc')
            ->get();
    }*/

}
