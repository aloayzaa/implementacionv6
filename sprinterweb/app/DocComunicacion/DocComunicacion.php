<?php

namespace App\DocComunicacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocComunicacion extends Model
{
    protected $table = "doccomunicacion";
    public $timestamps = false;


    public function scopeListDoc($query, $ventana){
        if($ventana){
            return $query->select(        //quite un * para ingreso y sucursal
                'd.id as doc_id',
                'd.estado',
                DB::raw('convert(concat(\'RA-\', d.fechaproceso, \'-\', d.numero) using latin1) as numero'),
                'd.fechaproceso',
                'd.glosa',
                'd.ventana',
                'p.codigo as periodo')
                ->from('doccomunicacion as d')
                ->join('periodo as p','d.periodo_id','=','p.id')
                ->where('d.ventana',$ventana)
                ->get();
        }
    }

    public static function listadocmd($cVentana){
        return static::selectRaw("i.fechaproceso, convert(concat('RA-', i.fechaproceso, '-', i.numero) using latin1) as numero, i.estado, i.glosa, p.codigo as periodo, i.id, i.ventana")
            ->from('doccomunicacion as i')
            ->join('periodo as p', 'i.periodo_id', '=', 'p.id')
            ->where('i.ventana', $cVentana)
            ->get();
    }

}
