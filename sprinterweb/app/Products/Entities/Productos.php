<?php

namespace App\Products\Entities;

use App\Color\Entities\Color;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\Families\Entities\Familia;
use App\Marks\Entities\Marca;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\Models\Entities\Modelo;
use App\Parancelaria\Entities\Parancelaria;
use App\ProductGroup\Entities\ProductGroup;
use App\Warehouses\Entities\WarehouseIncomeDetail;
use App\Warehouses\Entities\WarehouseOrderDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\fromJSON;

class Productos extends Model
{
    protected $table = "producto";
    public $timestamps = false;

    public function detalleIngresoAlmacen()
    {
        return $this->hasMany(WarehouseIncomeDetail::class);
    }

    public function detallepedido()
    {
        return $this->hasMany(WarehouseOrderDetail::class);
    }

    public function detaildocumenttopay()
    {
        return $this->hasMany(DocumentToPayDetail::class);
    }

    public function unidadmedidacompra(){
        return $this->belongsTo(UnidadesMedida::class, 'ucompra_id');
    }

    public function unidadmedida()
    {
        return $this->belongsTo(UnidadesMedida::class, 'umedida_id');
    }

    public function producto_sunat(){
        return $this->belongsTo(ProductoSunat::class,'productosunat_id');
    }

    public function tabla_marca(){
        return $this->belongsTo(Marca::class,'marca_id');
    }

    public function tabla_modelo(){
        return $this->belongsTo(Modelo::class,'modelo_id');
    }

    public function grupo_producto(){
        return $this->belongsTo(ProductGroup::class,'grupoproducto_id');
    }

    public function color(){
        return $this->belongsTo(Color::class,'color_id');
    }

    public function parancelaria(){
        return $this->belongsTo(Parancelaria::class,'parancelaria_id');
    }

    public function familia(){
        return $this->belongsTo(Familia::class,'familiapdto_id');
    }

    public static function lista_producto_familia()
    {
        return static::select('producto.*', 'f.descripcion as familia_descripcion')
            ->join('familiapdto as f', 'f.id', '=', 'producto.familiapdto_id')
            ->orderBy('id', 'DESC')
            ->orderBy('codigo', 'asc')
            ->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public static function eliminarCascada($id)
    {
        return static::foreign('producto_id')
            ->references('id')
            ->on('producto')
            ->onDelete('cascade')
            ->where('producto.id', '=', $id);
    }

    public static function productos_modal_ordencompra($tipo, $term){
        return static::select(DB::raw('p.*, u.codigo as ucompra_codigo, u.descripcion as ucompra_descripcion, uk.codigo as ukardex_codigo, uk.descripcion as ukardex_descripcion'))
        ->from('producto as p')
        ->join('umedida as u','p.ucompra_id','=','u.id')
        ->join('umedida as uk','p.umedida_id','=','uk.id')
        ->leftJoin('producto_ubicacion as pu','p.id','=','pu.producto_id')
        ->leftJoin('producto_stock as ps','p.id','=','ps.producto_id')
        ->Where('p.estado','!=',0)
        ->where('p.tipoproducto', '=', $tipo)
        ->where('compra', 1)
        ->where(DB::raw('concat(p.codigo," ",p.descripcion)'),'LIKE',"%$term%")
        ->get();
    }
    public static function get_ultimo($codigo_producto){

        if(empty($codigo_producto)){

            return static::select(DB::raw('max(p.codigo) as codigo'))
            ->from('producto as p')
            ->first();

        }else{

            return static::select(DB::raw('max(p.codigo) as codigo'))
            ->from('producto as p')
            ->where('p.codigo','like', $codigo_producto."%")
            ->first();

        }


    }

    public  static  function  selectidcmd ($producto_id){   //clasic, clase producto
        return static::selectRaw('p.*, u.codigo as ume_cod, u.descripcion as ume_dsc, c.codigo as uco_cod, c.descripcion as uco_dsc, ifnull(ma.descripcion,"") as mca_dsc, ifnull(mo.descripcion,"") as
        mod_dsc, ifnull(a.codigo,"") as par_cod, ifnull(a.descripcion,"") as par_dsc')
        ->from('producto as p')
        ->leftjoin('marca as ma','p.marca_id','=','ma.id')
        ->leftjoin('modelo as mo','p.modelo_id','=','mo.id')
        ->leftjoin('parancelaria as a','p.parancelaria_id','=','a.id')
        ->join('umedida as u','p.umedida_id','=','u.id')
        ->join('umedida as c','p.ucompra_id','=','c.id')
        ->where('p.id','=',$producto_id)
        ->first();

    }
    public static function select2($term){
        return static::select('producto.id','producto.codigo','producto.descripcion','producto.umedida_id', 'u.codigo as umedida_codigo','u.descripcion as umedida_descripcion')
            ->join('umedida as u', 'producto.umedida_id', '=', 'u.id')
            ->where(DB::raw('concat(producto.codigo," ",producto.descripcion)'),'LIKE',"%$term%")
            ->Where('producto.estado','!=',0)
            ->get();
    }

    public function ScopeTypeM($query, $term){
        if($term){
            return $query->select('id','codigo','descripcion', 'compra', 'venta')
                ->where(DB::raw('concat(codigo," ",descripcion)'),'LIKE',"%$term%")
                ->Where('tipoproducto', 'M')
                ->Where('estado','!=',0)
                ->get();
        }
    }


    public static function servicios($term){
        return static::select('producto.id','producto.codigo','producto.descripcion','producto.umedida_id', 'u.codigo as umedida_codigo','u.descripcion as umedida_descripcion')
            ->join('umedida as u', 'producto.umedida_id', '=', 'u.id')
            ->Where('producto.estado','!=',0)
            ->where('producto.tipoproducto', 'S')
            ->where(DB::raw('concat(producto.codigo," ",producto.descripcion)'),'LIKE',"%$term%")
            ->get();
    }

    public static function productoum($term){
        return static::select('producto.id','producto.codigo','producto.descripcion','producto.umedida_id', 'u.codigo as umedida_codigo','u.descripcion as umedida_descripcion')
            ->join('umedida as u', 'producto.umedida_id', '=', 'u.id')
            ->Where('producto.estado','!=',0)
            ->where('producto.tipoproducto', 'C')
            ->where(DB::raw('concat(producto.codigo," ",producto.descripcion)'),'LIKE',"%$term%")
            ->get();
    }

    public static function umedidas_producto($producto_id){
        return static::select('uc.id as ucompra_id','uc.codigo as ucompra_codigo','uk.id as ukardex_id','uk.codigo as ukardex_codigo')
            ->join('umedida as uc','producto.ucompra_id','=','uc.id')
            ->join('umedida as uk','producto.umedida_id','=','uk.id')
            ->where('producto.id',$producto_id)
            ->first();
    }

    public function busquedacmd($fecha, $almacen, $sql, $venta) { // Clasic, clase: producto, método busquedacmd
        
        return DB::select('CALL getpdtobusqueda(?,?,?,?)', array($fecha, $almacen, $sql, $venta));
    }

    public static function getctaventapdto_billing($producto_id, $tipoventa_id) { // clasic, clase: _dataman, método: getctaventapdto
        return static::select(DB::raw("t.cuenta_id, c.codigo, c.descripcion, c.pide_ccosto, p.precio1, p.precio2, p.precio3, p.esafecto, p.consumo, c.tipo_auxiliar, t.ctacosto_id, ifnull(c2.codigo,'') as ctacosto_cod, ifnull(c2.descripcion,'') as ctacosto_dsc, ifnull(c2.tipo_auxiliar,'') as ctacosto_aux"))
        ->from('producto as p')
        ->leftJoin('tipoventaxfamilia as t', 'p.familiapdto_id', '=', 't.familiapdto_id')
        ->join('pcg as c', 't.cuenta_id', '=', 'c.id')
        ->leftJoin('pcg as c2', 't.ctacosto_id', '=', 'c2.id')
        ->where('p.id', $producto_id)
        ->where('t.tipoventa_id', $tipoventa_id)
        ->first();
    }
}
