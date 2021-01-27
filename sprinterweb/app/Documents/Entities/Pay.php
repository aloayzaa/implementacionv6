<?php

namespace App\Documents\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pay extends Model
{
    protected $table = "docxpagar";
    public $timestamps = false;

    public function scopeBuscar($query, $txtBusca){
        if(trim($txtBusca)!="")
        {
            $query->where(tercero('descripcion'), 'LIKE',"%$txtBusca%");
            //->orWhere('codigo', 'LIKE',"%$txtBusca%");
        }
    }
    public static function lista_percepcion(){
        return static::select('*','tipo.id as tipo_id','tipo.codigo as tipo_codigo','tipo.descripcion as tipo_descripcion','pto.id as pto_id','pto.codigo as pto_codigo','pto.descripcion as pto_descripcion','t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo_moneda','doc.codigo as codigo_documento','d.estado as estado_provision','d.id as id_factura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')
            ->leftjoin('puntoventa as pto','pto.id','=','d.puntoventa_id')
            ->leftjoin('tipoventa as tipo','tipo.id','=','d.tipoventa_id')
            ->where('d.ventana','MOV_PERCEPCION')
//            ->where('d.periodo_id',session_periodo_id())
            ->get();
    }


    public static function lista_retencion(){
        return static::select('*','tipo.id as tipo_id','tipo.codigo as tipo_codigo','tipo.descripcion as tipo_descripcion','pto.id as pto_id','pto.codigo as pto_codigo','pto.descripcion as pto_descripcion','t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo_moneda','doc.codigo as codigo_documento','d.estado as estado_provision','d.id as id_factura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')
            ->leftjoin('puntoventa as pto','pto.id','=','d.puntoventa_id')
            ->leftjoin('tipoventa as tipo','tipo.id','=','d.tipoventa_id')
            ->where('d.ventana','MOV_RETENCION')
//            ->where('d.periodo_id',session_periodo_id())
            ->get();
    }

    public static function lista_facturacion(){
        return static::select('*',DB::raw('concat(doc.codigo, " ", rtrim(d.seriedoc),"-",rtrim(d.numerodoc)) as documento'),'tipo.id as tipo_id','tipo.codigo as tipo_codigo','tipo.descripcion as tipo_descripcion','pto.id as pto_id','pto.codigo as pto_codigo','pto.descripcion as pto_descripcion','t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo_moneda','doc.codigo as codigo_documento','d.estado as estado_provision','d.id as id_factura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')
            ->leftjoin('puntoventa as pto','pto.id','=','d.puntoventa_id')
            ->leftjoin('tipoventa as tipo','tipo.id','=','d.tipoventa_id')
            ->where('d.ventana','MOV_FACTURAVENTA')
//            ->where('d.periodo_id',session_periodo_id())
            ->orderby('documento','asc')
            ->get();
    }


    public static function lista_apertura_cobrar(){
        return static::select('*','t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo_moneda','doc.codigo as codigo_documento','d.estado as estado_provision','d.id as id_apertura')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')
            ->where('d.ventana','mov_aperturaxcobrar')
//            ->where('d.periodo_id',session_periodo_id())
            ->get();
    }

    public static function lista_nota_credito_debito(){
        return static::select('*',DB::raw('concat(doc.codigo, " ", rtrim(d.seriedoc),"-",rtrim(d.numerodoc)) as documento'),DB::raw('convert(concat(p.codigo, "-", RIGHT(concat("0000", d.numero),5)) using latin1) as numope'),'t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo_moneda','doc.codigo as codigo_documento','d.estado as estado_provision','d.id as id_notacredito')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
            ->leftjoin('periodo as p','p.id','=','d.periodo_id')
            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')

            ->where('d.ventana','MOV_NCREDITOCOMPRA')
//            ->where('d.periodo_id',session_periodo_id())
            ->orderby('documento','asc')
            ->get();
    }
    public static function lista_provisiones(){
        return static::select('*',DB::raw('concat(doc.codigo, " ", rtrim(d.seriedoc),"-",rtrim(d.numerodoc)) as documento'),DB::raw('convert(concat(p.codigo, "-", RIGHT(concat("0000", d.numero),5)) using latin1) as numope'),'t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','m.simbolo as simbolo_moneda','doc.codigo as codigo_documento','d.estado as estado_provision','d.id as id_provision')
            ->from('docxpagar as d')
            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
            ->leftjoin('periodo as p','p.id','=','d.periodo_id')
            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')
            //->leftjoin('documentocom  as dc','dc.documento_id','=','d.id')

            ->where('d.ventana','mov_docxpagar')
//            ->where('d.periodo_id',session_periodo_id())
            ->orderby('documento','asc')
            ->get();
    }
    public static function lista_apertura(){
        return static::select('*')
            ->from('docxpagar as d')

//            ->leftjoin('tercero as t','t.id','=','d.tercero_id')
//            ->leftjoin('moneda as m','m.id','=','d.moneda_id')
//            ->leftjoin('documentocom as doc','doc.id','=','d.documento_id')

            ->where('d.observaciones','Apertura por cobrar')
//            ->where('d.periodo_id',session_periodo_id())
            ->get();
    }
    public static function listado_otras_provisiones(){

        return static::select('*',DB::raw('concat(doc.codigo, " ", rtrim(da.seriedoc),"-",rtrim(da.numerodoc)) as documento'),'t.codigo as codigo_tercero','t.descripcion as descripcion_tercero','da.estado as estado','da.id as id','m.simbolo as simbolo_moneda')
            ->from('docxpagar as da')
            ->leftjoin('tercero as t','t.id','=','da.tercero_id')
            ->leftjoin('moneda as m','m.id','=','da.moneda_id')
            ->leftjoin('documentocom as doc','doc.id','=','da.documento_id')
            ->where('da.ventana','mov_generaxpagar')
//            ->where('da.periodo_id',session_periodo_id())
            ->orderby('documento','asc')
            ->get();

    }

    public static function devuelveId($variable)
    {
        return static::select('id')
            ->Where('ingresoalmacen_id','=',$variable)
            ->get()
            ->first();
    }

    public static function verificarIngreso($id)
    {
        return static::select('id')
            ->Where('ordencompra_id','=',"$id")
            ->get()
            ->first();
    }

    public static function anular($id,$parametro,$data)
    {
        return static::where('id',$id)
            ->update($data);
    }

    public static function activar($id,$parametro,$data)
    {
        return static::where('id',$id)
            ->update($data);
    }

    //INGRESO ALMACEN
    public function ingresoAlmacen(){
        return $this->hasMany(IngresoAlmacen::class);
    }

    //INICIO PROVISIONES POR PAGAR
    public function periodo(){
        return $this->belongsTo(Periodo::class,'periodo_id');
    }
    public function unidadNegocio(){
        return $this->belongsTo(BusinessUnit::class,'unegocio_id');
    }
    public function tercero(){
        return $this->belongsTo(Tercero::class,'tercero_id');
    }
    public function puntoVenta(){
        return $this->belongsTo(PuntoVenta::class,'puntoventa_id');
    }
    public function sucursal(){
        return $this->belongsTo(Sucursal::class,'sucursal_id');
    }
    public function almacen(){
        return $this->belongsTo(Almacen::class,'almacen_id');
    }
    public function tipoTransaccion(){
        return $this->belongsTo(TransactionType::class,'tipotransaccion_id');
    }
    public function tipoCompra(){
        return $this->belongsTo(TipoCompra::class,'tipocompra_id');
    }
    public function tipoVenta(){
        return $this->belongsTo(TipoVenta::class,'tipoventa_id');
    }
    public function moneda(){
        return $this->belongsTo(Moneda::class,'moneda_id');
    }
    public function documentocom(){
        return $this->belongsTo(DocumentoCompra::class,'documento_id');
    }
    public function condicionPago(){
        return $this->belongsTo(CondicionPago::class,'condicionpago_id');
    }
    public function vendedor(){
        return $this->belongsTo(Vendedor::class,'vendedor_id');
    }
    public function tipoNotaCredito(){
        return $this->belongsTo(TipoNotaCredito::class,'tiponotacredito_id');
    }
    public function tipoNotaDebito(){
        return $this->belongsTo(TipoNotaDebito::class,'tiponotadebito_id');
    }
    public function tipoAfectaIgv(){
        return $this->belongsTo(TipoAfectaIgv::class,'tipoafectaigv_id');
    }
    public function impuesto(){
        return $this->belongsTo(Impuesto::class,'impuesto_id');
    }
    public function impuesto2(){
        return $this->belongsTo(Impuesto::class,'impuesto2_id');
    }
    public function impuesto3(){
        return $this->belongsTo(Impuesto::class,'impuesto3_id');
    }
    public function impuesto4(){
        return $this->belongsTo(Impuesto::class,'impuesto4_id');
    }
    public function impuesto5(){
        return $this->belongsTo(Impuesto::class,'impuesto5_id');
    }
    public function tipoBienServicio(){
        return $this->belongsTo(TipoBienServicio::class,'tipobienservicio_id');
    }

    public function ordenCompra(){
        return $this->belongsTo(OrdenCompra::class,'ordencompra_id');
    }
    public function cotizacion(){
        return $this->belongsTo(Cotizacion::class,'cotizacion_id');
    }
    public function ordenTrabajo(){
        return $this->belongsTo(OrdenTrabajo::class,'ordentrabajo_id');
    }
    public function pedidoAlmacen(){
        return $this->belongsTo(PedidoAlmacen::class,'pedidoalmacen_id');
    }
    public function guiaTransporte(){
        return $this->belongsTo(GuiaTransporte::class,'guiatransporte_id');
    }

    public function documentosxPagarNoDomiciliado(){
        return $this->hasMany(DocxPagarNoDomiciliado::class);
    }
    public function documentosxPagarDetraccion(){
        return $this->hasMany(DocumentoPorPagarDetraccion::class);
    }
    public function documentosxPagarDetalle(){
        return $this->hasMany(DocumentoPorPagarDetalle::class);
    }

    //REFERENCIA
    public function referencia(){
        return $this->hasMany(Referencia::class);
    }

    public static function buscaDocProvision($id_parent){
        return static::select('*')
            ->where('ingresoalmacen_id','=',$id_parent)
            ->get();
    }

    public static function idCantidadRefactorizado_liqui($parent_id)
    {
        return static::select(DB::raw('count(*) as idCantidad'))
            ->where('id','=',$parent_id)
            //->where('item','=',$item)
            ->get()
            ->first();
    }

    public static function ultimo_numero_serie($seriedoc,$documento_id,$origen)
    {
        return static::select('numerodoc')
            ->Where('seriedoc','=',$seriedoc)
            ->Where('documento_id','=',$documento_id)
            ->Where('origen','=',$origen)
            ->orderBy('numerodoc','desc')
            ->get()
            ->first();
    }
    public function docbancodetalle(){
        return $this->hasMany(DocBancoDetalle::class);
    }

    public static function verifica_documento_registro($tercero_id,$seriedoc,$documento_id,$origen,$numerodoc)
    {
        return static::select('numerodoc')
            ->Where('tercero_id','=',$tercero_id)
            ->Where('seriedoc','=',$seriedoc)
            ->Where('documento_id','=',$documento_id)
            ->Where('origen','=',$origen)
            ->Where('numerodoc','=',$numerodoc)
            ->get()
            ->first();
    }

    public static function filtrar_referencia_ncd($id_tercero,$fecha_hasta){
        return static::select(DB::raw("c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento, fechadoc as fecha, t.descripcion as nombre,
                    c.moneda_id, c.tcambio, c.ventana, c.glosa"))
            ->from('docxpagar as c')
            ->join('tercero as t','c.tercero_id','=','t.id')
            ->join('documentocom as d','c.documento_id','=','d.id')
            ->where('c.tercero_id',$id_tercero)
            ->where('c.origen','P')
            ->where('c.estado','<>','ANULADO')
            ->where('d.codsunat','<>','')
            ->where('c.fechaproceso','<=',$fecha_hasta)
            ->whereNotIn('d.codigo', ['07','08'])
            ->get();

        /*IF !EMPTY(ldDesde)
            TEXT TO lcSql TEXTMERGE NOSHOW PRETEXT 2
                SELECT c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento, fechadoc as fecha, t.descripcion as nombre,
                    c.moneda_id, c.tcambio, c.ventana, c.glosa
                FROM docxpagar c
                join comun.tercero t on c.tercero_id = t.id
                join comun.documentocom d on c.documento_id = d.id
                where c.tercero_id = <<lnTercero>> and c.fechaproceso between '<<ldDesde>>' and '<<ldFecha>>'
                    and c.seriedoc like '%<<lcSerie>>%' and c.numerodoc like '%<<lcNumero>>%'
                    and c.origen = '<<thisform.corigen>>' and c.estado <> 'ANULADO' and d.factor = <<thisform.nfactor>> and d.codsunat <> ''
                    and c.id <> <<lnId>>
            ENDTEXT
        ELSE
            TEXT TO lcSql TEXTMERGE NOSHOW PRETEXT 2
                SELECT c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento, fechadoc as fecha, t.descripcion as nombre,
                    c.moneda_id, c.tcambio, c.ventana, c.glosa
                FROM docxpagar c
                join comun.tercero t on c.tercero_id = t.id
                join comun.documentocom d on c.documento_id = d.id
                where c.tercero_id = <<lnTercero>> and c.fechaproceso <= '<<ldFecha>>'
                    and c.seriedoc like '%<<lcSerie>>%' and c.numerodoc like '%<<lcNumero>>%'
                    and c.origen = '<<thisform.corigen>>' and c.estado <> 'ANULADO' and d.factor = <<thisform.nfactor>>0 and d.codsunat <> ''
                    and c.id <> <<lnId>>
            ENDTEXT
        ENDIF*/
    }

    public static function filtrar_referencia_ncd_ventas($id_tercero,$fecha_hasta){
        return static::select(DB::raw("c.id, concat(d.codigo, ' ', c.seriedoc, '-', c.numerodoc) as documento, fechadoc as fecha, t.descripcion as nombre,
                    c.moneda_id, c.tcambio, c.ventana, c.glosa"))
            ->from('docxpagar as c')
            ->join('tercero as t','c.tercero_id','=','t.id')
            ->join('documentocom as d','c.documento_id','=','d.id')
            ->where('c.tercero_id',$id_tercero)
            ->where('c.origen','C')
            ->where('c.estado','<>','ANULADO')
            ->where('d.codsunat','<>','')
            ->where('c.fechaproceso','<=',$fecha_hasta)
            ->whereNotIn('d.codigo', ['07','08'])
            ->get();
    }

    public static function filtrar_referenciadetalle_ncd($id_referencia){

        // return static::select(DB::raw("d.*,p.codigo,p.descripcion,u.codigo ume_cod"));

        /*return static::select(DB::raw("d.*,p.codigo,p.descripcion,u.codigo as ume_cod"))
        ->from('ingresoalmacen_detalle as d')
        ->join('ingresoalmacen as i','d.parent_id','=','i.id')
        ->join('docxpagar as c','c.ingresoalmacen_id','=','i.id')
        ->join('producto as p','d.producto_id','=','p.id')
        ->join('umedida as u','d.umedida_id','=','u.id')
        ->where('c.id',$id_referencia)
        ->get();*/
        return static::select('*')
            ->from('docxpagar_detalle as d')
            ->where('d.parent_id',$id_referencia)
            ->get();

    }

    /*
    lcSql = 'select d.*,p.codigo,p.descripcion,u.codigo ume_cod
    from ingresoalmacen_detalle d join ingresoalmacen i on d.parent_id = i.id '+;
                    'join docxpagar c on c.ingresoalmacen_id = i.id join comun.producto p on d.producto_id = p.id '+;
                    'join comun.umedida u on d.umedida_id = u.id where c.id = ' + STR(referencia_id,11,0)
            odet.getquerycmd(lcSql, '_tmp')
    */
    public static function comprobar_registro_existente_compras($ruc,$id_doc,$serie_doc,$num_doc,$origen){
        return static::select('id','ruc','documento_id','seriedoc','numerodoc','origen')
            ->Where('ruc',$ruc)
            ->Where('documento_id',$id_doc)
            ->Where('seriedoc',$serie_doc)
            ->Where('numerodoc',$num_doc)
            ->Where('origen',$origen)
            ->first();
    }
    public static function comprobar_registro_existente_ventas($id_doc,$serie_doc,$num_doc,$origen){
        return static::select('id','documento_id','seriedoc','numerodoc','origen')
            ->Where('documento_id',$id_doc)
            ->Where('seriedoc',$serie_doc)
            ->Where('numerodoc',$num_doc)
            ->Where('origen',$origen)
            ->first();
    }
}
