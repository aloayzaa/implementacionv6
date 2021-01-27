<?php

namespace App\CategoriesCtaCte\Entities;

use App\Documents\Entities\Commercial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoriesCtaCteDetail extends Model
{
    protected $table = "categctacte_detalle";
    public $timestamps = false;

    public function documentos(){
        return $this->belongsTo(Commercial::class,'documento_id');
    }

    public static function update_data($id, $doc, $data)
    {
        return static::where('parent_id', $id)
            ->where('documento_id', $doc)
            ->update($data);
    }

    public static function data($id){
        return static::select('t.codigo')
            ->from('categctacte_detalle as d')
            ->join('documentocom as t', 'd.documento_id','=','t.id')
            ->where('d.parent_id',$id)
            ->get();
    }
}
