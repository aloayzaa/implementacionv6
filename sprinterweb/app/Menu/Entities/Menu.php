<?php

namespace App\Menu\Entities;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menu";
    public $timestamps = false;

    public static function filtroId($id)
    {
        return static::select('id', 'codigo', 'descripcion', 'nombreComercial', 'estado')
            ->Where('marca_id', '=', $id)
            ->get();
    }
    ///////////////////
    public static function modulo($variable){
        return static::select('modulo','descripcion')
            ->whereRaw("length(codigo) > 2 and length(codigo) <= 5 and codigo LIKE '$variable%'")
            ->orderBy('codigo','asc')
            ->get();
    }


    public static function submodulos($variable){
        return static::select('id','modulo','descripcion')
            ->where('estado',1)
            ->whereRaw("codigo LIKE '$variable%'")
            ->whereRaw("length(codigo) > 5 and length(codigo) <= 8")
            ->orderBy('codigo','asc')
            ->get();
    }
    public static function submodulos_especiales($variable){
        return static::select('id','modulo','descripcion')
            ->where('estado',1)
            ->whereRaw("codigo LIKE '$variable%'")
            ->whereRaw("length(codigo) > 8 and length(codigo) <= 11")
            ->orderBy('codigo','asc')
            ->get();
    }

    public static function submodulos2($variable, $id){
        return static::select('p.*')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado',1)
            ->where('p.usuario_id', $id)
            ->whereRaw("length(m.codigo) <= 8 and m.codigo LIKE '".$variable."%'")
            ->orderBy('m.codigo', 'asc')
            ->get();
    }
    public static function submodulos3($variable, $id){
        return static::select('m.id', 'm.descripcion', 'p.*')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado',1)
            ->where('p.usuario_id', $id)
            ->whereRaw("length(m.codigo) > 8 and length(m.codigo) <=11 and m.codigo LIKE '".$variable."%'")
            ->orderBy('m.codigo', 'asc')
            ->get();
    }

    public static function especial($variable, $id){
        return static::select('m.modulo', 'm.descripcion')
            ->from('privilegio as p')
            ->join('menu as m', 'p.menu_id', '=', 'm.id')
            ->join('usuario as u', 'p.usuario_id', '=', 'u.id')
            ->where('m.estado',1)
            ->where('p.usuario_id', $id)
            ->whereRaw("m.codigo LIKE '".$variable."'")
            ->orderBy('m.codigo', 'asc')
            ->get();
    }

    public static function admin_condicion($variable){
        return static::select('*')
            ->whereRaw("length(codigo) > 2")
            ->whereRaw("length(codigo) <= 5")
            ->whereRaw("codigo LIKE '".$variable."%'")
            ->orderBy('codigo', 'asc')
            ->get();
    }

    public static function admin(){
        return static::select('*')
            ->whereRaw("length(codigo) <= 2")
            ->orderBy('codigo', 'asc')
            ->get();
    }
}
