<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
use App\Http\Requests\PointOfSaleRequest;
use App\Warehouses\Entities\WarehouseIncome;
use App\DocumentToPay\Entities\DocumentToPayPaymentWay;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Customers\Entities\CustomerAddress;
use App\Documents\Entities\Identification;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Http\Traits\Test;
use App\Panel\Companies\Entities\Pempresa;
use App\PaymentMethod\Entities\PaymentMethod;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Http\Traits\Fields\PurchaseDocument;
use App\Parancelaria\Entities\Parancelaria;
use App\PaymentCondition\Entities\PaymentCondition;
use App\PointSale\Entities\PointSale;
use App\PointSale\Entities\PointSaleProducts;
use App\Seller\Entities\Seller;
use App\Products\Entities\Productos;
use App\SalesTypes\Entities\SaleType;
use App\Taxes\Entities\Taxes;
use App\TypeVia\Entities\TypeVia;
use App\Ubigeos\Entities\Ubigeo;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Users\Entities\UserByCompany;
use App\ZoneType\Entities\ZoneType;
use App\AffectsIgv\Entities\AffectIgv;
use App\Documents\Entities\Commercial;
use App\PointSale\Entities\PointSaleSeries;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Procedures\ProvisionesPorPagarProcedure;

class PointOfSaleController extends Controller
{
    use CartTrait, Tables, UserPrivileges, Test;
    private $var = 'pointofsale';
    private $tabla_cabecera = 'puntoventa';
    private $instancia = 'details_pointofsale';
    private $instancia_cobranza = 'cobranza_pointofsale';
    private $instancia_products = 'cobranza_products';
    //private $ventana = 'MOV_FACTURAPTOVTA';

    protected $user;
    protected $privilegios = '03.02.08';

    private $lconimptos;
    private $editar_precio;
    private $lstock;

    protected $consumerController;
    protected $customer;
    private $lnegativo;
    private $documento_anticipo;
    private $ltarifasuc;
    private $lcentraliza;
    private $lcpe;
    private $cServcpe;
    private $lunico;
    private $lvalidadocpto;
    private $lauxiliar;
    private $ldigcant;
    private $lcredito;
    private $lcierraimp;

    //private $ventana = 'mov_facturaventa';
    private $origen = 'C';
    private $centraliza = 1;
    private $provisionToPayProcedure;
    private $lcontabiliza;
    private $pdescuento;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(ProvisionesPorPagarProcedure $provisionesPorPagarProcedure)
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
        $this->lcentraliza  = Pempresa::get_parametro('CEN_VENTA')->valor; //
        $this->lcpe  = Pempresa::get_parametro('CPE_ACTIVAR')->valor; //
        $this->cServcpe  = Pempresa::get_parametro('CPE_SERVIDOR')->valor; //
        $this->lunico  = Pempresa::get_parametro('FTCONUNICO')->valor; //
        $this->lvalidadocpto  = Pempresa::get_parametro('FTDOCXPUNTO')->valor; //
        $this->lauxiliar  = Pempresa::get_parametro('VTA_VENAUX')->valor; //
        $this->ldigcant  = Pempresa::get_parametro('FTDIGCANT')->valor; //
        $this->lcredito  = Pempresa::get_parametro('CTRLCREDITO')->valor; //
        $this->lcierraimp  = Pempresa::get_parametro('BLOQPRINT')->valor; //

        $this->lconimptos = Pempresa::get_parametro('CON_IMPTOS')->valor; //El precio de venta incluye impuestos
        $this->editar_precio = Pempresa::get_parametro('EDT_PVENTA')->valor; //El usuario puede cambiar precio de venta
        $this->lstock = Pempresa::get_parametro('FTCONSTOCK')->valor; //Validar disponibilidad de stock en la facturación
        $this->lnegativo = Pempresa::get_parametro('FTCONNEGAT')->valor; //Se permite valores negativos en la facturación
        $this->documento_anticipo = Pempresa::get_parametro('DOC_ANTXPAG')->valor; //Documento para los anticipos a proveedores
        $this->ltarifasuc  = Pempresa::get_parametro('VTA_TARIFA')->valor;  //Tipo Tarifario
        //$this->consumerController = new ConsumerController;
        $this->customer = new CustomerController;
        $this->provisionToPayProcedure = $provisionesPorPagarProcedure;
        $this->lcontabiliza = 1;

        $usuario = UserByCompany::select('pdescuento')->where('id', Auth::id())->first();
        $this->pdescuento = $usuario->pdescuento; // ver si el usuario puede aplicar descuento
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['fechita'] = '';
        $fechas = \session('fechaventa');
        if ($fechas !== '') {
            $fecha = new Carbon($fechas);
            $fecha = $fecha->format('Y-m-d');
            $data['fecha'] = $fecha;
        } else {
            $fecha = new Carbon(date('Y-m-d'));
            $fecha = $fecha->format('Y-m-d');
            $data['fecha'] = $fecha;
        }
        $data['fecha_pv'] = $fecha;
        ///
        $data['impuesto'] = Taxes::crsimpuesto();
        $data['impuesto2'] = Taxes::crsimpuesto2();
        $data['condicionpagos'] = PaymentCondition::all()->where('estado', 1);
        $data['documentocom'] = Commercial::selectfrmcmd('MOV_FACTURAPTOVTA');
        //dd($data['documentocom']);
        $data['monedas'] = Currency::where('estado', 1)->where('id', 1)->first();
        $data['unegocio'] = BusinessUnit::all()->where('estado',1);
        $data['tipoventa'] = SaleType::where('estado', 1)->where('id', 1)->first();

        //adicional
        $tipoventa = $this->txtcodigo_valid($data['tipoventa'], $data['impuesto']);
        $puntoventa = $this->selectidcmd_puntoventa(intval(\session('point_id')));
        $data['puntoventa'] = $puntoventa;
        $data['observaciones'] = 'Ventas ' . (isset($puntoventa->descripcion) ? $puntoventa->descripcion : '');
        $data['impuesto_select'] = $tipoventa['impuesto'];
        $data['afectaigv'] = $tipoventa['igv'];

        $data['formapago'] = PaymentMethod::all();
        //
        $data['view'] = link_view('Ventas', 'Transacciones', 'Punto de Venta', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $this->eliminar_instancias();
        Cart::instance($this->instancia_cobranza)->destroy();

        Cart::instance($this->instancia_products)->destroy();
        return view('pointofsale.create', $data);
    }

    public function documentocom(){

    }

    public function selectidcmd_puntoventa($Pid){
        if((gettype($Pid) == 'string')){
            $lcSelectCmd = PointSale::selectidcmd_codigo($Pid);
        }else{
            $lcSelectCmd = PointSale::selectidcmd_id($Pid);
        }
        return $lcSelectCmd;
    }

    public function txtcodigo_valid($tipoventa, $impuestos){
        $impuesto_id = 0;
        $tabla = new AffectIgv;
        $llenar = null;
        if($tipoventa->noafecto == 1){
            foreach($impuestos as $impuesto){
                if($impuesto->codigo == '99'){
                    $impuesto_id = $impuesto->id;
                    $txtid = 9;
                    $llenar = $this->selectidcmd($txtid, $tabla);
                }
            }
        }
        if($tipoventa->esgratuito == 1){
            if($tipoventa->noafecto == 1){
                foreach($impuestos as $impuesto){
                    if($impuesto->codigo == '99'){
                        $impuesto_id = $impuesto->id;
                    }
                }
            }else{
                $impuesto_id = 4;
            }
            if($tipoventa->noafecto == 0){
                $txtid = 4;
            }else{
                $txtid = 17;
            }
            $llenar = $this->selectidcmd($txtid, $tabla);
        }
        if($impuesto_id > 0){
            $impuesto_id = $impuesto_id;
        }
        $impuesto = ($impuesto_id == 0) ? null : $impuesto_id;
        $array = array('impuesto' => $impuesto, 'igv' => $llenar);
        return $array;
    }

    public function selectidcmd($Pid, $tabla){
        if((gettype($Pid) == 'string')){
            $lcSelectCmd = $tabla::where('codigo', $Pid)->first();
        }else{
            $lcSelectCmd = $tabla::find($Pid);
        }
        return $lcSelectCmd;
    }

    public function list_detalle_venta()
    {
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }

    public function list_cobranza_venta()
    {
        return DataTables::of(collect(Cart::instance($this->instancia_cobranza)->content()))->make();
    }

    public function list_products_venta()
    {
        Cart::instance($this->instancia_products)->destroy();
        return DataTables::of([])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PointOfSaleRequest $request)
    {

        try{
            if (Cart::instance($this->instancia)->count() == 0) {
                return response()->json(['errors' => 'Ingrese un producto']);
            }else{
                $puntoventa = PointSale::select('descripcion')->where('id', $request->txt_descripcionpv)->first()->descripcion;
                $conkardex = SaleType::find($request->txt_descripciontv)->conkardex;
                $docxpagar = new DocumentToPay();
                $docxpagar->periodo_id = Session::get('period_id');
                $docxpagar->numero = $request->numberseries;
                $docxpagar->fechaproceso = $request->txt_fecha;
                $docxpagar->unegocio_id = $request->cbo_unegocio;
                $docxpagar->horaproceso = date("H:i:s");
                $docxpagar->origen = $this->origen;
                $docxpagar->tercero_id = $request->cliente;
                $docxpagar->puntoventa_id = $request->txt_descripcionpv;
                $docxpagar->sucursal_id = $request->sucursal_id;
                $docxpagar->almacen_id = $request->almacen_id;
                $docxpagar->tipotransaccion_id = $request->tipotransaccion_id;
                $docxpagar->tipocompra_id = null;
                $docxpagar->tipoventa_id = $request->txt_descripciontv;
                $docxpagar->moneda_id = $request->txt_descripcionmon;
                $docxpagar->tcmoneda = 0;
                $docxpagar->documento_id = $request->cbotipodoc;
                $docxpagar->seriedoc = $request->txtserie;
                $docxpagar->numerodoc = $request->txtnumero;
                $docxpagar->fechadoc = $request->txt_fecha;
                $docxpagar->tcambio = $request->tcambio;
                $docxpagar->condicionpago_id = $request->txt_descripcioncp;
                $docxpagar->recepcion = null;
                $docxpagar->vencimiento = $request->txt_vence;
                $docxpagar->vendedor_id = $request->vendedor_id;
                $docxpagar->glosa = ($request->edtobservacion == '') ? $request->txtserie.'-'.$request->txtnumero : $request->edtobservacion;
                $docxpagar->tiponotacredito_id = null;
                $docxpagar->tiponotadebito_id = null;
                $docxpagar->tipoafectaigv_id = $request->cnttipoafectaigv;
                $docxpagar->impuesto_id = $request->cbo_impuesto;
                $docxpagar->impuesto2_id = ($request->cbo_impuesto2 == '') ? null :  $request->cbo_impuesto2;
                $docxpagar->impuesto3_id = null;
                $docxpagar->impuesto4_id = null;
                $docxpagar->impuesto5_id = null;
                $docxpagar->tipobienservicio_id = null;
                //$docxpagar->tipoadq = '';
                $docxpagar->base = $request->txt_base;
                $docxpagar->inafecto = $request->txt_inafecto;
                $docxpagar->gratuito = $request->txt_gratuito;
                $docxpagar->descuento = $request->txt_descuento;
                $docxpagar->impuesto = $request->txt_impuesto;
                $docxpagar->impuesto2 = $request->txt_impuesto2;
                $docxpagar->impuesto3 = 0.00;
                $docxpagar->impuesto4 = 0.00;
                $docxpagar->impuesto5 = 0.00;
                $docxpagar->isc = $request->txt_isc;
                $docxpagar->icbper = 0;
                $docxpagar->total = $request->txt_total;
                $docxpagar->ventana = 'mov_facturaventa';
                $docxpagar->estado = 'ACTIVO';
                $docxpagar->esapertura = 0;
                $docxpagar->conpercepcion = 0;
                $docxpagar->refgremision = ($request->remision1 == '') ? '' : $request->remision1."-".$request->remision2;
                $docxpagar->occliente = '';
                $docxpagar->noalmacen = $conkardex;
                $docxpagar->periodoemi = '';
                $docxpagar->razonsocial = $request->razonsocial;
                $docxpagar->ruc = $request->documento;
                $docxpagar->direccion = ($request->direccion_id == null) ? '' : $request->direccion_id;
                $docxpagar->ubigeo_id = ($request->ubigeo_id == '') ? null : $request->ubigeo_id;
                $docxpagar->centraliza = 1;
                $docxpagar->referencial = 0.00;
                $docxpagar->ingresoalmacen_id = 0;
                $docxpagar->liquidacion_id = 0;
                $docxpagar->detraccion_id = 0;
                $docxpagar->ordencompra_id = null;
                $docxpagar->cotizacion_id = null;
                $docxpagar->ordentrabajo_id = null;
                $docxpagar->pedidoalmacen_id = null;
                $docxpagar->guiatransporte_id = null;
                $docxpagar->docplanilla_id = 0;
                $docxpagar->usuario = $this->user;
                $docxpagar->observaciones = $request->txtserie.'-'.$request->txtnumero.' '.$puntoventa;

                $id = null;
                $formapago = Cart::instance($this->instancia_cobranza)->count();
                $id = DB::transaction(function () use ($request,$docxpagar) {
                    $docxpagar->save();
                    $this->save_details($docxpagar->id, $request);
                    return $docxpagar->id;
                });

                //cdocporpagar_formapago.
                if($formapago != 0){
                    $this->save_docxpagar_formapago($id);
                }

                //llenarde. ->formapago

                //$this->llenarde($docxpagar);

                //gabardet:
                $this->provisionToPayProcedure->setUpdateIngalm($id, 'AA');
                //dd($a);


                if($docxpagar->noalmacen == 0){
                    //eliminar ingresos almacen
                    //actualizar ingresos almacen
                }

                //grabar
                $this->setestadoref($docxpagar);

                //contabilizar
                if($this->lcontabiliza == 1){
                    $this->contabilizar($id);
                }

                //centralizar
                if($this->lcentraliza == 1){
                    $this->centralizar($id);
                }

                $r["ruta"] = route('pointofsale');
                $r["estado"] = "ok";
                $r["mensaje"] = "Grabado Correctamente";
            }
        }catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function save_details($id, $request){
        try{
            foreach (Cart::instance($this->instancia)->content() as $cart) {
                $detalle = new DocumentToPayDetail;
                $detalle->parent_id = $id;
                $detalle->item = $cart->options->items;
                $detalle->cuenta_id = $cart->options->cuenta_id;
                $detalle->glosa = $cart->options->descripcion;
                $detalle->centrocosto_id = $cart->options->centro_costo_id;
                $detalle->actividad_id = $cart->options->actividad_id;
                $detalle->proyecto_id = $cart->options->proyecto_id;
                //$detalle->ordentrabajo_id = null;//
                $detalle->peso = $cart->options->peso;
                $detalle->tipoprecio = $cart->options->tipoprecio;
                $detalle->referencia_id = $cart->options->referencia_id;
                $detalle->esnuevo = $cart->options->esnuevo;
                //$detalle->activo_id = null;//
                $detalle->producto_id = $cart->options->id;
                $detalle->serie = $cart->options->serie;
                $detalle->lote = $cart->options->lote;
                //$detalle->dato = "";//
                //$detalle->vencimiento = null;//
                $detalle->umedida_id = $cart->options->umedida_id;
                $detalle->cantidad = $cart->options->cantidad;
                $detalle->preciolista = $cart->options->preciolista;
                $detalle->precio = $cart->options->precio;
                $detalle->isc = $cart->options->isc;
                $detalle->icbper = $cart->options->icbper;
                $detalle->importe = $cart->options->importe;
                $detalle->descuento = $cart->options->descuento;
                //$detalle->cobertura = 0;
                $detalle->puntoventa_id = $request->txt_descripcionpv;
                //$detalle->guiatransaporte_id = 0;
                //$detalle->refpercepcion_id = 0;
                $detalle->tipoafectaigv_id = ($cart->options->tipoafectaigv_id == null ) ? 0 : $cart->options->tipoafectaigv_id;//($request->cnttipoafectaigv == null) ? 0 : $request->cnttipoafectaigv;//
                $detalle->usuario = $this->user;
                $detalle->save();
            }
        }catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
    }

    public function save_docxpagar_formapago($id){
        //dd(Cart::instance($this->instancia_cobranza)->content());
        foreach (Cart::instance($this->instancia_cobranza)->content() as $cart) {
            $docxpagar_formapago = new DocumentToPayPaymentWay();
            $docxpagar_formapago->parent_id = $id;
            $docxpagar_formapago->item = $cart->options->item;
            $docxpagar_formapago->formapago_id = $cart->options->id;
            $docxpagar_formapago->ctactebanco_id = $cart->options->ctactebanco_id;
            $docxpagar_formapago->importe = $cart->options->importe;
            $docxpagar_formapago->nrocheque = '';
            $docxpagar_formapago->mediopago_id = null;
            $docxpagar_formapago->transaccion = $cart->options->ref;
            //$docxpagar_formapago->docbanco_id = 0; //falta especificar
            $docxpagar_formapago->usuario = $this->user;
            $docxpagar_formapago->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function valida_serie(Request $request) // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    {

        extract($this->buscar_producto_inicializar($request, new ProductoSerie())); // inicializar variables

        $busquedapdto = new SearchPdtoController($tabla, $valor, $where, $almacen_id, $fecha_proceso, $nventa);

        $series = (!empty($valor)) ?  $busquedapdto->codigo_valid($valor) : null; // buscar por código serie, objeto SearchPdtoController
        //dd($series);
        $serie = collect($series)->firstWhere('codigo', $valor); // buscar concicidencia con lo enviado

        return isset($serie->codigo) ? null /* no necesito volver nada este dato ya estaría en el front  */ : response()->json( ['errors' => array('detalle' => 'La serie ' . $valor . ' no coincide') ], 422) ;
    }

    public function valida_descuento($descuento)
    {
        $usuario_descuento = UserByCompany::select('pdescuento')->where('id', Auth::id())->first();
        if ($usuario_descuento->pdescuento < $descuento) {
            $r['success'] = "Descuento máximo asignado es de " . $usuario_descuento->pdescuento . " %";
            $r['noapto'] = $usuario_descuento->usuario;
            $r['descuento'] = 0;
        } else {
            if ($descuento < 0) {
                $r['success'] = "No puede ingresar valores negativos";
                $r['descuento'] = 0;
            } else {
                $r['success'] = "";
                $r['aprobado'] = $usuario_descuento->usuario;
                $r['descuento'] = $descuento;
            }
        }
        return $r;
    }

    public function valida_cantidad_stock($cantidad, $stock)
    {

        $r = array();
        if ($this->lstock == 1 && $cantidad > $stock) {
            $r['success'] = 'No hay stock suficiente';
            $r['cantidad'] = $stock;
        }
        if ($this->lnegativo != 1 && $cantidad < 0) {
            $r['success'] = 'No se permiten valores negativos';
            $r['cantidad'] = 0;
        }
        return $r;
    }

    public function agregar_item(Request $request)
    {
        $data = json_decode($request->data);
        $count = count($data);
        if ($count > 0) {
            foreach (Cart::instance($this->instancia_products)->content() as $cart) {
                foreach ($data as $key => $value) {
                    if ($cart->id == $value->ids->value) {
                        $this->llenar_carrito($cart, $value);
                        //$this->valid_txtproducto($value);
                    }
                }
            }
        }
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }

    public function btncobrar(Request $request)
    {
        $lndias = $lntotal = $lnret = $lnsoles = $lndolares = 0;

        $total = 0;
        foreach (Cart::instance($this->instancia)->content() as $cart) {
            if($cart->options->precio == 0 || $cart->options->importe == 0){
                $lnret = 1;
            }
            if($cart->options->cantidad == 0){
                $lnret = 1;
            }
            if($cart->options->umedida_id == 0){
                $lnret = 1;
            }
            $total += $cart->options->importe;
        }

        if($lnret == 1){
            $r['success'] = 'Hay items sin precio, um o cantidad igual a 0. Desea continuar con la operación de cobranza?';
            $r['txtneto'] = $total;
            $this->forma_pago_genera($lntotal, $lnsoles, $lndolares);
            return $r;
        }

        $lndias = 0;

        $lntotal = $total;

        if($request->tmoneda == 1){
            $lnsoles = $lntotal;
            $lndolares = $lntotal / $request->tmoneda;
        }else{
            $lndolares = $lntotal;
            $lnsoles = $lntotal * $request->tmoneda;
        }

        $tabla = new PaymentCondition;
        $condicionpago = $this->selectidcmd(intval($request->condicionpago_id), $tabla);

        $lndias = $condicionpago->dias;
        if($lndias == 0){
            $formapago_id = 0;
            $ctactebanco_id = 0;
            $fpg_cod = '';
            $fpg_dsc = '';
            $moneda_id = 0;

            //$formapago = $this->selectcmd();
            //nctactebanco

            //crsdocumentocom
        }
        $this->forma_pago_genera($lntotal, $lnsoles, $lndolares);
        $r['txtneto'] = $lntotal;
        return $r;
    }

    public function forma_pago_genera($lntotal, $lnsoles, $lndolares){
        Cart::instance($this->instancia_cobranza)->destroy();
        $forma_pago = PaymentMethod::first();
        $items = count(Cart::instance($this->instancia_cobranza)->content());
        Cart::instance($this->instancia_cobranza)->add(['id' => $items+1, 'name' => $forma_pago->id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'item' => $items+1,
                'id' => $forma_pago->id,
                'fp' => $forma_pago->codigo,
                'descripcion' => $forma_pago->descripcion,
                'ref' => '',
                'importe' => $lntotal,
                'soles' => $lnsoles,
                'dolares' => $lndolares,
                'ctactebanco_id' => $forma_pago->ctactebanco_id,
            ]]);
        DataTables::of(collect(Cart::instance($this->instancia_cobranza)->content()))->make();
    }

    public function eliminar_datos(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);

        $id = $request->item;
        session()->push('items', $id);
        //$this->llenar_cobranza();
    }

    public function eliminar_instancias()
    {
        Cart::instance($this->instancia)->destroy();
    }

    public function buscar_producto(Request $request)
    {
        //destruir la instancia del modal
        Cart::instance($this->instancia_products)->destroy();
        ///
        extract($this->buscar_producto_inicializar($request, new Productos()));

        $busquedapdto = new SearchPdtoController($tabla, $valor, $where, $almacen_id, $fecha_proceso, $nventa);
        if ( !empty($codigo = trim($request->producto_codigo)) )                { $productos = $busquedapdto->codigo_valid($codigo); }
        elseif ( !empty($descripcion = trim($request->producto_descripcion)) )  { $productos = $busquedapdto->descripcion_valid($descripcion, trim($request->producto_presentacion)); }
        elseif ( !empty($presentacion = trim($request->producto_presentacion)) ){ $productos = $busquedapdto->presentacion_valid($presentacion, $descripcion); }

        return $this->listado_productos($productos, $precio, $sucursal, $esgratuito, $noafecto);
    }

    private function buscar_producto_inicializar($request, $instancia)
    {
        $punto_venta = PointSale::findOrFail($request->punto_venta);
        $tipo_venta = SaleType::findOrFail($request->tipo_venta); // ver clasic, objeto: txtidproducto, método: Valid

        switch ($instancia->getTable()) { // evaluar origen
            case 'producto': // buscando producto
                $valor = $request->producto_id;
                $where = 'p.estado=1';
                break;
            case 'productoserie': // validar serie, clasic objeto: txtserie, método: keyPress
                $valor = $request->serie;
                $where = 'p.estado=1 and p.id = ' . $request->producto_id;
                break;
            default:
                $valor = null;
                $where = '';
                break;
        }
        $data['tabla'] = $instancia;
        $data['valor'] = $valor;
        $data['where'] = $where;
        $data['almacen_id'] = $punto_venta->almacen_id;
        $data['fecha_proceso'] = $request->fecha_proceso;
        $data['nventa'] = 1; //formulario: mov_facturaventa, objeto : pfdetalle - Txtidproducto1, propiedad : nventa. En productoserie no se usa
        $data['precio'] = $tipo_venta->precio; // número de precio
        $data['sucursal'] = $punto_venta->sucursal_id;
        $data['esgratuito'] = $tipo_venta->esgratuito;
        $data['noafecto'] = $tipo_venta->noafecto;

        return $data;

    }

    private function listado_productos($productos, $precio, $sucursal, $esgratuito, $noafecto){ // formando listado para el modal de productos
        $precio_0 = $esgratuito + $noafecto; //  si ambos valores son verdaderos (son 1) // clasic, objeto: txtidproducto1, método: Valid

        // EN CASO TENGA RAZÓN QUE EL PRECEDMIENTO HACE LA MISMA VALIDACIÓN Y  NO ES NECESARIO OBTENER PRECIO POR TXTIDPRODUCTO1 MÉTODO VALID
        $precio = $this->obtener_precio_procedimiento_almacenado($precio);
        $pdescuento = ($this->pdescuento > 0)  ? 1 : 0; // ver si el usuario puede aplicar descuento

        foreach ($productos as $producto) {

            $productodb = Productos::select('precio1', 'precio2', 'precio3', 'precio4', 'precio5', 'precio6', 'pideserie', 'tipoproducto')->where('id', $producto->id)->first();
            //$precio = $this->obtener_precio($precio, $sucursal, $producto->id, $productodb, $producto->umedida_id); // clasic, txtidproducto1, método Valid

            Cart::instance($this->instancia_products)->add(['id' => $producto->id, 'name' => $producto->descripcion, 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'cumseps' => $producto->cumseps,
                    'descripcion' => $producto->descripcion,
                    'caracteristicas' => $producto->caracteristicas,
                    'umedida' => $producto->umedida,
                    'ubicacion' => $producto->ubicacion,
                    'stock' => $producto->stock,
                    'precio' => ($precio_0 == 2) ? 0 : $producto->$precio, //en caso el precio sea del procedimiento almacenado // en caso sea del valid txtidproducto1 : $precio
                    'esafecto' => $producto->esafecto,
                    'flag' => $producto->flag,
                    'estado' => $producto->estado,
                    'umedida_id' => $producto->umedida_id,
                    'marca' => $producto->marca,
                    'editar_precio' => $this->editar_precio,
                    'pideserie' => $productodb->pideserie,
                    'serie' => '',
                    'tipoprecio' => 1, // este  valor se asigna en el evento del formulario nuevalinea
                    'lote' => '', // se ingresa en el modal o en el detalle
                    'importe' => 0,
                    'preciolista' => ($precio_0 == 2) ? 0 : $producto->$precio, //en caso el precio sea del procedimiento almacenado // en caso sea del valid txtidproducto1 : $precio // en principio (txtidproducto1, método: Valid) es igual a precio (cuando el precio de la bd sea cero), pero el precio puede cambiar y este se queda con el valor (ver buscaritem)
                    'cantidad' => 0,
                    'isc' => '',
                    'icbper' => '',
                    'pdescuento' => $pdescuento,
                ]
            ]);

        }
        /*dd(Cart::instance($this->instancia_products)->content());*/
        return DataTables::of(collect(Cart::instance($this->instancia_products)->content()))->make();
    }

    public function sumar($request)
    {
        $lnImptoIgv = 0;
        $importe = 0;
        $Ticbper = 0;
        $tipoprecio = 0;
        $Tisc = 0;

        $tipoventa = SaleType::find($request->tipo_venta);

        $impuesto = Taxes::find( $request->igv);

        $afeigv_cod = AffectIgv::select('codigo')->where('id', $request->tipo_afectacion_igv)->first();
        /*if($request->afeigv_cod != ''){
            $afeigv_cod = AffectIgv::find($request->tipo_afectacion_igv)->codigo;
        }else{
            $afeigv_cod = $request->tipo_afectacion_igv;
        }*/
        $lnImptoIgv = ($impuesto->tipocalculo == 'P') ? $impuesto->valor / 100 : 0;

        $Tbase = $Tinafecto = $Tisc = $Ticbper = $Tdescuento = $Tgratuito = $Ttotal = 0;

        foreach (Cart::instance($this->instancia)->content() as $cart) {
            $base = $inafecto = $isc = $icbper = $gratuito = $descuento = $anticipo = $bonifica = $total = 0;

            $tipoprecio = ($tipoprecio == 0) ? 1 : $cart->options->tipoprecio;

            $linafecto = true;

            $lcTipoAfeIgv = trim($afeigv_cod);

            if ((is_null($cart->options->referencia_id) ? 0 : $cart->options->referencia_id) > 0 && $cart->options->esnuevo == 0) {
                if ($cart->options->importe != 0) {
                    $anticipo = $cart->options->importe;
                    if ($this->left($cart->options->docrefer, 3) == '07 ') {
                        //Nota de credito
                        $total = $cart->options->importe;
                    } else {
                        //anticipos
                        if (in_array($lcTipoAfeIgv, array(20, 21, 30, 31, 32, 33, 34, 35, 36))) {
                            $total = $cart->options->importe;
                            $linafecto = true;
                        } else {
                            $total = round(round($cart->options->importe * (1 + $lnImptoIgv), 3), 2);
                        }
                    }
                    $totalref = abs($total);
                    if (ceil($totalref) - $totalref == 0.01 || $totalref - intval($totalref) == 0.01) {
                        if ($totalref - intval($totalref) == 0.01) {
                            $totalref = $totalref - 0.01;
                        } else {
                            $totalref = $totalref + 0.01;
                        }
                        if ($total < 0) {
                            $total = $totalref * (-1);
                        } else {
                            $total = $totalref;
                        }
                    }
                }
            } else {
                $isc = ($cart->options->isc == '') ? 0 : $cart->options->isc;
                $icbper = ($cart->options->icbper == '') ? 0 : $cart->options->icbper;

                if ($cart->options->esafecto == 0 || $tipoventa->noafecto == 1 || in_array($lcTipoAfeIgv, array(20, 21, 30, 31, 32, 33, 34, 35, 36))) {

                    $linafecto = true;

                    if ($cart->options->importe > 0) {

                        $inafecto = $cart->options->importe;

                        if ($cart->options->preciolista > $cart->options->precio) {

                            if ($cart->options->tipoprecio == 1 || $cart->options->tipoprecio == 4) {

                                $descuento = (($cart->options->preciolista - $cart->options->precio) * $cart->options->cantidad);

                            } else {

                                $descuento = (($cart->options->preciolista - $cart->options->precio) * $cart->options->peso);

                            }
                        }
                    } else {
                        $bonifica = abs($cart->options->importe);
                    }
                    if ($tipoventa->esgratuito == 1) {
                        if ($cart->options->precio = 0) {
                            if ($tipoprecio == 1 || $tipoprecio == 4) {
                                $gratuito = $cart->options->preciolista * $cart->options->cantidad;
                            } else {
                                $gratuito = $cart->options->preciolista * $cart->options->peso;
                            }
                        } else {
                            if ($tipoprecio == 1 || $tipoprecio == 4) {
                                $gratuito = $cart->options->precio * $cart->options->cantidad;
                            } else {
                                $gratuito = $cart->options->precio * $cart->options->peso;
                            }
                        }
                    }
                    $total = $cart->options->importe;
                } else {
                    if ($cart->options->importe >= 0) {

                        $base = $cart->options->importe;

                        if ($cart->options->preciolista > $cart->options->precio) {

                            if ($tipoprecio == 1 || $tipoprecio == 4) {

                                $descuento = (($cart->options->preciolista - $cart->options->precio) * $cart->options->cantidad);
                            } else {
                                $descuento = (($cart->options->preciolista - $cart->options->precio) * $cart->options->peso);
                            }
                        }
                    } else {
                        $bonifica = abs($cart->options->importe);
                    }

                    if ($tipoventa->esgratuito == 1) {

                        if ($cart->options->precio == 0) {

                            if ($tipoprecio == 1 || $tipoprecio == 4) {

                                $gratuito = $cart->options->preciolista * $cart->options->cantidad;

                            } else {

                                $gratuito = $cart->options->preciolista * $cart->options->peso;

                            }

                        } else {

                            if ($tipoprecio == 1 || $tipoprecio == 4) {

                                $gratuito = $cart->options->preciolista * $cart->options->cantidad;

                            } else {

                                $gratuito = $cart->options->preciolista * $cart->options->peso;

                            }
                        }
                    }
                    $total = $cart->options->importe;
                    if ($this->lconimptos == 1) {
                        //dd('hola');
                        $base = $base / (1 + $lnImptoIgv);
                        $bonifica = $bonifica / (1 + $lnImptoIgv);
                        $descuento = $descuento / (1 + $lnImptoIgv);
                    } else {
                        $total = $total * (1 + $lnImptoIgv);
                        $gratuito = $gratuito * (1 + $lnImptoIgv);
                    }
                }
            }
            if ($linafecto) {
                //dd($base);
                $Tbase = $Tbase + $base + $anticipo + $descuento;
            } else {
                $Tinafecto = $Tinafecto + $inafecto + $anticipo + $descuento;
            }
            $Tisc = $Tisc + $isc;
            $Ticbper = $Ticbper + $icbper;
            $Tdescuento = $Tdescuento + $descuento + $bonifica;
            $Tgratuito = $Tgratuito + $gratuito;
            $Ttotal = $Ttotal + $total + $isc + $icbper;
        }
        $Tdescuento = intval($Tdescuento) * intval(-1);

        if ($tipoventa->noafecto == 1) {
            $Tinafecto = ($Tbase != 0) ? $Tbase : $Tinafecto;
            $Tbase = 0;
            $igv = 0;
            $Tisc = 0;
        } else {
            if (entre_intervalo($Tbase, -0.02, 0.02)) {
                $Tbase = 0;
                $igv = 0;
                $Tisc = 0;
                $Ticbper = 0;
            } else {
                $igv = ($Tbase + $Tdescuento) * $lnImptoIgv;
                //dd($Tbase);
            }
            if (entre_intervalo($Tinafecto, -0.02, 0.02)) {
                $Tinafecto = 0;
            }
        }
        $txtbase = round($Tbase, 2);
        $txtinafecto = round($Tinafecto, 2);
        $txtdescuento = round($Tdescuento, 2);
        $txtisc = round($Tisc, 2) + round($Ticbper, 2);

        //dd($txtisc);
        $txtimpuesto = round($igv, 2);
        $txtgratuito = round($Tgratuito, 2);
        $txttotal = round($Ttotal, 2);

        if ($txttotal != $txtbase + $txtinafecto + $txtdescuento + $txtisc + $txtimpuesto) {
            if ($txtbase != 0) {
                $txtbase = $txtbase + ($txttotal - ($txtbase + $txtinafecto + $txtdescuento + $txtisc + $txtimpuesto));
            }
        }

        // actualizar el isc por $tisc y icbper por $ticbper - clasic
        session()->put('tisc', $Tisc);
        session()->put('ticbper', $Ticbper);

        /*$result = array('txtbase'=> $txtbase,
                        'txtinafecto' => $txtinafecto,
                        'txtdescuento' => $txtdescuento,
                        'txtisc' => $txtisc,
                        'txtimpuesto' => $txtimpuesto,
                        'txtgratuito' => $txtgratuito,
                        'txttotal' => $txttotal);*/

        //$importe = ( $request->importe != 0 && $request->importe != $txttotal) ? $txttotal : $importe;
        $data['tbase'] = $txtbase;
        $data['tinafecto'] = $txtinafecto;
        $data['tdescuento'] = $txtdescuento;
        $data['tisc_icbper'] = $txtisc;
        $data['igv'] = $igv;
        $data['tgratuito'] = $txtgratuito;
        $data['ttotal'] = $txttotal;
        //$data['importe'] = $importe; // txtimporte

        return $data;
    }

    public function totalizar(Request $request)
    {
        $txttotal = $request->txt_base + $request->txt_inafecto + $request->txt_impuesto + $request->txt_impuesto2 + $request->txt_descuento;

        $lnSoles = $lnDolares = 0;
        $soles = 0;
        $txtnum3 = 0;
        $txtnum4 = 0;
        $dolares = 0;
        $lnMon = $request->tmoneda;
        $lnTc = $request->tcambio;
        foreach (Cart::instance($this->instancia_cobranza)->content() as $cart) {
            $lnItem = $cart->options->item;
            $lnneto = $request->txtneto;
            if ($lnMon == 1) {
                $lnsaldoS = $lnneto - $cart->options->soles;
                $lnsaldoD = ($lnneto / $lnTc) - $cart->options->dolares;
            } else {
                $lnsaldoS = ($lnneto * $lnTc) - $cart->options->soles;
                $lnsaldoD = $lnneto - $cart->options->dolares;
            }
            $lnI = 0;
            $lnMax = Cart::instance($this->instancia_cobranza)->count();
            $lnI = $lnI + 1;
            if ($cart->options->item != $lnItem) {
                if ($lnMon == 1) {
                    if ($lnsaldoS == 0) {
                        $soles = 0;
                        $dolares = 0;
                    }
                } else {
                    if ($lnsaldoD == 0) {
                        $dolares = 0;
                        $soles = 0;
                    }
                }
                $lnsaldoS = $lnsaldoS - $cart->options->soles;
                $lnsaldoD = $lnsaldoD - $cart->options->dolares;
                if ($lnMon == 1) {
                    if ($lnsaldoS < 0 || $lnI == $lnMax) {
                        $soles = $soles + $lnsaldoS;
                        $dolares = $dolares + $lnsaldoD;
                        $importe = $soles;
                        $lnsaldoS = 0;
                        $lnsaldoD = 0;
                    }
                } else {
                    if ($lnsaldoD < 0 || $lnI == $lnMax) {
                        $soles = $soles + $lnsaldoS;
                        $dolares = $dolares + $lnsaldoD;
                        $lnsaldoS = 0;
                        $lnsaldoD = 0;
                    }
                }
            }
            $lnSoles += $soles;
            $lnDolares += $dolares;
        }

        foreach (Cart::instance($this->instancia_cobranza)->content() as $cart) {
            if ($lnMon == 1) {
                $txtnum3 = $request->txtneto - $lnSoles;
                $txtnum4 = ($request->txtneto / $lnTc) - $lnDolares;
            } else {
                $txtnum3 = ($request->txtneto * $lnTc) - $lnSoles;
                $txtnum4 = $request->txtneto - $lnDolares;
            }
        }
        $data['txtnum3'] = $txtnum3;
        $data['txtnum4'] = $txtnum4;

        return $data;
    }

    function left($str, $length)
    {
        return substr($str, 0, $length);
    }

    function right($str, $length)
    {
        return substr($str, -$length);
    }

    public function valida_codigo(Request $request)
    {
        $r['tipo_persona'] = 0;
        $r['documentoide_id'] = 0;
        $r['ubigeo'] = 0;
        $r['persona'] = 0;
        $r['estado'] = 'no';
        $data_tercero = 0;

        //validar la vista crear id = 0 | edit id > 0
        $resultado = $this->verificar_codigo($request->codigo);
        if (isset($resultado->codigo)) {
            $r['customer'] = $resultado;
            //dd($r['customer']);
            $direccion = CustomerAddress::where('tercero_id', $resultado->id)->get();
            $r['direcciones'] = $direccion;
        } else {
            $this->customer->eliminar_locales_anexos(); //otro codigo consultado
            $data_tercero = $this->customer->data_tercero($request->codigo, $request->id);

            $data_tercero = $this->save_terceros($request->codigo, $data_tercero);
            $customer = Customer::where('id', $data_tercero['resultado'])->first();
            $r['customer'] = $customer;
            $direccion = CustomerAddress::where('tercero_id', $customer->id)->get();
            $r['direcciones'] = $direccion;
        }
        return $r;
    }

    public function verificar_codigo($codigo)
    {
        $resultado = Customer::whereRaw('codigo = ' . $codigo . ' or dni = ' . $codigo)->first();
        return $resultado;
    }

    public function save_terceros($codigo, $data)
    {
        try {
            $ubigeo = Ubigeo::select('id', 'descripcion')->where('codigo', session('ubigeo'))->first();
            $tercero = new Customer();
            if (strlen($codigo) == 8) {
                $data_sunat = json_decode($data['persona']);
                //DNI
                $tercero->codigo = $data_sunat->dni;
                $tercero->descripcion = $data_sunat->apellidoPaterno . " " . $data_sunat->apellidoMaterno . " " . $data_sunat->nombres;
                $tercero->apellidopaterno = $data_sunat->apellidoPaterno;
                $tercero->apellidomaterno = $data_sunat->apellidoMaterno;
                $tercero->nombres = $data_sunat->nombres;
                $tercero->nrodocidentidad = $data_sunat->dni;
                $tercero->dni_id = 1;
                $tercero->dni = $data_sunat->dni;
                $tercero->ruc = '';
                $tercero->ubigeo_id = $ubigeo->id;
                $tercero->tipovia_id = null;
                $tercero->via_nombre = $ubigeo->descripcion;
                $tercero->via_numero = '';
                $tercero->via_interior = '';
                $tercero->tipozona_id = null;
                $tercero->zona_nombre = '';
            } else {
                $data_sunat = json_decode($data['persona'])[0];
                //RUC
                $tercero->codigo = $data_sunat->emp_ruc;
                $tercero->descripcion = $data_sunat->emp_descripcion;
                $tercero->apellidopaterno = '';
                $tercero->apellidomaterno = '';
                $tercero->nombres = '';
                $tercero->ruc = $data_sunat->emp_ruc;
                $tercero->nrodocidentidad = $data_sunat->emp_ruc;
                $tercero->dni_id = null;
                $tercero->dni = '';
                $ubigeo = Ubigeo::select('id')->where('codigo', $data_sunat->emp_ubigeo)->first();
                $tercero->ubigeo_id = $ubigeo->id;

                $tipo_via = $this->tipo_via($data_sunat->emp_tipo_via);
                $tercero->tipovia_id = $tipo_via;
                $tercero->via_nombre = $data_sunat->emp_nombre_via;
                $tercero->via_numero = $data_sunat->emp_numero;
                $tercero->via_interior = $data_sunat->emp_interior;
                //$tercero->foto = $request->
                $tipo_zona = $this->tipo_zona($data_sunat->emp_codigo_zona);
                $tercero->tipozona_id = $tipo_zona;
                $tercero->zona_nombre = $data_sunat->emp_tipo_zona;

            }

            $tercero->nombrecomercial = '';
            $tercero->fechanac = null;
            $tercero->tratamiento = '';
            $tercero->tipopersona = $data['tipopersona'];
            //$tercero->ruc = $request->
            $tercero->clasetercero_id = null;
            $tercero->telefono1 = '';
            $tercero->telefono2 = '';
            $tercero->telefono3 = '';
            $tercero->web = '';
            $tercero->escliente = 1;
            $tercero->precio = 1;
            $tercero->lineacredito = 0.000;
            $tercero->condicioncobr_id = 1;
            $tercero->esproveedor = 0;
            $tercero->condicionpago_id = 1;
            $tercero->conretencion = 0;
            $tercero->condetraccion = 0;
            $tercero->estrabajador = 0;
            $tercero->esservidor = 0;

            $tercero->documentoide_id = $data['documentoide_id'];

            $tercero->pais_id = null;
            $tercero->observaciones = '';
            $tercero->estado = 1;

            $tercero->email = '';
            //$tercero->referencia = $request->

            $tercero->sucursal_id = null;
            $tercero->nropermiso = '';
            $tercero->vendedor_id = null;
            $tercero->conyuge = '';
            $tercero->usuario = Session::get('usuario');

            $fecha = new Carbon(date('Y-m-d'));

            $tercero->fechaafilia = $fecha->format('Y-m-d');

            $id = null;
            $id = DB::transaction(function () use ($tercero, $codigo) {
                $tercero->save();
                /*if (strlen($codigo) == 8) {
                    $this->guardar_tercero_direccion($tercero->id, $tercero->via_nombre, $tercero->ubigeo_id);
                }else{*/
                    $this->customer->guardar_tercero_direccion($tercero->id);
                //}
                return $tercero->id;
            });
            $r['resultado'] = $id;

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function guardar_tercero_direccion($tercero_id, $via_nombre, $ubigeo_id){

        $tercero_direccion = new CustomerAddress();
        $tercero_direccion->tercero_id = $tercero_id;
        $tercero_direccion->item = 1;
        //$tercero_direccion->tipovia_id = $cart->options->tipovia_id;
        $tercero_direccion->via_nombre = ($via_nombre != '') ? $via_nombre : '';
        //$tercero_direccion->via_numero = ($cart->options->via_numero != '') ? $cart->options->via_numero : '';
        //$tercero_direccion->via_interior = ($cart->options->via_interior != '') ? $cart->options->via_interior : '';
        $tercero_direccion->ubigeo_id = $ubigeo_id;
        $tercero_direccion->usuario = $this->user;
        $tercero_direccion->save();
    }

    public function tipo_via($tipo_via)
    {
        switch ($tipo_via) {
            case 'AV.':
                return 1;
                break;
            case 'JR.':
                return 2;
                break;
            case 'C.':
            case 'C/':
            case 'CA.':
            case 'CL.':
                return 3;
                break;
            case 'PSJE.':
            case 'PJE.':
                return 4;
                break;
            case 'ALA.':
            case 'AL.':
                return 5;
                break;
            case 'MAL.':
            case 'ML.':
                return 6;
                break;
            case 'OVA.':
            case 'OV.':
                return 7;
                break;
            case 'PAR.':
            case 'PQ.':
                return 8;
                break;
            case 'PLA.':
            case 'PZA.':
            case 'PL.':
            case 'PLZA.':
                return 9;
                break;
            case 'CAR.':
            case 'CTRA.':
            case 'CARRET.':
                return 10;
                break;
            case 'BL.':
                return 11;
                break;
            case 'TRO.':
                return 12;
                break;
            case 'CR.':
                return 14;
                break;
            case 'BAJ.':
                return 15;
                break;
            case 'GAL.':
                return 16;
                break;
            case 'PROL.':
                return 17;
                break;
            case 'PAS.':
                return 18;
                break;
            case 'PLZ.':
                return 19;
                break;
            case 'POR.':
                return 20;
                break;
            case 'CAM.':
                return 21;
                break;
            case 'TRCAR.':
                return 22;
                break;
            default:
                return 23;
                break;
        }
    }

    public function tipo_zona($tipo_zona)
    {
        switch ($tipo_zona) {
            case 'URB.':
                return 1;
                break;
            case 'PJ.':
                return 2;
                break;
            case 'UV.':
                return 3;
                break;
            case 'CH.':
                return 4;
                break;
            case 'AH.':
                return 5;
                break;
            case 'COOP.':
                return 6;
                break;
            case 'RES.':
                return 7;
                break;
            case 'ZI':
                return 8;
                break;
            case 'GRU.':
                return 9;
                break;
            case 'CAS':
                return 10;
                break;
            case 'FND':
                return 11;
                break;
            default:
                return 13;
                break;
        }
    }

    /*public function buscaritem(Request $request)
    {
        $r = array();
        $producto = Productos::find($request->producto_id);
        $tipoventa = SaleType::find($request->tventa);
        $moneda = Currency::find($request->tmoneda);
        $lnTcambio = $request->tcambio;
        $lcTipoMon = $moneda->tipo;

        $cart = collect(Cart::instance($this->instancia)->content())->firstWhere('name', $request->contador)->options;

        $preciolista = (empty($cart->precio)) ? 0 : $request->precio; // input
        $serie = $request->serie; // input

        $precio = 0;

        $lncantidad = $request->cantidad;
        $lnid = 0;

        $lnitem = $cart->item;
        $producto_id = $cart->id;
        $tipoprecio = ($cart->tipoprecio == 0) ? 1 : $cart->tipoprecio;// se actuliza el tipo_precio

        if ($lnitem == 0) {
            $tipoprecio = 1;
        }
        if ($tipoventa->esgratuito == 1 && $request->cboimpuesto == 3) {
            $precio = 0;
        } else {
            $precio = $request->precio * (1 - ($request->dscts / 100));
        }
        ///$tipoventa->conkardex  -> chkalmacen
        if ($this->lstock && $tipoventa->conkardex == 1 && $producto->tipoproducto != 'S') {
            $lnId = $producto_id;
            //$lcLote = trim($lote);
            if ($lncantidad > $cart->stock){
                $r['success'] = 'No hay stock suficiente';
                if ($cart->stock < 0){
                    $r['cantidad'] = 0;
                }else{
                    $r['cantidad'] = $cart->stock;
                }
            }
        }
        if ($tipoprecio == 1 || $tipoprecio == 4) {
            $cantidad = $request->cantidad;
        }else{
            $cantidad = $cart->peso;
        }
        $importe = $cantidad * $precio;

        $importe = $importe;

        $isc = 0;
        $icbper = 0;
        $parancelaria = Parancelaria::selectparentcmd($cart->id);
        //tipo
        if (isset($parancelaria->tipo)){
            if ($parancelaria->tipo == 'N'){
                if (trim($parancelaria->tipoisc) == 'ICBPER'){
                    $icbper = $cantidad * $parancelaria->isc;
                }else{
                    $isc = $cantidad * $parancelaria->isc;
                }
            }else{
                if (trim($parancelaria->tipoisc) == 'ICBPER'){
                    $icbper = $importe * ($parancelaria->isc / 100);
                }else{
                    $isc = $importe * ($parancelaria->isc / 100);
                }
            }
        }

        if ($lcTipoMon != 'N'){
            $icbper =  $icbper / $lnTcambio;
        }

        $isc = $isc;
        $icbper = $icbper;
        $importe = $importe + ($isc * 1.18);

        $tipoprecio = $tipoprecio;
        $precio = $precio; // el valor alterado es precio y el que no cambia preciolista (en detalle y en el modal)
        $cantidad = $request->cantidad;
        $descuento = $request->dscts; // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        $preciolista = $preciolista;   // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        //$data['serie'] = $serie; // adicional no esta en el clasic pesro se necesita, para actualizar el datatable

        //$this->actualizar_carrito($ics, $icbper, $importe, $tipoprecio, $precio, $cantidad, $descuento, $preciolista);

        return $r;
    }*/

    private function buscaritem($request){ // formulario: mov_facturaventa, método: buscaritem

        // se usa cuando se desencadena el evento blur de los inputs del modal o del docxpagar_detalle
        $lnFactor = 0;
        extract($this->inicializar_buscaritem($request));
        if(isset($documentocom)) {$lnFactor = $documentocom->factor;}else{$lnFactor = 1;}
        $tipoventa = SaleType::find($request->tipo_venta);
        //dd($request->serie);
        $crear_kardex = $tipoventa->conkardex;
        $cantidad_productos_repetidos = 0;
        $cantidad = (empty($request->cantidad)) ? 0 : $request->cantidad; // input
        $descuento = (empty($request->descuento)) ? 0 : $request->descuento; // input
        $preciolista = (empty($request->precio)) ? 0 : $request->precio; // input
        $serie = ($request->serie == null) ? '' : $request->serie; // input

        $productodb = Productos::select('tipoproducto', 'peso')->where('id', $producto->options->id)->first();

        // operaciones

        $tipoprecio = ( $producto->options->tipoprecio == 0 ) ? 1 : $producto->options->tipoprecio; // se actuliza el tipo_precio

        $precio = ( $tipo_venta->esgratuito == 1 && $igv->codigo == '99' ) ? 0 : $preciolista * ( 1 - ( $request->descuento / 100 ) ); // se actuliza el precio

        $cantidad = ( $tipoprecio == 1 || $tipoprecio == 4 ) ? $cantidad : $productodb->peso;

        $importe = $cantidad * $precio; // se actualiza el importe

        $parancelaria = Parancelaria::selectparentcmd($producto->options->id);

        $isc = 0;
        $icbper = 0;

        if ($parancelaria) { // en caso exista data

            if ( $parancelaria->tipo == 'N' ) { // calcular $icbper y $isc

                if ( trim($parancelaria->tipoisc) == 'ICBPER' ) {
                    $icbper = $cantidad * $parancelaria->isc;  // se actualiza icbper
                } else {
                    $isc = $cantidad * $parancelaria->isc; // se actualiza isc
                }

            } else {

                if (trim($parancelaria->tipoisc) == 'ICBPER') {
                    $icbper = $importe * ($parancelaria->isc/100); // se actualiza icbper
                } else {
                    $isc = $importe * ($parancelaria->isc/100); // se actualiza isc
                }

            }

        }

        $icbper = ( $moneda->tipo != 'N' ) ?  $icbper / $request->tcambio : $icbper; // se actualiza icbper

        $importe = $importe + ( $isc * 1.18 ); // se actualiza el importe

        $data['tipoprecio'] = $tipoprecio;
        $data['precio'] = $precio; // el valor alterado es precio y el que no cambia preciolista (en detalle y en el modal)
        $data['cantidad'] = $cantidad;
        $data['importe'] = $importe;
        $data['isc'] = $isc;
        $data['icbper'] = $icbper;
        $data['descuento'] = $descuento; // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        $data['preciolista'] = $preciolista;   // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        $data['serie'] = $serie; // adicional no esta en el clasic pesro se necesita, para actualizar el datatable
        return $data;

        // después normalmente llama al sumar en el clasic

    }

    public function txtserie_valid(Request $request){
        //dd($request->all());
        $lnNro = '';
        $valor = '';
        $puntoventa_series = PointSaleSeries::where('puntoventa_id', $request->puntoventa)->where('documentocom_id', $request->cbotipodoc)->first();
        $serie = is_null($puntoventa_series) ? '' : $puntoventa_series->serie;
        $documentocom = (is_null($puntoventa_series)) ? '' : $puntoventa_series->documentocom_id;
        $lcNro = ($serie == null || $serie == '') ? '' : $request->serie;
        $cbotipodoc = Commercial::where('id', $request->cbotipodoc)->first()->codigo;

        if($this->lcpe == 1){
            if(ctype_alpha($this->left($lcNro, 1))){
                $lnserie = intval(substr($request->serie, 2, 3));
                if(strtoupper($this->left($lcNro, 1)) == 'E'){
                    $valor = 'E'.str_pad($lnserie, 3, '0', STR_PAD_LEFT);
                }else{
                    $lnserie =intval(substr($request->serie, 2, 3));
                    switch($cbotipodoc){
                        case '01':
                        case 'FT':
                            $valor = 'F'.str_pad($lnserie, 3, '0', STR_PAD_LEFT);
                            break;
                        case '03':
                        case 'BV':
                            $valor = 'B'.str_pad($lnserie, 3, '0', STR_PAD_LEFT);
                            break;
                        case in_array($cbotipodoc, array('07','08','97','98','ND','NC')):
                            $valor = strtoupper($this->left($lcNro, 1)).str_pad($lnserie, 3, '0', STR_PAD_LEFT);
                            break;
                        default:
                            $valor = str_pad($lnserie, 4, '0', STR_PAD_LEFT);
                            break;
                    }
                }
            }else{
                $r['success'] = 'Serie no válida';
                return $r;
            }
        }else{
            if(strtoupper($this->left($lcNro, 1)) == 'E'){
                $lnserie = str_pad($serie, 3, '0', STR_PAD_LEFT);
                $lnserie = intval(substr($serie, 2, 3));
                $valor = 'E'.str_pad($lnserie, 3, '0', STR_PAD_LEFT);
            }else{
                if(strlen($lcNro) < 4){
                    $valor = str_pad(trim($lnserie), 4, '0', STR_PAD_LEFT);
                }
            }
        }
        $r['serie'] = $valor;

        $numero = $this->t_sgtenumero($cbotipodoc, $valor, $this->origen);
        $r['numero'] = str_pad($numero, 8, '0', STR_PAD_LEFT);
        return $r;
    }

    public function interactiveChange(Request $request){
        $lnserie = 1;
        $puntoventa_series = PointSaleSeries::where('puntoventa_id', $request->puntoventa)->where('documentocom_id', $request->cbotipodoc)->first();
        $codigo_documento = Commercial::where('id', $request->cbotipodoc)->first();
        if(isset($puntoventa_series)){
            if($this->lvalidadocpto == 1){
                $r['success'] = 'No se puede emitir este tipo de documento para este tipo de cliente';
                $documentocom_id = $puntoventa_series->documentocom_id;
                return $r;
            }
            $lnserie = $puntoventa_series->serie;
        }

        $lnserie = $lnserie;

        if(empty($serie)){
            if($this->lcpe == 1){
                switch($codigo_documento->codigo){
                    case '03':
                    case 'BV':
                        $valor = 'B'.str_pad($lnserie, 3, '0', STR_PAD_LEFT);
                        break;
                    case in_array($codigo_documento->codigo, array('01','07','08','97','98','FT','ND','NC')):
                        $valor = 'F'.str_pad($lnserie, 3, '0', STR_PAD_LEFT);
                        break;
                    default:
                        $valor = str_pad($lnserie, 4, '0', STR_PAD_LEFT);
                        break;
                }
            }else{
                $valor = str_pad($lnserie, 4, '0', STR_PAD_LEFT);
            }
        }

        return $valor;
    }

    public function cboimpuesto(Request $request){
        /*$lnid = $request->cbo_impuesto;

        $impuesto = Taxes::find($request->cbo_impuesto);
        //$lnImptoIgv = ($impuesto->tipocalculo == 'P') ? $impuesto->valor / 100 : 0;
        $lnvalor = 0;
        if ($impuesto->tipocalculo == 'P') {
            $lnvalor = $impuesto->valor / 100;
            $lnvalor = $request->base * $lnvalor;
        }else{
            $lnvalor = $impuesto->valor;
        }

        //$total = $request->base + $request->inafecto + $request->descuento + $request->impuesto + $request->isc;
        $base = $request->total - $lnvalor;
        $r['txtimpuesto'] = $lnvalor;
        $r['txtbase'] = $base;
        return $r;*/

        $sumar = $this->sumar($request);
        return $sumar;
    }

    public function cboimpuesto2(Request $request){
        if($request->cbo_impuesto2 != null){
            $lnid = $request->cbo_impuesto2;
            $impuesto = Taxes::find($request->cbo_impuesto2);
            $lnvalor = 0;
            if ($impuesto->tipocalculo == 'P') {
                $lnvalor = ($request->base + $request->inafecto + $request->impuesto + $request->descuento) * ($impuesto->valor / 100);
            }else{
                $lnvalor = $impuesto->valor;
            }

            $total = $request->base + $request->inafecto + $request->descuento + $request->impuesto + $request->isc;

            $r['txtimpuesto2'] = $lnvalor;
            $r['txttotal'] = $total;
            return $r;
        }
    }

    public function txtcodigo_vencimiento(Request $request){
        $lndias = 0;

        $tabla = new PaymentCondition;
        $condicionpago = $this->selectidcmd(intval($request->condicionpago_id), $tabla);

        $lndias = $condicionpago->dias;

        $txtvence = date("d-m-Y", strtotime($request->fecha."+ ".$lndias." days"));
        $txtvence = new Carbon($txtvence);
        $txtvence = $txtvence->format('Y-m-d');

        return $txtvence;
    }

    public function valida_contenido(Request $request){
        $r['valida'] = false;
        $r['mensaje'] = '';
        if (Cart::instance($this->instancia)->count() == 0) {
            $r['valida'] = true;
            $r['mensaje'] = 'Ingrese un producto';
        }

        return $r;
    }

    public function detalle_cobro(Request $request){
        $forma_pago = PaymentMethod::where('id', $request->forma_pago)->first();
        $item = count(Cart::instance($this->instancia_cobranza)->content());
        Cart::instance($this->instancia_cobranza)->add(['id' => $item + 1, 'name' => $forma_pago->id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'item' => $item + 1,
                'id' => $forma_pago->id,
                'fp' => $forma_pago->codigo,
                'descripcion' => $forma_pago->descripcion,
                'ref' => '',
                'importe' => $request->resto,
                'soles' => 0,
                'dolares' => 0,
                'ctactebanco_id' => $forma_pago->ctactebanco_id,
            ]]);

    }

    public function buscar_vendedores(Request $request){
        $resultado = Seller::select2($request->term);
        return $resultado;
    }

    public function forma_pago(Request $request){
        $data = json_decode($request->data);
        foreach($data as $data){
            $cart = collect(Cart::instance($this->instancia_cobranza)->content())->firstWhere('name', $data->ids->value);
            $options = $cart->options->merge([
                'item' => $cart->options->item,
                'id' => $cart->options->id,
                'fp' => $cart->options->fp,
                'descripcion' => $cart->options->descripcion,
                'ref' => $data->ref->value,
                'importe' => $data->importe->value,
                'soles' => $cart->options->soles,
                'dolares' => $cart->options->dolares,
                'ctactebanco_id' => $cart->options->ctactebanco_id,
            ]);

            Cart::instance($this->instancia_cobranza)->update($cart->rowId, [
                'options' => $options
            ]);
        }
        $importe = 0;
        foreach(Cart::instance($this->instancia_cobranza)->content() as $cart){
            $importe += $cart->options->importe;
        }
        if($request->neto > $importe){
            $resto = $request->neto - $importe;
            $r['valor'] = $resto;
            return $r;
        }
    }

    public function centralizar($pId){
        //lcRet = this.ocab.centralizacmd(lnId)
        $lctipo = 'V';
        $arroba = str_repeat("& nbsp;", 200);
        $lcRet = $this->provisionToPayProcedure->cntDocxCobrar($pId, $arroba);
        if($lcRet != null){
            return response()->json(['error' => $lcRet]);
        }
    }

    public function contabilizar($pId){
        //lcRet = this.ocab.contabilizacmd(lnId)
        $lcRet = str_repeat("& nbsp;", 200);
        $lcRet = $this->provisionToPayProcedure->generaCtacte($pId, 'docxpagar', $lcRet);
        if($lcRet != null){
            return response()->json(['error' => $lcRet]);
        }
    }

    private function setestadoref($docxpagar){ // clasic, formulario: mov_facturaventa, método: setestadoref

        // Actualizando referencia de almacén
        $estado = '';
        $ingresoalmacen_id = 0;

        if ($docxpagar->noalmacen == 1){
            //$ingresoalmacen_id = buscar el id del ingreso a almacen
            $estado = ($docxpagar->estado != 'ANULADO') ?  'FACTURADO' :  'ANULADO';
        }else{
            if ($docxpagar->id > 0) {
                //obtiene el último ingreso almacen del docxpagar
                $temporal_almacen = WarehouseIncome::select("id")->where('docxpagar_id', $docxpagar->id)->orderBy('id','desc')->first();
                if ($temporal_almacen) {
                    $ingresoalmacen_id = $temporal_almacen->id;
                }
                $estado = ($docxpagar->estado != 'ANULADO') ? 'FACTURADO' : 'ACTIVO';
            }
        }

        if ($ingresoalmacen_id > 0) {

            $ingresoalmacen = WarehouseIncome::findOrFail($ingresoalmacen_id);
            $ingresoalmacen->estado = $estado;
            $ingresoalmacen->save();

        }
    }
    ///miercoles
    private function obtener_precio_procedimiento_almacenado($nprecio){ // nprecio es del tipo venta

        $precio_a_seleccionar = 1; // ver como varia

        // el precio en ambos casos es del procedimiento almacenado
        if ( !$this->ltarifasuc == 2 ) {

            $precio = ( $nprecio == 3 || $nprecio == 2 ) ? 'precio' . $nprecio : 'precio' . 1;

        } else {

            // el precio nº : $precio_a_seleccionar(preguntar como puede variar)
            $precio = 'precio' . $precio_a_seleccionar;

        }

        return $precio;
    }

    public function validar_cantidad(Request $request) // objeto grilla-cantidad, método: Valid
    {

        $tipoventa = SaleType::find($request->tipo_venta);
        //dd($request->serie);
        $crear_kardex = $tipoventa->conkardex;

        $cantidad = $request->cantidad; // puede ser del modal o del detalle

        $id_o_item_evaluar_origen = $this->id_o_item_evaluar_origen($request); // ver origen y buscar el carrito correspondiente

        $producto = collect(Cart::instance($id_o_item_evaluar_origen['carrito'])->content())->firstWhere('id', $id_o_item_evaluar_origen['where']);

        if ( $producto->options->referencia_id > 0){ // docxpagar_detalle(cantidad) método: When

            if ( $cantidad != $producto->options->cantidad ) :  return response()->json(['errors' => array('detalle' => 'No se puede cambiar la cantidad'), 'cantidad' => $producto->options->cantidad ],422); endif;

        }

        if ( $producto->options->pideserie == 1 ) { // en caso pida serie deberia ir un solo producto, único de la serie

            if ( $cantidad > 1 ) :  return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser mayor a 1'), 'cantidad' => 1 ],422); endif;

        }

        if ( $cantidad < 0 && $this->lnegativo != 1 ) {

            return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser negativo'), 'cantidad' => 0 ],422);

        }
        /*
        if ( $cantidad > $producto->options->stock && $this->lstock == 1 ) {

            return response()->json(['errors' => array('detalle' => 'La cantidad excede al stock'), 'cantidad' => $producto->options->stock ],422);

        }*/

        // comprobar si el stock excede cuando los productos son repetidos y pertenece al mismo lote :
        $documentocom = Commercial::find($request->tipo_doc);
        if(isset($documentocom)) {$lnFactor = $documentocom->factor;}else{$lnFactor = 1;}
        $productodb = Productos::select('tipoproducto', 'peso')->where('id', $producto->options->id)->first();

        if ( $this->lstock == 1 && $crear_kardex == 1 && $productodb->tipoproducto != 'S' && $lnFactor > 0 ){

            // buscar en el docxpagar_detalle los productos repetidos(en el modal no se repiten)
            $docxpagar_detalles = collect(Cart::instance($this->instancia)->content())->where('name', $producto->options->id);

            if ( count($docxpagar_detalles) > 0 ) { // si existen docxpagar_detalles

                $cantidad_productos_repetidos = 0;

                foreach($docxpagar_detalles as $docxpagar_detalle){ // sumar la cantidad de los mismos productos y con el mismo lote

                    if ($request->item != $docxpagar_detalle->id){ // sirve cuando estamos en el detalle

                        $cantidad_productos_repetidos +=  ( $producto->options->lote == $docxpagar_detalle->options->lote ) ? $docxpagar_detalle->options->cantidad : 0;

                    }

                }

                if ( ($cantidad_productos_repetidos + $cantidad) > $producto->options->stock ) {

                    $cantidad = ($producto->options->stock < 0 ) ? 0 : $producto->options->stock - $cantidad_productos_repetidos; // se actualiza la cantidad
                    return response()->json(['errors' => array('detalle' => 'La cantidad excede el stock'), 'cantidad' => $cantidad ],422);

                }

            }

        }
        return $this->calculos_modal_o_detalle($request); // dependiendo del origen regresará los cálculos

    }

    public function existe_stock(Request $request){
        $producto = collect(Cart::instance($this->instancia_products)->content())->firstWhere('id', $request->id);
        return ($this->lstock == 1 && $producto->options->stock == 0) ? response()->json(['errors' => array('detalle' => 'No hay suficiente stock!')],422) : null;
    }

    public function validar_precio(Request $request){
        //dd(Cart::instance($this->instancia)->content());

        // el precio es el que cambia en el buscaritem, el preciolista es el que se ve

        $id_o_item_evaluar_origen = $this->id_o_item_evaluar_origen($request); // ver origen y buscar el carrito correspondiente

        $producto = collect(Cart::instance($id_o_item_evaluar_origen['carrito'])->content())->firstWhere('id', $id_o_item_evaluar_origen['where']);


        if ( $producto->options->referencia_id > 0){ // docxpagar_detalle(precio) método: When

            if ($request->precio != $producto->options->preciolista ) : return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio'), 'precio' => $producto->options->preciolista ],422); endif;

        }

        // en cuanto al preciolista, deberia consultar al método buscar_producto() y realizar el
        // proceso para obtener el precio correcto (este seria el precio origen)

        if ( $this->editar_precio == 2 ) { // no se puede editar el precio
            if ($request->precio != $producto->options->preciolista ) : return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio'), 'precio' => $producto->options->preciolista ],422);  endif;
        }

        if ( $this->editar_precio == 1 ) { // se puede cambiar el precio >= // se podria ejecutar el buscar_producto() para buscar el precio real

            if ($request->precio < $producto->options->preciolista ) : return response()->json(['errors' => array('detalle' => 'El precio no puede ser menor'), 'precio' => $producto->options->preciolista ],422);  endif;
        }

        if ( $request->precio < 0 && $this->lnegativo != 1 ) {

            return response()->json(['errors' => array('detalle' => 'El precio no puede ser negativo'), 'precio' => 0 ],422);

        }

        return $this->calculos_modal_o_detalle($request); // dependiendo del origen regresará los cálculos

    }

    private function id_o_item_evaluar_origen($request){

        if ( $request->item != '') { // detalle ó modal

            $data['carrito'] = $this->instancia;
            $data['where'] = $request->item;

        } else {

            $data['carrito'] = $this->instancia_products;
            $data['where'] = $request->id;

        }

        return $data;
    }


    private function calculos_modal_o_detalle($request){

        if ( $request->item == '' ) { // item vacío = instancia_products, en caso contrario cart_docxpagar_detalle

            extract($this->buscaritem($request)); // afecta los inputs del modal

            $this->actualizar_buscaritem_instancia_products($request->id, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper);

            // después normalmente llama al sumar en el clasic

            //$data['tipoprecio'] = $tipoprecio;
            //$data['precio'] = $precio; // el valor alterado es precio y el que no cambia preciolista (en detalle y en el modal)
            $data['cantidad'] = $cantidad;
            //$data['importe'] = $importe;
            //$data['isc'] = $isc;
            //$data['icbper'] = $icbper;
            //$data['serie'] = $serie;

            return $data;  // modal

        } else {

            extract($this->buscaritem($request));

            // doxpagar_detalle(datatable) se actualiza no es necesario regresar la data del buscaritem()
            $this->actualizar_buscaritem_cart_docxpagar_detalle($request->item, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper, $descuento, $preciolista, $serie);

            // sumar
            extract($this->sumar($request)); // los totales

            $data['tbase'] = $tbase;
            $data['tinafecto'] = $tinafecto;
            $data['tdescuento'] = $tdescuento;
            $data['tisc_icbper'] = $tisc_icbper;
            $data['igv'] = $igv;
            $data['tgratuito'] = $tgratuito;
            $data['ttotal'] = $ttotal;
            $data['importe'] = $importe;

            return $data;

        }

    }

    private function inicializar_buscaritem($request){ // inicializando variables

        // si envia el item quiere decir que vamos a buscar

        if ( isset($request->id) ) { // cuando se llama desde el modal

            // usos : buscaritem

            $producto = collect(Cart::instance($this->instancia_products)->content())->firstWhere('id', $request->id);

        }

        if ( isset($request->item) ) { // cuando se llama desde el docxpagar_detalle

            // usos : buscaritem

            $producto = collect(Cart::instance($this->instancia)->content())->firstWhere('id', $request->item);

        }

        // usos : buscaritem
        $producto = (isset($producto)) ? $producto : null;

        // usos : buscaritem, buscaritem_todoelcarrito
        $tipo_venta = ( isset($request->tipo_venta) ) ? SaleType::select('esgratuito')->where('id', $request->tipo_venta)->first() : null;

        // usos : buscaritem, buscaritem_todoelcarrito x
        $igv = ( isset($request->igv) ) ? Taxes::select('codigo','tipocalculo')->where('id', $request->igv)->first() : null;

        // usos : buscaritem, buscaritem_todoelcarrito
        //dd(isset($request->tipo_doc));
        $documentocom = ( isset($request->tipo_doc) ) ? Commercial::find($request->tipo_doc) : null;


        // usos : buscaritem, buscaritem_todoelcarrito
        $moneda = ( isset($request->moneda) ) ? Currency::find($request->moneda) : null;

        // usos : buscaritem, buscaritem_todoelcarrito
        $tipoafectaigv = ( isset($request->tipo_afectacion_igv) ) ? AffectIgv::select('codigo')->where('id', $request->tipo_afectacion_igv)->first() : null;


        $r['tipo_venta'] = $tipo_venta;
        $r['igv'] = $igv;
        $r['producto'] = $producto;
        $r['documentocom'] = $documentocom;
        $r['moneda'] = $moneda;
        $r['tipoafectaigv'] = $tipoafectaigv;

        return $r;

    }

    private function actualizar_buscaritem_instancia_products($id, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper){

        // cuando viene del modal id = producto_id
        // actualizo estos valores por que aun no se agregar en el docxpagar_detalle

        $cart = collect(Cart::instance($this->instancia_products)->content())->firstWhere('id', $id);
        $item = Cart::instance($this->instancia_products)->get($cart->rowId);

        $options = $item->options->merge([
            'tipoprecio' => $tipoprecio,
            'precio' => $precio, //el precio afectado por el descuento
            //'cantidad' => $cantidad, //toma el valor del input del modal
            'importe' => $importe,
            'isc' => $isc,
            'icbper' => $icbper
        ]);

        Cart::instance($this->instancia_products)->update($cart->rowId, [
            'options' => $options]);

    }

    private function actualizar_buscaritem_cart_docxpagar_detalle($item, $tipoprecio, $precio, $cantidad, $importe, $isc, $icbper, $descuento, $preciolista, $serie){

        // cuando viene del detalle id = item

        $cart = collect(Cart::instance($this->instancia)->content())->firstWhere('id', $item);
        $item = Cart::instance($this->instancia)->get($cart->rowId);

        $options = $item->options->merge([
            'tipoprecio' => $tipoprecio,
            'precio' => $precio, //el precio afectado por el descuento
            'cantidad' => $cantidad,
            'descuento' => $descuento,
            'importe' => $importe,
            'isc' => $isc,
            'icbper' => $icbper,
            'preciolista' => $preciolista,
            'serie' => $serie
        ]);

        Cart::instance($this->instancia)->update($cart->rowId, [
            'options' => $options]);
        //dd(Cart::instance($this->instancia)->content());
    }

    public function validar_descuento(Request $request){

        $id_o_item_evaluar_origen = $this->id_o_item_evaluar_origen($request); // ver origen y buscar el carrito correspondiente
        //$usuario = UserByCompany::select('pdescuento')->where('id', Auth::id())->first(); // lo consulto en el constructor

        $producto = collect(Cart::instance($id_o_item_evaluar_origen['carrito'])->content())->firstWhere('id', $id_o_item_evaluar_origen['where']);

        if ( $producto->options->referencia_id > 0){ // docxpagar_detalle(precio) método: When

            if ($request->descuento != $producto->options->descuento ) : return response()->json(['errors' => array('detalle' => 'No puede cambiar el descuento'), 'descuento' => $producto->options->descuento ],422); endif;

        }

        if ( $request->descuento < 0) { // no puede ser negativo

            return response()->json(['errors' => array('detalle' => 'No puede ingresar valores negativos'), 'descuento' => $this->pdescuento ],422);

        }

        if ( $this->pdescuento < $request->descuento ) { // no puede ser mayor a lo asignado en la configuración por usuario

            return response()->json(['errors' => array('detalle' => 'El descuento excede lo permitido'), 'descuento' => $this->pdescuento ],422);

        }

        return $this->calculos_modal_o_detalle($request); // dependiendo del origen regresará los cálculos

    }


    public function agregar_productos(Request $request){
        
        $data = json_decode($request->data);
        $productos_id = array_column(array_column($data, 'ids'),'value'); // id productos seleccionados
        $count = count($data);

        if ( $count > 0 ){ // existen productos seleccionados ?

            $buscaritem_todoelcarrito = $this->buscaritem_todoelcarrito($request, $data); // validar y unir la información antes de agregar a docxpagar_detalle

            if ( $buscaritem_todoelcarrito != null) { return $buscaritem_todoelcarrito; }



            $productos = collect(Cart::instance($this->instancia_products)->content())->whereIn('id', $productos_id); // productos seleccionados

            $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first(); // en este caso el id es el item

            $item = ($ultimoCart) ? $ultimoCart->id : 0;  // último item del docxpagar

            $this->llenar_cart_docxpagar_detalle($productos, $item, $request->tipo_venta, $request->moneda);

            $sumar = $this->sumar($request); // ya no se envia el ultimo parámetro arreglar
        }

        return ( isset($sumar) ) ? $sumar : null;

    }


    public function buscaritem_todoelcarrito($request, $data){ // solo cuando viene del modal

        extract($this->inicializar_buscaritem($request)); // no se envia id, item ok
        if(isset($documentocom)) {$lnFactor = $documentocom->factor;}else{$lnFactor = 1;}
        $tipoventa = SaleType::find($request->tipo_venta);
        //dd($request->serie);
        $crear_kardex = $tipoventa->conkardex;
        foreach ( $data as $d ) {

            $cart = collect(Cart::instance($this->instancia_products)->content())->firstWhere('id', $d->ids->value);
            $productodb = Productos::select('tipoproducto', 'peso')->where('id', $d->ids->value)->first();
            $serie = $d->series->value;
            $cantidad = ($d->cantidades->value == null) ? 0 : $d->cantidades->value;
            $preciolista = ($d->precios->value == null) ? 0 : $d->precios->value;
            $descuento = ($d->dsctos->value == null) ? 0 : $d->dsctos->value;
            $cantidad_productos_repetidos = 0;

            // validar serie

            if ( $cart->options->pideserie == 1 ) {

                if ($serie != null) {

                    $request->request->add(['serie' => $serie]); // agrega al request un valor sin perder su clase
                    $request->request->add(['producto_id' => $cart->id]); // agrega al request un valor sin perder su clase
                    $valida_serie = $this->valida_serie($request);

                    if ( $valida_serie !== null ) : // si hay error
                        return $valida_serie;
                    endif;

                }

            }

            // validar cantidad
            //igualamos a 1 si es verdad :v
            if ( $cart->options->pideserie == 1) { // en caso pida serie deberia ir un solo producto, único de la serie

                if ( $cantidad > 1 ) :
                    return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser mayor a 1, en el producto: '. $cart->options->codigo), 'cantidad' => 1 ],422);
                endif;

            }

            //lnegativo no se si igualar a 0 o a 1 :v
            if ( $cantidad < 0 && $this->lnegativo != 1) {

                return response()->json(['errors' => array('detalle' => 'La cantidad no puede ser negativo, en el producto ' . $cart->options->codigo), 'cantidad' => 0 ],422);

            }

            if ( $cantidad > $cart->options->stock && $this->lnegativo != 1 ) {

                return response()->json(['errors' => array('detalle' => 'La cantidad excede al stock'), 'cantidad' => $cart->options->stock ],422);

            }

            // validar precio

            // en cuanto al preciolista, deberia consultar al método buscar_producto() y realizar el
            // proceso para obtener el precio correcto (este seria el precio origen)

            $origen = collect(Cart::instance($this->instancia_products)->content())->firstWhere('id', $cart->id); // consultar al origen en el caso del modal: instancia_products
            if ( $this->editar_precio == 2 ) { // no se puede editar el precio
                if ($preciolista != $origen->options->preciolista ) :

                    return response()->json(['errors' => array('detalle' => 'No puede cambiar el precio del producto: ' . $cart->options->codigo), 'precio' => $origen->options->preciolista ],422);

                endif;
            }

            if ( $this->editar_precio == 1 ) { // se puede cambiar el precio >=
                if ($preciolista < $origen->options->preciolista ) :

                    return response()->json(['errors' => array('detalle' => 'El precio del producto: ' . $cart->options->codigo . 'no puede ser menor'), 'precio' => $origen->options->preciolista ],422);

                endif;
            }

            // validar descuento

            if ( $descuento < 0) { // no puede ser negativo

                return response()->json(['errors' => array('detalle' => 'No puede ingresar valores negativos'), 'descuento' => $this->pdescuento ],422);

            }

            if ( $this->pdescuento < $descuento ) { // no puede ser mayor a lo asignado en la configuración por usuario

                return response()->json(['errors' => array('detalle' => 'El descuento excede lo permitido (' . $this->pdescuento . ')'), 'descuento' => $this->pdescuento ],422);

            }

            // operaciones foreach

            $tipoprecio = ( $cart->options->tipoprecio == 0 ) ? 1 : $cart->options->tipoprecio; // se actuliza el tipo_precio

            $precio = ( $tipo_venta->esgratuito == 1 && $igv->codigo == '99' ) ? 0 : $preciolista  * ( 1 - ( $descuento / 100 ) ); // se actuliza el precio

            // comprobar si el stock excede cuando los productos son repetidos y pertenece al mismo lote :

            if ( $this->lstock == 1 && $crear_kardex == 1 && $productodb->tipoproducto != 'S' && $lnFactor > 0 ){

                // buscar en el docxpagar_detalle los productos repetidos // el name = producto_id
                $docxpagar_detalles = collect(Cart::instance($this->instancia)->content())->where('name', $cart->id);

                //if ( count($docxpagar_detalles) > 0 ) { // si existen docxpagar_detalles // en este caso siempre tiene, ver el agregar_productos()

                    foreach($docxpagar_detalles as $docxpagar_detalle){ // sumar la cantidad de los mismos productos y con el mismo lote
                        $cantidad_productos_repetidos +=  ( $request->lote == $docxpagar_detalle->options->lote ) ? $docxpagar_detalle->options->cantidad : 0;
                    }

                    if ( ($cantidad_productos_repetidos + $cantidad) > $cart->options->stock ) {

                        // mensaje : No hay stock suficiente
                        $cantidad_mensaje = ($cantidad->options->stock < 0 ) ? 0 : $cart->options->stock - $cantidad_productos_repetidos; // se actualiza la cantidad

                        return response()->json(['errors' => array('detalle' => 'No hay suficiente stock : ' . $cantidad_mensaje . ' para el producto : ' . $cart->options->codigo ) ],422);

                    }

                //}

            }

            $cantidad = ( $tipoprecio == 1 || $tipoprecio == 4 ) ? $cantidad : $productodb->peso;

            $importe = $cantidad * $precio; // se actualiza el importe

            $parancelaria = Parancelaria::selectparentcmd($cart->options->id);

            $isc = 0;
            $icbper = 0;

            if ($parancelaria) { // en caso exista data

                if ( $parancelaria->tipo == 'N' ) { // calcular $icbper y $isc

                    if ( trim($parancelaria->tipoisc) == 'ICBPER' ) {
                        $icbper = $cantidad * $parancelaria->isc;  // se actualiza icbper
                    } else {
                        $isc = $cantidad * $parancelaria->isc; // se actualiza isc
                    }

                } else {

                    if (trim($parancelaria->tipoisc) == 'ICBPER') {
                        $icbper = $importe * ($parancelaria->isc/100); // se actualiza icbper
                    } else {
                        $isc = $importe * ($parancelaria->isc/100); // se actualiza isc
                    }

                }

            }

            $icbper = ( $moneda->tipo != 'N' ) ?  $icbper / $request->tcambio : $icbper; // se actualiza icbper

            $importe = $importe + ( $isc * 1.18 ); // se actualiza el importe

            // datos a actualizar

            /*
                tipoprecio
                precio
                cantidad // input modal
                importe
                isc
                icbper
                serie // input modal
                preciolista // input modal
                descuento // input modal
            */

            $item = Cart::instance($this->instancia_products)->get($cart->rowId);

            $options = $item->options->merge([ // actualizo el instancia_products porque uno los inputs con este carrito y al final se agrega al carrito de docxpagar_detalle
                'tipoprecio' => $tipoprecio,
                'precio' => $precio,
                'cantidad' => $cantidad, // input modal
                'importe' => $importe,
                'isc' => $isc,
                'icbper' => $icbper,
                'serie' => $serie, // input modal
                'preciolista' => $preciolista, // input modal
                'descuento' => $descuento // input modal
            ]);

            Cart::instance($this->instancia_products)->update($cart->rowId, [
                'options' => $options]);


        }

    }

    private function llenar_cart_docxpagar_detalle($productos, $item, $tipo_venta_id, $moneda_id){

        foreach ($productos as $producto){

            $productodb = Productos::selectidcmd($producto->options->id); // obtener algunos datos adicionales del producto para los cálculos
            $getctaventapdto_data = $this->getctaventapdto_data($producto, $tipo_venta_id, $moneda_id, $productodb); // clasic, objeto: txtxidproducto1, método: Valid

            $producto_existente = $this->se_permite_repetir_producto($producto);

            if ($producto_existente){

                $this->editar_docxpagar_detalle($producto_existente->rowId, $producto, $productodb, $getctaventapdto_data);

            } else {
                $item += 1; // nº item
                $this->agregar_docxpagar_detalle($item, $producto, $productodb, $getctaventapdto_data);
            }
        }

    }


    private function getctaventapdto_data($docxpagar_detalle, $tipo_venta_id, $moneda_id, $productodb){

        $getctaventapdto = Productos::getctaventapdto_billing($docxpagar_detalle->options->id, $tipo_venta_id); // clasic, objeto: txtidproducto1, método Valid
        $tipoventa = SaleType::select('esanticipo')->where('id', $tipo_venta_id)->first();
        $moneda = Currency::select('tipo')->where('id', $moneda_id)->first();


        $cuenta_id = 0;
        $cuenta_codigo = '';
        $pide_ccosto = 0;
        $tipo_auxiliar = '';
        $esnuevo = 0;
        $referencia_id = 0;
        $docrefer = null; // no hacer cambios
        $tipoafectaigv_id =  null; // no hacer cambios
        $tipoafectaigv_codigo = null; // no hacer cambios


        if ( $getctaventapdto ) {

            $cuenta_id = ( $getctaventapdto->cuenta_id ) ? $getctaventapdto->cuenta_id : 0; // tabla: tipoventaxfamilia, campo: cuenta_id
            $cuenta_codigo = ( $getctaventapdto->codigo ) ? $getctaventapdto->codigo : ''; // tabla: pcg, campo: codigo
            $pide_ccosto = ( $getctaventapdto->pide_ccosto ) ? $getctaventapdto->pide_ccosto : 0;  // tabla: pcg, campo: pide_ccosto
            $tipo_auxiliar = ( $getctaventapdto->tipo_auxiliar ) ? $getctaventapdto->tipo_auxiliar: ''; // tabla: pcg, campo: tipo_auxiliar

            if ( $tipoventa->esanticipo == 1 && $cuenta_codigo == '122' && $moneda->tipo != 'N' )  {
                $cuenta_id = ( $getctaventapdto->ctacosto_id ) ? $getctaventapdto->ctacosto_id : 0; // tabla: tipoventaxfamilia, campo: ctacosto_id
                $cuenta_codigo = ( $getctaventapdto->ctacosto_cod ) ? $getctaventapdto->ctacosto_cod : ''; // tabla: pcg, campo: ctacosto_cod
            }
        }

        // si la referencia_id = 0, hacer lo siguiente:

        if ( $tipo_auxiliar == 'T' && $tipoventa->esanticipo == 0) {

            $esnuevo  = 1;

        } else {

            $esnuevo = $referencia_id = 0; $docrefer = '';

        }


        if ( $productodb->esafecto == 0 && $tipoafectaigv_id = $request->tipoafecta_igv) { // revisado esto funciona en el editar (lo comentado) // preguntar la razón de la 2º condición - clasic, objeto: txtidproducto1, método Valid

            $tipoafectaigv_id = 8;
            $tipoafectaigv_codigo = '20'; // clasic: afeigv_cod

        }

        // regregar lo siguiente:
        // cuenta_id
        // cuenta_codigo
        // pide_ccosto
        // tipo_auxiliar
        // esnuevo
        // referencia_id
        // docrefer
        // tipoafectaigv_id
        // tipoafectaigv_codigo

        // nota: si regreso nulo no debe cambiar el valor
        $data['cuenta_id'] = $cuenta_id;
        $data['cuenta_codigo'] = $cuenta_codigo;
        $data['pide_ccosto'] = $pide_ccosto;
        $data['tipo_auxiliar'] = $tipo_auxiliar;
        $data['esnuevo'] = $esnuevo;
        $data['referencia_id'] = $referencia_id;
        $data['docrefer'] = $docrefer;
        $data['tipoafectaigv_id'] = $tipoafectaigv_id;
        $data['tipoafectaigv_codigo'] = $tipoafectaigv_codigo;

        return $data;
    }

    private function se_permite_repetir_producto($docxpagar_detalle){
        if ($this->lunico == 1) {
          return collect(Cart::instance($this->instancia)->content())->firstWhere('name', $docxpagar_detalle->options->id); // Validar si el producto existe
        }
        return null;
    }

    private function editar_docxpagar_detalle($rowId, $producto, $productodb, $getctaventapdto_data){

        $options = $item->options->merge([
            'id' => $producto->options->id,
            'items' => $producto->options->items,
            'codigo' => $producto->options->codigo,
            'cumseps' => $producto->options->cumseps,
            'descripcion' => $producto->options->descripcion,
            'caracteristicas' => $producto->options->caracteristicas,
            'umedida_codigo' => $producto->options->umedida, // clasic, objeto: txtidproducto, método: Valid, es ume_cod
            'ubicacion' => $producto->options->ubicacion,
            'stock' => $producto->options->stock,
            'esafecto' => $producto->options->esafecto,
            'flag' => $producto->options->flag,
            'estado' => $producto->options->estado,
            'umedida_id' => $producto->options->umedida_id, // clasic, objeto: txtidproducto, método: Valid, es um1
            'marca' => $producto->options->marca,
            'editar_precio' => $producto->options->editar_precio,
            'pideserie' => $producto->options->pideserie,
            'serie' => $producto->options->serie, // modal o detalle
            'tipoprecio' => $producto->options->tipoprecio, // este  valor se asigna en el evento del formulario nuevalinea
            'lote' => $producto->options->lote,
            'importe' => $producto->options->importe,
            'preciolista' => $producto->options->preciolista,  // modal o detalle
            'cantidad' => $producto->options->cantidad, // modal o detalle
            'precio' =>  $producto->options->precio,
            'descuento' => $producto->options->descuento, // modal o detalle
            'isc_icbper' => 0, // es la suma del isc + icbper
            'referencia_codigo' => '',
            'cuenta_data' => null,
            'centro_costo_id' => $producto->options->centro_costo_id, //modal detalle
            'centro_costo_codigo' => $producto->options->centro_costo_codigo, // modal detalle
            'centro_costo_descripcion' => $producto->options->centro_costo_descripcion, // modal detalle
            'tipoafectaigv_codigo' => '',
            'op' => '',
            'lote' => '',
            'isc' => '',
            'icbper' => '',
            //datos adicionales para los cálculos // algunos datos se puede ver clasic, objeto: txtidproducto1, método: Valid
            'peso' => $productodb->peso,
            'consumo' => $productodb->consumo,
            'tipoproducto' => $productodb->tipoproducto,
            'utilidad' => $productodb->utilidad,
            'pidelote' => $productodb->pidelote, // tal vez en el futuro se considere en el modal
            'um2' => $productodb->ucompra_id,
            'conversion' => $productodb->conversion,
            // getctaventapdto_data
            'cuenta_id' => $getctaventapdto_data['cuenta_id'],
            'cuenta_codigo' => $getctaventapdto_data['cuenta_codigo'],
            'pide_ccosto' => $getctaventapdto_data['pide_ccosto'],
            'esnuevo' => $getctaventapdto_data['esnuevo'],
            'referencia_id' => $getctaventapdto_data['referencia_id'],
            'tipoafectaigv_id' => $getctaventapdto_data['tipoafectaigv_id'],
            //ver si se puede agregar descuentos (esto es por usuario)
            'pdescuento' => $producto->options->pdescuento,
            'actividad_id' => null,
            'actividad_codigo' => '',
            'actividad_descripcion' => '',
            'proyecto_id'=> null,
            'proyecto_codigo' => '',
            'proyecto_descripcion' => '',
        ]);

        Cart::instance($this->instancia)->update($rowId, [
            'options' => $options]);

    }

    private function agregar_docxpagar_detalle($item, $producto, $productodb, $getctaventapdto_data){
        $centrocosto = PointSale::centrocosto(\session('point_id'));
        $items = count(Cart::instance($this->instancia)->content());
        Cart::instance($this->instancia)->add(['id' => $item , 'name' => $producto->options->id, 'qty' => 1, 'price' => 0.00,
            'options' => [
                'id' => $producto->options->id,
                'items' => $items+1,
                'codigo' => $producto->options->codigo,
                'cumseps' => $producto->options->cumseps,
                'descripcion' => $producto->options->descripcion,
                'caracteristicas' => $producto->options->caracteristicas,
                'umedida_codigo' => $producto->options->umedida, // clasic, objeto: txtidproducto, método: Valid, es ume_cod
                'ubicacion' => $producto->options->ubicacion,
                'stock' => $producto->options->stock,
                'esafecto' => $producto->options->esafecto,
                'flag' => $producto->options->flag,
                'estado' => $producto->options->estado,
                'umedida_id' => $producto->options->umedida_id, // clasic, objeto: txtidproducto, método: Valid, es um1
                'marca' => $producto->options->marca,
                'editar_precio' => $producto->options->editar_precio,
                'pideserie' => $producto->options->pideserie,
                'serie' => $producto->options->serie, // modal o detalle
                'tipoprecio' => $producto->options->tipoprecio, // este  valor se asigna en el evento del formulario nuevalinea
                'lote' => $producto->options->lote,
                'importe' => $producto->options->importe,
                'preciolista' => $producto->options->preciolista,  // modal o detalle
                'cantidad' => $producto->options->cantidad, // modal o detalle
                'precio' =>  $producto->options->precio,
                'descuento' => $producto->options->descuento, // modal o detalle
                'isc_icbper' => 0, // es la suma del isc + icbper
                'referencia_codigo' => '',
                'cuenta_data' => null,
                'centro_costo_id' => $centrocosto->id,
                'centro_costo_codigo' => $centrocosto->codigo,
                'centro_costo_descripcion' => $centrocosto->descripcion,
                'tipoafectaigv_codigo' => '',
                'op' => '',
                'lote' => '',
                'isc' => '',
                'icbper' => '',
                //datos adicionales para los cálculos // algunos datos se puede ver clasic, objeto: txtidproducto1, método: Valid
                'peso' => $productodb->peso,
                'consumo' => $productodb->consumo,
                'tipoproducto' => $productodb->tipoproducto,
                'utilidad' => $productodb->utilidad,
                'pidelote' => $productodb->pidelote, // tal vez en el futuro se considere en el modal
                'um2' => $productodb->ucompra_id,
                'conversion' => $productodb->conversion,
                // getctaventapdto_data
                'cuenta_id' => $getctaventapdto_data['cuenta_id'],
                'cuenta_codigo' => $getctaventapdto_data['cuenta_codigo'],
                'pide_ccosto' => $getctaventapdto_data['pide_ccosto'],
                'esnuevo' => $getctaventapdto_data['esnuevo'],
                'referencia_id' => $getctaventapdto_data['referencia_id'],
                'tipoafectaigv_id' => $getctaventapdto_data['tipoafectaigv_id'],
                //ver si se puede agregar descuentos (esto es por usuario)
                'pdescuento' => $producto->options->pdescuento,
                'actividad_id' => null,
                'actividad_codigo' => '',
                'actividad_descripcion' => '',
                'proyecto_id'=> null,
                'proyecto_codigo' => '',
                'proyecto_descripcion' => '',
            ]
        ]);

    }

    public function llenarde($docxpagar){
        $ladd = 1;
        $lnid = $lcxml = $lnalmacen = $odata = $odataC = 0;
        $lnid = $docxpagar->id;
        $lnalmacen = $docxpagar->noalmacen;

        ///llamamos al docxpagar_detalle
        $docxpagar_detalle = DocumentToPayDetail::selectidcmd($lnid);

        //docxpagar_formapago
        $docxpagar_formapago = PaymentMethod::selectidcmd($lnid);

        //ingresoalmacen
        if($ladd == 1 || $lnalmacen == 1){
            $ingreso = $this->selectrefcmd($lnId, 'X');
        }else{
            $ingreso =  WarehouseIncome::selectidcmd(0);
        }

    }
    //
    public function selectrefcmd($pId, $pTipo){
        switch($pTipo){
            case 'O': //Orden de Compra
                $result = WarehouseIncome::orden_compra($pId);
                return $result;
            case 'P': //Pedido de Almacen
                $result = WarehouseIncome::pedido_almacen($pId);
                return $result;
            case 'X': //Documento por pagar
                $result = WarehouseIncome::documento_pagar($pId);
                return $result;
            case 'I': //Ingresos de Transfeencia
                $result = WarehouseIncome::ingreso_transferencia($pId);
                return $result;
            case 'T': //Orden de Trabajo
                $result = WarehouseIncome::orden_trabajo($pId);
                return $result;
            case 'TI': //Ingresos de Orden de Trabajo
                $result = WarehouseIncome::ingreso_orden_trabajo($pId);
                return $result;
        }
    }
}
