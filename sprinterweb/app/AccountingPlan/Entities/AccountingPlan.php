<?php

namespace App\AccountingPlan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AccountingPlan extends Model
{
    protected $table = "pcg";
    public $timestamps = false;


    public function scopeElemento($query, $elemento){
        if($elemento){
            return $query->orwhere('codigo', 'LIKE', "$elemento%");
        }
    }

    public static function select2($term){
        return static::select('id','codigo','descripcion', 'es_titulo', 'tipo_auxiliar', 'pide_ccosto')
            ->where('estado', 1)
            ->whereRaw("(descripcion LIKE '%".$term."%' or codigo LIKE '%".$term."%')")
            ->get();
    }

    public static function select2_sales($term){
        return static::select('id','codigo','descripcion', 'es_titulo', 'tipo_auxiliar', 'pide_ccosto')
            ->where('estado', 1)
            ->where('es_titulo', 2)
            ->whereRaw("(descripcion LIKE '%".$term."%' or codigo LIKE '%".$term."%')")
            ->get();
    }

    public static function listado_cuentas_ple()
    {
        return static::select('*')
            ->where('es_titulo', '2')
            ->orderBy('codigo', 'asc')
            ->get();
    }

/*    public static function planCuentasGeneralDatos($id)
    {
        return static::select('pcg.id', 'pcg.codigo', 'pcg.descripcion as descripcion_plan', 'pcg.tipo_cuenta', 'pcg.es_titulo', 'pcg.id', 'pcg.pide_ccosto', 'pcg.pide_op', 'pcg.tipo_auxiliar', 'pcg.tipo_ajuste', 'pcg.tipo_cambio', 'pcg.estado', 'pcg.dbalance', 'm.id as moneda_id', 'm.codigo as codigo_moneda', 'm.descripcion as descripcion_moneda', 'c.id as ctacargo_id', 'c.codigo as codigo_cargo', 'c.descripcion as descripcion_cargo', 'd.id as ctaabono_id', 'd.codigo as codigo_abono', 'd.descripcion as descripcion_abono')
            ->join('moneda as m', 'm.id', '=', 'pcg.moneda_id', 'left')
            ->join('pcg as c', 'c.id', '=', 'pcg.ctacargo_id', 'left')
            ->join('pcg as d', 'd.id', '=', 'pcg.ctaabono_id', 'left')
            ->where('pcg.id', '=', $id)->first();
    }*/

    //Los Scopes si se usan!!

    public function scopeClases(){

        return collect([
             ['desc' => 'Balance', 'value' => 'B'],
             ['desc' => 'Naturaleza', 'value' => 'N'],
             ['desc' => 'Función', 'value' => 'F'],
             ['desc' => 'Naturaleza y Función', 'value' => 'A'],
             ['desc' => '(Ninguno)', 'value' => ""],
        ]);
    }

    public function scopeAuxiliares(){

        return collect([
            ['desc' => '(Ninguno)', 'value' => 'N'],
            ['desc' => 'Terceros', 'value' => 'T'],
            ['desc' => 'Productos', 'value' => 'P'],
        ]);
    }

    public function scopeNaturalezas(){

        return collect([
            ['desc' => 'Deudora', 'value' => 'D'],
            ['desc' => 'Acreedora', 'value' => 'A'],
        ]);
    }

    public function scopeAjustes(){

        return collect([
            ['desc' => '(Ninguno)', 'value' => 'N'],
            ['desc' => 'Al saldo de la cuenta', 'value' => 'C'],
            ['desc' => 'Por las transacciones', 'value' => 'D'],
        ]);
    }

    public function scopeTipoCambio(){

        return collect([
            ['desc' => 'Compra', 'value' => 'C'],
            ['desc' => 'Venta', 'value' => 'V'],
        ]);
    }


}
