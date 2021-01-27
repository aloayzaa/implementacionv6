<?php

namespace App\SalesTypes\Entities;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Families\Entities\Familia;
use App\Security\Entities\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FamilySaleType extends Model
{
    protected $table = "tipoventaxfamilia";
    public $timestamps = false;

    public function accountSale()
    {
        return $this->belongsToMany(AccountingPlan::class, 'rol_permiso', 'rolid', 'permisoid');
    }

    public function familia()
    {
        return $this->belongsTo(Familia::class, 'familiapdto_id');
    }



    public static function probando_filtro($id_var){
        return static::select('tipoventaxfamilia.id as id_tventa', 'f.descripcion as name','tipoventaxfamilia.familiapdto_id as familiapdto_id','tipoventaxfamilia.tipoventa_id as tipoventa_id','tipoventaxfamilia.cuenta_id as cuenta_id','tipoventaxfamilia.ctacosto_id as ctacosto_id','f.codigo as codigo')
        ->join('familiapdto as f', 'f.id', '=', 'tipoventaxfamilia.familiapdto_id','left')
        ->where('tipoventa_id',$id_var)
        ->where(DB::raw('CHARACTER_LENGTH(codigo)'),'=',5)
        ->get();
    }

    public static function verifica_registro($variable)
    {
        return static::select('id')
        ->Where('familiapdto_id','=',$variable)
        ->get()
        ->first();
    }

    public function familiapdto(){
        return $this->belongsTo(Familia::class);
    }

    public function pCuenta(){
        return $this->belongsTo(AccountingPlan::class,'cuenta_id');
    }
    public function pCtaCuenta(){
        return $this->belongsTo(AccountingPlan::class,'ctacosto_id');
    }

    public static function getctaventapdto($producto_id,$tipoventa_id){
        return static::select('t.cuenta_id', 'c.codigo', 'c.descripcion', 'c.pide_ccosto', 'p.precio1', 'p.precio2', 'p.precio3','p.esafecto', 'p.consumo', 'c.tipo_auxiliar')
        ->from('producto as p')
        ->leftjoin('tipoventaxfamilia as t','p.familiapdto_id','=','t.familiapdto_id')
        ->join('pcg as c','t.cuenta_id','=','c.id')
        ->where('p.id',$producto_id)
        ->where('t.tipoventa_id',$tipoventa_id)
        ->first();
    }

    public static function obtener_data($id){
        return static::selectRaw('t.*, c.codigo, c.descripcion, p.codigo as cta_cod, p.descripcion as cta_dsc, g.codigo as cos_cod, g.descripcion as cos_dsc ')
            ->from('tipoventaxfamilia as t')
            ->leftJoin('pcg as p', 't.cuenta_id', '=', 'p.id')
            ->leftJoin('pcg as g', 't.ctacosto_id', '=', 'g.id')
            ->join('familiapdto as c', 't.familiapdto_id', '=', 'c.id')
            ->where('t.tipoventa_id', $id)
            ->get();
    }
    public static function update_data($tipoventa, $familia, $data)
    {
        return static::where('familiapdto_id', $familia)
            ->where('tipoventa_id', $tipoventa)
            ->update($data);
    }
}

/*
    select t.cuenta_id, c.codigo, c.descripcion, c.pide_ccosto, p.precio1, p.precio2, p.precio3,    p.esafecto, p.consumo, c.tipo_auxiliar
    from comun.producto p
    left join tipoventaxfamilia t on p.familiapdto_id = t.familiapdto_id
    join pcg c on t.cuenta_id = c.id
    where p.id = ?pPdto and t.tipoventa_id = ?pTventa
*/
