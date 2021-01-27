<?php

namespace App\Parancelaria\Entities;

use App\Products\Entities\Productos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Parancelaria extends Model
{
    protected $table = "parancelaria";
    public $timestamps = false;

    public function producto(){
        return $this->hasMany(Productos::class);
    }

    public static function select2($term){
        return static::select('*')
            ->from('parancelaria')
            ->where('codigo', 'like', $term.'%')
            ->orwhere('descripcion', 'like', $term.'%')
            ->get();
    }

    public static function selectparentcmd($pId){
        return static::selectRaw('p.id, p.codigo, p.descripcion, a.codigo as codigo_parancelaria, a.valor, a.isc, a.tipoisc_id, a.tipo, i.tipo as tipoisc')
            ->from('producto as p')
            ->join('parancelaria as a', 'p.parancelaria_id', '=', 'a.id')
            ->join('tipoisc as i', 'a.tipoisc_id', '=', 'i.id')
            ->where('p.id', $pId)
            ->first();
    }

}
