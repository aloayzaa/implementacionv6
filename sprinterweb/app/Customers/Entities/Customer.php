<?php

namespace App\Customers\Entities;

use App\Nationalities\Entities\Nationality;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Seller\Entities\Seller;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Ubigeos\Entities\Ubigeo;
use App\Warehouses\Entities\WarehouseIncome;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Customer extends Model
{
    protected $table = "tercero";
    public $timestamps = false;

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public function pedidoAlmacen()
    {
        return $this->hasMany(PedidoAlmacen::class);
    }

    public function ordenCompras()
    {
        return $this->hasMany(OrdenCompra::class);
    }

    public function ingresoAlmacen()
    {
        return $this->hasMany(WarehouseIncome::class);
    }

    public function inventario()
    {
        return $this->hasMany(Inventario::class);
    }

    public function tipovia()
    {
        return $this->belongsTo(Via::class, 'tipovia_id');
    }

    public function tipozona()
    {
        return $this->belongsTo(Zona::class, 'tipozona_id');
    }

    public function documentoide()
    {
        return $this->belongsTo(DocumentoIdentidad::class, 'documentoide_id');
    }

    public function ddni()
    {
        return $this->belongsTo(DocumentoIdentidad::class, 'dni_id');
    }

    public function condicionpagocliente()
    {
        return $this->belongsTo(PaymentCondition::class, 'condicioncobr_id');
    }

    public function condicionpagoproveedor()
    {
        return $this->belongsTo(PaymentCondition::class, 'condicionpago_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(Seller::class, 'vendedor_id');
    }

    public function condicioncobranza()
    {
        return $this->belongsTo(CondicionPago::class, 'condicioncobr_id');
    }

    public function nacionalidad()
    {
        return $this->belongsTo(Nationality::class, 'pais_id');
    }

    public function clasetercero()
    {
        return $this->belongsTo(ThirdClass::class, 'clasetercero_id');
    }

    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'ubigeo_id');
    }

    public function documentoActivo()
    {
        return $this->hasMany(DocumentoActivo::class);
    }

    //DOC X PAGAR
    public function documentosPorPagar()
    {
        return $this->hasMany(DocumentoPorPagar::class);
    }

    public function documentosxPagarNoDomiciliado()
    {
        return $this->hasMany(DocxPagarNoDomiciliado::class);
    }

    public function confirmaServicio()
    {
        return $this->hasMany(ConfirmServicio::class);
    }

    public function notaContableDetalle()
    {
        return $this->hasMany(NotaContableDetalle::class);
    }

    public function docbancodetalle()
    {
        return $this->hasMany(DocBancoDetalle::class);
    }

    public function impuesto()
    {
        return $this->hasMany(Impuesto::class);
    }

    public function sucursal(){
        return $this->belongsTo(Subsidiaries::class,'sucursal_id');
    }

    public function terceroContacto(){
        return $this->hasMany(CustomerContact::class, 'tercero_id');
    }

    public static function personal_asociado(){
        return static::select('t.*')
        ->from('tercero as t')
        ->join('tercero_personal as tp','t.id','=','tp.tercero_id')
        ->where('t.estado',1)
        ->get();
    }

    public static function personal_tercero(){
        return static::select('*')
            ->from ('tercero')
            ->get();
    }

    public static function select2($term){
        return static::select(DB::raw("t.*, c.descripcion as clase,
		convert(ltrim(concat(ifnull(v.simbolo,''), '', t.via_nombre, '', t.via_numero, '', t.via_interior, '', ifnull(z.simbolo,''), '', t.zona_nombre)) using latin1) as direccion,
		u.disprodep as ubigeo"))
            ->from('tercero as t')
            ->leftJoin('ubigeo as u','t.ubigeo_id','=','u.id')
            ->leftJoin('tipovia as v','t.tipovia_id','=','v.id')
            ->leftJoin('tipozona as z', 't.tipozona_id','=','z.id')
            ->leftJoin('clasetercero as c','t.clasetercero_id','=','c.id')
            ->where(DB::raw('concat(t.codigo," ",t.descripcion)'),'LIKE',"%$term%")
            ->Where('t.estado','!=',0)
            ->get();
    }

    public static function tercero_cuenta($tercero_id){
        return static::select('b.codigo','m.simbolo','tc.cuenta')
            ->join('tercero_cuenta as tc', 'tercero.id','=','tc.tercero_id')
            ->join('banco as b', 'tc.banco_id','=','b.id')
            ->join('moneda as m', 'tc.moneda_id','=','m.id')
            ->where('tc.tercero_id', $tercero_id)
            ->get();
    }

    public static function documento_tercero_facturacion($tercero_id){
        return static::select("t.id", "t.descripcion","t.dni", "t.nrodocidentidad as ruc", "d.codsunat") // campos agregados !clasic : id, descripion
        ->from("tercero as t")
        ->join("documentoide as d", "t.documentoide_id", "=", "d.id")
        ->where("t.id", $tercero_id)
        ->first();
    }

    public static function select2_id($tercero_id){
        return static::select(DB::raw("t.*, c.descripcion as clase,
		convert(ltrim(concat(ifnull(v.simbolo,''), '', t.via_nombre, '', t.via_numero, '', t.via_interior, '', ifnull(z.simbolo,''), '', t.zona_nombre)) using latin1) as direccion,
		u.disprodep as ubigeo"))
            ->from('tercero as t')
            ->leftJoin('ubigeo as u','t.ubigeo_id','=','u.id')
            ->leftJoin('tipovia as v','t.tipovia_id','=','v.id')
            ->leftJoin('tipozona as z', 't.tipozona_id','=','z.id')
            ->leftJoin('clasetercero as c','t.clasetercero_id','=','c.id')
            ->where('t.id',$tercero_id)
            ->Where('t.estado','!=',0)
            ->first();
    }
    
}
