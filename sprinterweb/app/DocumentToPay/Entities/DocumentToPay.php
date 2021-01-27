<?php

namespace App\DocumentToPay\Entities;

use App\BusinessUnit\Entities\BusinessUnit;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Documents\Entities\Commercial;
use App\GoodsServicesType\Entities\GoodsServicesType;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\PurchaseOrder\Entities\PurchaseOrder;
use App\PurchasesType\Entities\PurchasesType;
use App\SalesTypes\Entities\SaleType;
use App\Subsidiaries\Entities\Subsidiaries;
use App\TransactionType\Entities\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Warehouses\Entities\WarehouseIncome;

class DocumentToPay extends Model
{
    protected $table = "docxpagar";
    public $timestamps = false;

/*    public function parent(){
        return $this->belongsTo(DocumentToPay::class);
    }*/

    public function parent(){
        return $this->hasOne(DocumentToPay::class, 'detraccion_id');
    }

    public function formapago(){
        return $this->hasMany(DocumentToPayPaymentWay::class, 'parent_id');
    }

    public function ordencompra(){
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function tipotransaccion(){
        return $this->belongsTo(TransactionType::class);
    }

    public function unegocio(){
        return $this->belongsTo(BusinessUnit::class);
    }

    public function documento()
    {
        return $this->belongsTo(Commercial::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Period::class, 'periodo_id');
    }

    public function condicionpago()
    {
        return $this->belongsTo(PaymentCondition::class);
    }

    public function tipoCompra()
    {
        return $this->belongsTo(PurchasesType::class, 'tipocompra_id');
    }

    public function tercero()
    {
        return $this->belongsTo(Customer::class, 'tercero_id');
    }

    public function tipoBienServicio()
    {
        return $this->belongsTo(GoodsServicesType::class, 'tipobienservicio_id');
    }

    public function tipoventa(){
        return $this->belongsTo(SaleType::class);
    }

    public function puntoventa(){
        return $this->belongsTo(PointSale::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'moneda_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Subsidiaries::class, 'sucursal_id');
    }

    public function ingresoAlmacen(){

        return $this->hasMany(WarehouseIncome::class, 'docxpagar_id');

    }

    public function getCodigoProvisionAttribute()
    {
        return "{$this->documento->codigo} {$this->seriedoc}-{$this->numerodoc}";
    }

    public function moneda(){
        return $this->belongsTo(Currency::class, 'moneda_id');
    }
    public static function lista_apertura()
    {
        return static::select('*', 't.codigo as codigo_tercero', 't.descripcion as descripcion_tercero', 'm.simbolo as simbolo_moneda', 'doc.codigo as codigo_documento', 'd.estado as estado_provision', 'd.id as id_apertura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t', 't.id', '=', 'd.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'd.moneda_id')
            ->leftjoin('documentocom as doc', 'doc.id', '=', 'd.documento_id')
            ->where('d.ventana', 'mov_aperturaxpagar')
            ->where('d.periodo_id', Session::get('period_id'))
            ->get();
    }

    public static function lista_aperturaBilling()
    {
        return static::select('*')
            ->from('docxpagar as d')
            ->where('d.observaciones', 'Apertura por cobrar')
            ->get();
    }

    public static function lista_aperturaReceivable()
    {
        return static::select('*')
            ->from('docxpagar as d')
            ->where('d.observaciones', 'Apertura por cobrar')
            ->get();
    }


    public static function lista_aperturaRecordVoidedDocuments()
    {
        return static::select('*')
            ->from('docxpagar as d')
            ->where('d.observaciones', 'Documento anulado')
            ->get();
    }

    public static function anular($id, $parametro)
    {
        return static::where('id', $id)
            ->update(['estado' => $parametro]);
    }

    public function scopeListaProvisiones($query, $periodo){

        if($periodo){
            return $query->select(
                DB::raw('concat(doc.codigo, " ", rtrim(d.seriedoc),"-",rtrim(d.numerodoc)) as documento'),
                DB::raw('convert(concat(p.codigo, "-", RIGHT(concat("0000", d.numero),5)) using latin1) as numope'),
                DB::raw('base + inafecto as num'),
                't.codigo as codigo_tercero',
                't.descripcion as descripcion_tercero',
                'm.simbolo as simbolo_moneda',
                'doc.codigo as codigo_documento',
                'd.numerodoc',
                'd.fechadoc',
                'd.impuesto',
                'd.impuesto2',
                'd.total',
                'd.glosa',
                'd.estado',
                'd.id as id')
                ->from('docxpagar as d')
                ->leftjoin('tercero as t', 't.id', '=', 'd.tercero_id')
                ->leftjoin('moneda as m', 'm.id', '=', 'd.moneda_id')
                ->leftjoin('periodo as p', 'p.id', '=', 'd.periodo_id')
                ->leftjoin('documentocom as doc', 'doc.id', '=', 'd.documento_id')
                ->where('d.ventana', 'mov_docxpagar')
                ->where('d.periodo_id', $periodo)
                ->orderby('documento', 'asc')
                ->get();
        }

    }

    public static function lista_nota_credito_debito()
    {
        return static::select('*', DB::raw('concat(doc.codigo, " ", rtrim(d.seriedoc),"-",rtrim(d.numerodoc)) as documento'),
            DB::raw('convert(concat(p.codigo, "-", RIGHT(concat("0000", d.numero),5)) using latin1) as numope'),
            DB::raw('base + inafecto as num'),
            't.codigo as codigo_tercero', 't.descripcion as descripcion_tercero', 'm.simbolo as simbolo_moneda', 'd.id as id',
            'doc.codigo as codigo_documento', 'd.estado as estado', 'd.id as id_notacredito')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t', 't.id', '=', 'd.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'd.moneda_id')
            ->leftjoin('periodo as p', 'p.id', '=', 'd.periodo_id')
            ->leftjoin('documentocom as doc', 'doc.id', '=', 'd.documento_id')
            ->where('d.ventana', 'MOV_NCREDITOCOMPRA')
            ->where('d.periodo_id', Session::get('period_id'))
            ->orderby('documento', 'asc')
            ->get();
    }

    public static function lista_apertura_cobrar()
    {
        return static::select('*', 't.codigo as codigo_tercero', 't.descripcion as descripcion_tercero', 'm.simbolo as simbolo_moneda', 'doc.codigo as codigo_documento', 'd.estado as estado_provision', 'd.id as id_apertura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t', 't.id', '=', 'd.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'd.moneda_id')
            ->leftjoin('documentocom as doc', 'doc.id', '=', 'd.documento_id')
            ->where('d.ventana', 'mov_aperturaxcobrar')
            ->get();
    }

    public static function filtrar_referencia_ncd($id_tercero, $fecha_hasta)
    {
        return static::select(DB::raw("c.id as id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento,
                    fechadoc as fecha, t.descripcion as nombre, c.moneda_id, c.tcambio, c.ventana, c.glosa as glosa"))
            ->from('docxpagar as c')
            ->join('tercero as t', 'c.tercero_id', '=', 't.id')
            ->join('documentocom as d', 'c.documento_id', '=', 'd.id')
            ->where('c.tercero_id', $id_tercero)
            ->where('c.origen', 'P')
            ->where('c.estado', '<>', 'ANULADO')
            ->where('d.codsunat', '<>', '')
            ->where('c.fechaproceso', '<=', $fecha_hasta)
            ->whereNotIn('d.codigo', ['07', '08'])
            ->get();
    }

    public static function filtrar_referenciadetalle_ncd($id_referencia)
    {
        return static::select('d.parent_id as parent_id', 'pcg.codigo as codigo', 'pcg.descripcion as descripcion', 'd.importe as importe')
            ->from('docxpagar_detalle as d')
            ->leftjoin('producto as p', 'd.producto_id', '=', 'p.id')
            ->leftjoin('pcg as pcg', 'd.cuenta_id', '=', 'pcg.id')
            ->where('d.parent_id', $id_referencia)
            ->get();
    }

    public static function lista_liquidaciongasto($periodo)
    {
        return static::select('*', 'c.fechaproceso as fechaproceso', DB::raw('concat(dc.codigo, " ", rtrim(c.seriedoc),"-",rtrim(c.numerodoc)) as documento'), 'c.fechadoc as fechadoc', 't.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo moneda',
            'c.total as total','c.estado as estado')->from('docxpagar as c')
            ->leftjoin('docxpagar  as l', 'l.liquidacion_id', '=', 'c.id')
            ->leftjoin('docxpagar_detalle   as d', 'd.parent_id', '=', 'l.id')
            ->leftjoin( 'tercero as t' ,'c.tercero_id' ,'=', 't.id')
            ->leftjoin( 'tercero as tl' ,'l.tercero_id' ,'=', 'tl.id')
            ->leftjoin( 'pcg as p' ,'d.cuenta_id' ,'=', 'p.id')
            ->leftjoin('moneda as m', 'l.moneda_id', '=', 'm.id')
            ->leftjoin('periodo as g', 'g.id', '=', 'c.periodo_id')
            ->leftjoin('documentocom as dc', 'dc.id', '=', 'c.documento_id')
            ->leftjoin('documentocom as dl', 'dl.id', '=', 'l.documento_id')
            ->leftjoin('centrocosto  as o', 'o.id', '=', 'd.centrocosto_id')
            ->leftjoin('proyecto  as y', 'y.id', '=', 'd.proyecto_id')
            ->leftjoin('actividad  as a', 'a.id', '=', 'd.actividad_id')
            ->where('c.ventana', 'mov_liquidaciongasto')
            ->where('l.periodo_id',$periodo )
            ->orderby('l.numero')
            ->get();
    }

    public static function verifica_documento_registro($tercero_id, $seriedoc, $documento_id, $origen, $numerodoc)
    {
        return static::select('numerodoc')
            ->Where('tercero_id', '=', $tercero_id)
            ->Where('seriedoc', '=', $seriedoc)
            ->Where('documento_id', '=', $documento_id)
            ->Where('origen', '=', $origen)
            ->Where('numerodoc', '=', $numerodoc)
            ->first();
    }

    public static function listado_otras_provisiones()
    {

        return static::select('*', DB::raw('concat(doc.codigo, " ", rtrim(da.seriedoc),"-",rtrim(da.numerodoc)) as documento'),
            't.codigo as codigo_tercero', 't.descripcion as descripcion_tercero', 'da.estado as estado', 'da.id as id', 'm.simbolo as simbolo_moneda')
            ->from('docxpagar as da')
            ->leftjoin('tercero as t', 't.id', '=', 'da.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'da.moneda_id')
            ->leftjoin('documentocom as doc', 'doc.id', '=', 'da.documento_id')
            ->where('da.ventana', 'mov_generaxpagar')
            ->where('da.periodo_id', Session::get('period_id'))
            ->orderby('documento', 'asc')
            ->get();

    }

    public static function ultimo_numero_serie($seriedoc, $documento_id, $origen)
    {
        return static::select('numerodoc')
            ->Where('seriedoc', '=', $seriedoc)
            ->Where('documento_id', '=', $documento_id)
            ->Where('origen', '=', $origen)
            ->orderBy('numerodoc', 'desc')
            ->first();
    }

    public static function lista_retencion()
    {
        return static::select('*', 'tipo.id as tipo_id', 'tipo.codigo as tipo_codigo', 'tipo.descripcion as tipo_descripcion', 'pto.id as pto_id', 'pto.codigo as pto_codigo', 'pto.descripcion as pto_descripcion', 't.codigo as codigo_tercero', 't.descripcion as descripcion_tercero', 'm.simbolo as simbolo_moneda', 'doc.codigo as codigo_documento', 'd.estado as estado_provision', 'd.id as id_factura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t', 't.id', '=', 'd.tercero_id')
            ->leftjoin('moneda as m', 'm.id', '=', 'd.moneda_id')
            ->leftjoin('documentocom as doc', 'doc.id', '=', 'd.documento_id')
            ->leftjoin('puntoventa as pto', 'pto.id', '=', 'd.puntoventa_id')
            ->leftjoin('tipoventa as tipo', 'tipo.id', '=', 'd.tipoventa_id')
            ->where('d.ventana', 'MOV_RETENCION')
            ->where('d.periodo_id', Session::get('period_id'))
            ->get();
    }

    public static function data_ordencompra($ordencompra_id){
        return static::select('d.*',DB::raw("concat( doc.codigo,' ', d.seriedoc,'-', d.numerodoc) as referencia"))
        ->from('docxpagar as d')
        ->leftjoin('documentocom as doc', 'd.documento_id', '=', 'doc.id')
        ->where('d.ordencompra_id', $ordencompra_id)
        ->get();
    }

    public static function importe_referencia_docbanco($docxpagar_id){
        return static::select('c.origen','d.codsunat','c.periodoemi')
        ->from('docxpagar as c')
        ->join('documentocom as d','c.documento_id','=','d.id')
        ->where('c.id', $docxpagar_id)
        ->first();
    }

    public static function percepcion_referencia_docbanco($docxpagar_id){
        return static::select('d.parent_id','c.impuesto2')
        ->from('docxpagar as c')
        ->leftJoin('docbanco_detalle as d','c.id','=','d.referencia_id')
        ->where('c.id', $docxpagar_id)
        ->where('c.conpercepcion', 0)
        ->where('c.impuesto2_id','<>',null)
        ->first();
    }

    public static function retencion_referencia_docbanco($docxpagar_id){
        return static::select(DB::raw('c.fechadoc,c.impuesto,c.detraccion_id,c.total*(case m.tipo when \'N\' then 1 else c.tcambio end) as total '))
        ->from('docxpagar as c')
        ->join('moneda as m','c.moneda_id','=','m.id')
        ->where('c.id', $docxpagar_id)
        ->first();
    }

    public static function retencion_referencia_docbanco2($docxpagar_id){
        return static::select('c.impuesto4','i.documento_id')
        ->from('docxpagar as c')
        ->join('impuesto as i','c.impuesto4_id','=','i.id')
        ->where('c.id', $docxpagar_id)
        ->first();
    }

    public static function retencion_renta_pension($docxpagar_id){
        return static::select(DB::raw('c.moneda_id, c.impuesto3, SUM(ifnull(d.retencion_renta,0)*if(ifnull(b.moneda_id,1)=1,1,b.tcambio)) ret_rentamn, sum(ifnull(d.retencion_renta,0)/if(b.moneda_id=1,b.tcambio,1)) ret_rentame, c.impuesto5, c.total'))
        ->from('docxpagar as c')
        ->leftJoin('docbanco_detalle as d','c.id','=','d.referencia_id')
        ->leftJoin('docbanco as b','d.parent_id','=','b.id')
        ->where('c.id', $docxpagar_id)
        ->first();
    }

    public static function listadoCmd($ventana){ //MOV_FACTURAVENTA
        return DB::select("select i.id, p.codigo as periodo, i.numero, i.fechadoc, i.tcambio, i.fechaproceso,
		pv.descripcion as puntoventa, s.descripcion as sucursal, tv.descripcion as tipoventa, tc.descripcion as tipocompra,
		dc.codigo as tipodoc, concat(dc.codigo, ' ', rtrim(i.seriedoc),'-',rtrim(i.numerodoc)) as documento, m.simbolo as moneda,
		t.codigo, case when i.razonsocial='' then t.descripcion else i.razonsocial end as tercero, i.razonsocial, i.ruc, i.direccion,
		i.base, i.inafecto, i.impuesto, i.impuesto2, i.impuesto3, i.impuesto4, i.total, ifnull(d.totaldetraccion,0) as totaldetraccion,
		i.ventana, i.estado, i.origen, i.centraliza, i.glosa, convert(concat(p.codigo, '-', RIGHT(concat('0000', i.numero),5)) using latin1) as numope
		from docxpagar i
		left join docxpagar_detraccion d on d.parent_id = i.id
		join tercero t on i.tercero_id=t.id
		left join puntoventa pv on i.puntoventa_id = pv.id
		join periodo p on i.periodo_id=p.id
		left join tipoventa tv on i.tipoventa_id = tv.id
		left join tipocompra tc on i.tipocompra_id = tc.id
		join documentocom dc on i.documento_id=dc.id
		join sucursal s on i.sucursal_id=s.id
		join moneda m on i.moneda_id=m.id
		where i.ventana = '$ventana'
        and i.periodo_id = ". Session::get('period_id'));
    }

    public static function ultimonumero($documento_id, $serie, $origen){ //clase docxpagar - método ultimonumero
        return static::select("numerodoc", "fechadoc")
        ->where("documento_id", $documento_id)
        ->where("seriedoc", $serie)
        ->where("origen", $origen)
        ->orderBy("numerodoc", "desc")
        ->first();
    }

    public static function validadoc($documento_id, $serie, $numero, $origen){
        return static::select("c.id", "c.tercero_id", "d.controlnum", "c.origen")
        ->from("docxpagar as c") 
        ->join("documentocom as d", "c.documento_id", "=", "d.id")
        ->where("c.documento_id", $documento_id)
        ->where("c.seriedoc", $serie)   
        ->where("c.numerodoc", $numero)
        ->where("c.origen", $origen)
        ->first();
    }

    public static function selectidcmd_billing($docxpgar_id){ // clasic, clase docxpagar, método: selectidcmd

        return static::select(DB::raw("d.*, dc.codigo as tipodoc, dc.descripcion as doc_dsc, m.tipo as tipomoneda, m.simbolo as moneda, m.codsunat as codmoneda, dc.factor, 
        concat(dc.codigo,' ',RIGHT(d.seriedoc,4),'-',RIGHT(d.numerodoc,7)) as documento, dc.codsunat, ifnull(v.esanticipo,0) as esanticipo, ifnull(v.esgratuito,0) as esgratuito"))
        ->from('docxpagar as d')
        ->join('documentocom as dc', 'd.documento_id', '=', 'dc.id')
        ->leftJoin('tipoventa as v', 'd.tipoventa_id', '=', 'v.id')
        ->join('moneda as m', 'd.moneda_id', '=', 'm.id')
        ->where('d.id', $docxpgar_id)
        ->first();

    }

    public static function liberar_orden_trabajo_billing($docxpagar_id, $data){

        return static::where('id', $docxpagar_id)
            ->update($data);  

    }

    public function buscar_nota_creditodebito_desde($tercero_id, $desde, $hasta, $serie, $numero, $origen, $factor, $docxpagar_id){ // clasic, formulario: aux_aplicanc, objeto: btnrefresh, método: click
        // fecha = hasta
        return  DB::select("select c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento, fechadoc as fecha, t.descripcion as nombre,
        c.moneda_id, c.tcambio, c.ventana, c.glosa
        FROM docxpagar as c
        join tercero as t on c.tercero_id = t.id
        join documentocom as d on c.documento_id = d.id
        where c.tercero_id = $tercero_id  and c.fechaproceso between '$desde' and '$hasta'
        and c.seriedoc like '%$serie%' and c.numerodoc like '%$numero%'
        and c.origen = '$origen' and c.estado <> 'ANULADO' and d.factor = $factor and d.codsunat <> ''
        and c.id <> $docxpagar_id");

    }

    public function buscar_nota_creditodebito($tercero_id, $hasta, $serie, $numero, $origen, $factor, $docxpagar_id){
        // fecha = hasta
        return DB::select("select c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento, fechadoc as fecha, t.descripcion as nombre,
        c.moneda_id, c.tcambio, c.ventana, c.glosa
        FROM docxpagar as c
        join tercero as t on c.tercero_id = t.id
        join documentocom as d on c.documento_id = d.id
        where c.tercero_id = $tercero_id and c.fechaproceso <= '$hasta'
        and c.seriedoc like '%$serie%' and c.numerodoc like '%$numero%'
        and c.origen = '$origen' and c.estado <> 'ANULADO' and d.factor = $factor and d.codsunat <> ''
        and c.id <> $docxpagar_id");

    }

    public function setanulacpe($docxpagar_id, $usuario){

        return DB::select('CALL setanulacpe (?,?)', array($docxpagar_id, $usuario));

    }

    public static function actualizar_estado($id, $estado){

        return static::where('id', $id)
        ->update(['estado' => $estado]);

    }
}
