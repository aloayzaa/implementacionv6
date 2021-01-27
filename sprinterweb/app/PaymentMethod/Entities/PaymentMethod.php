<?php

namespace App\PaymentMethod\Entities;

use App\BankCurrentAccount\Entities\BankCurrentAccount;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = "formapago";
    public $timestamps = false;


    public function documentosxPagarFormaPago(){
        return $this->hasMany(DocumentoPorPagarFormaPago::class);
    }
    public function ctactebanco(){
        return $this->belongsTo(BankCurrentAccount::class);
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function findByCodigoOrDescription($term)
    {
        return static::select('*',DB::raw('concat(codigo," | ",descripcion) as name'))
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(15)
            ->get();
    }

    public function scopeBuscar($query, $txtBusca)
    {
        if(trim($txtBusca)!="")
        {
            $query->where('descripcion', 'LIKE',"%$txtBusca%");
        }
    }

    public static function activar($id,$parametro)
    {
        return static::where('id',$id)
            ->update(['estado' => $parametro]);
    }

    public static function lista_search($codigo,$descripcion)
    {
        $term=$codigo." ".$descripcion;

        return static::select('codigo','id','descripcion')
            ->Where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
            ->Where('estado','!=',0)
            ->limit(50)
            ->get();
    }
    //esta por ver si se utiliza
    public static function selectcmd(){
        return static::selectRaw('f.*, c.banco_id, c.moneda_id, b.efectivo')
            ->from('formapago as f')
            ->join('ctactebanco as c', 'f.ctactebanco_id', '=', 'c.id')
            ->join('banco as b', 'c.banco_id', '=', 'b.id')
            ->first();
    }

    public static function selectidcmd($pId){
        return static::selectRaw('d.*, f.codigo as fpg_cod, f.descripcion as fpg_dsc, c.banco_id, c.moneda_id, b.efectivo,
        m.codigo as mpg_cod, m.descripcion as mpg_dsc, db.ventana, d.importe as soles, d.importe as dolares')
            ->from('docxpagar_formapago as d')
            ->leftJoin('docbanco as db', 'd.docbanco_id', '=', 'db.id')
            ->leftJoin('formapago as f', 'd.formapago_id', '=', 'f.id ')
            ->leftJoin('ctactebanco as c', 'f.ctactebanco_id', '=', 'c.id')
            ->leftJoin('banco as b', 'c.banco_id', '=', 'b.id')
            ->leftJoin('mediopago as m', 'd.mediopago_id', '=', 'm.id')
            ->where('d.parent_id', $pId)
            ->get();
    }

    public static function selectidcmd_formapago($formapago_id){
		return static::select("f.*", "c.banco_id", "c.moneda_id", "b.efectivo")
		->from("formapago as f") 
		->join("ctactebanco as c", "f.ctactebanco_id", "=", "c.id")
		->join("banco as b", "c.banco_id", "=", "b.id")
        ->where("f.id", $formapago_id)
        ->first();
    }

    
}
