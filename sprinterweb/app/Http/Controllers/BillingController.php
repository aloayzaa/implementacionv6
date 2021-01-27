<?php

namespace App\Http\Controllers;

use App\AffectsIgv\Entities\AffectIgv;
use App\BusinessUnit\Entities\BusinessUnit;
use App\Panel\Companies\Entities\Pempresa;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Customers\Entities\CustomerAddress;
use App\DetractionType\Entities\DetractionType;
use App\Documents\Entities\Commercial;
use App\DocumentToPay\Entities\DocumentoToPayDetraction;
use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\DocumentToPay\Entities\DocumentToPayPaymentWay;
use App\Bank\Entities\Bank;
use App\Http\Traits\Controls\UserPrivileges;
use App\Http\Traits\ValidateBilling;
use App\PaymentCondition\Entities\PaymentCondition;
use App\PaymentType\Entities\PaymentType;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\Procedures\DocumentToPayProcedure;
use App\Products\Entities\Productos;
use App\SalesTypes\Entities\SaleType;
use App\Seller\Entities\Seller;
use App\Taxes\Entities\Taxes;
use App\PaymentMethod\Entities\PaymentMethod;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Parancelaria\Entities\Parancelaria;
use App\Http\Requests\BillingRequest;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Http\Controllers\AuxiliaryApplyNCController;
use App\Reference\Entities\Reference;
use App\Http\Controllers\GeneraFEController;
use App\Http\Controllers\ReferenceGuideBillingController;
use App\Http\Controllers\LlenarDeBillingController;
use App\Http\Controllers\GrabarDetBillingController;
use App\Http\Controllers\PaymentMethodBillingController;
use App\Http\Controllers\CpeBillingController;
use App\Http\Controllers\MovctacteBillingController;
use App\Http\Controllers\DetractionBillingController;
use App\Http\Controllers\DocumentApllyBillingController;
use App\DocumentToPay\Entities\DocumentToPayCPE;
use Illuminate\Database\Eloquent\Model;
use App\DocumentToPay\Entities\DocumentToPayWork;
use App\Http\Traits\Controls\Movement;

class BillingController extends Controller
{
    //PurchaseDocument
    use UserPrivileges, ValidateBilling, Movement;

    private $var = 'billing';
    protected $user;
    protected $privilegios = '03.02.05';
    private $ventana = 'MOV_FACTURAVENTA';
    private $tabla_cabecera = 'docxpagar';
    private $documenttopayprocedure;
    private $provisionesPorPagarProcedure;
    private $lvalidadocpto;
    private $lcpe;
    private $lnegativo;
    private $lconimptos;
    private $lcontabiliza;
    private $lcentraliza;
    private $cservcpe;
    private $cart_notacreditodebito_cabecera = 'notacreditodebito_cabecera';
    private $cart_notacreditodebito_detalle = 'notacreditodebito_detalle';
    private $cart_referencia = 'referencia';
    private $llenarDeBillingController;
    private $warehouseReferenceBilling;
    private $detailBillingController;
    private $paymentMethodBillingController;
    private $cpeBillingController;
    private $movctacteBillingController;
    private $detractionBillingController;
    private $documentApllyBillingController;
    private $referenceBillingController;

    private $docxpagar; // OBJETO DOCXPAGAR UTILIZADO EN EL ELIMINAR

    public function __construct(DocumentToPayProcedure $documenttopayprocedure, ProvisionesPorPagarProcedure $provisionesPorPagarProcedure)
    {

        $this->user = Session::get('usuario');
        $this->obtener_cliente();
        $this->documenttopayprocedure = $documenttopayprocedure;

        // mov_facturaventa - método Init
        $this->lcentraliza = Pempresa::get_parametro('CEN_VENTA')->valor; //Centralización módulo de Ventas
        $this->lvalidadocpto = Pempresa::get_parametro('FTDOCXPUNTO')->valor; //Validar Tipo de Documento por Punto de Venta
        $this->lcpe = Pempresa::get_parametro('CPE_ACTIVAR')->valor; // Activar Factura Electrónica
        $this->lnegativo = Pempresa::get_parametro('FTCONNEGAT')->valor; // Se permite valores negativos en la facturación    
        $this->lconimptos = Pempresa::get_parametro('CON_IMPTOS')->valor; // El precio de venta incluye impuestos
        $this->cservcpe =  Pempresa::get_parametro('CPE_SERVIDOR')->valor; // Factura Electrónica. Tipo Servidor

        // adicionales
        $this->provisionesPorPagarProcedure = $provisionesPorPagarProcedure;
        $this->lcontabiliza = 1; // ver propiedad, formulario mov_facturaventa
        $this->llenarDeBillingController =  new LlenarDeBillingController(session()->get('id'));


        // carritos
        $this->warehouseReferenceBilling = new WarehouseReferenceBillingController(session()->get('id'));
        $this->detailBillingController = new DetailBillingController(session()->get('id'));
        $this->paymentMethodBillingController = new PaymentMethodBillingController(session()->get('id'));
        $this->cpeBillingController =  new cpeBillingController(session()->get('id'), $this->lcpe);
        $this->movctacteBillingController = new movctacteBillingController(session()->get('id'));
        $this->detractionBillingController = new DetractionBillingController(session()->get('id'));
        $this->documentApllyBillingController = new DocumentApllyBillingController(session()->get('id'));
        $this->referenceBillingController =  new ReferenceBillingController(session()->get('id'));
    }


    public function index() 
    {
        $this->eliminar_variables_de_sesion();
        $this->eliminar_carritos();


        $data['var'] = $this->var;
        $data['view'] = link_view('Ventas', 'Transacción', 'Facturación', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('billings.list', $data);
    }

    public function list() 
    {
        $billings = DocumentToPay::listadoCmd('MOV_FACTURAVENTA'); // Ver docxpagar, método rptlistado
        return DataTables::of($billings)->make();
    }


    public function create() 
    {
        $this->eliminar_variables_de_sesion();
        $this->eliminar_carritos();

        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['today'] =  Carbon::today()->format('Y-m-d');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['unegocios'] = BusinessUnit::where('estado',1)->get();
        $data['puntosventa'] = PointSale::where('estado',1)->get();
        $data['tiposventa'] = SaleType::where('estado',1)->get();
        $data['monedas'] = Currency::where('estado',1)->get();
        $data['condicionespago'] = PaymentCondition::where('estado',1)->get();
        $data['tiposafectaigv'] = AffectIgv::where("estado", 1)->get();
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        $data['documentoscom'] = Commercial::selectfrmcmd($this->ventana);
        $data['vendedores'] = Seller::where("estado", 1)->get();
        $data['tiposdetraccion'] = DetractionType::where("estado", 1)->get();
        $data['formaspago'] = PaymentMethod::where("estado", 1)->get();
        $data['mediospago'] = PaymentType::where("estado", 1)->get();
        $data['bancos'] = Bank::where("estado", 1)->get();
        $data['view'] = link_view('Ventas', 'Transacción', 'Facturación', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('billings.create', $data);
    }


    public function store(BillingRequest $request)
    {

        // validar inputs
        //$buscaritem_todoelcarrito_store =  $this->detailBillingController->buscaritem_todoelcarrito_store($request);
        //if ($buscaritem_todoelcarrito_store != null) { return $buscaritem_todoelcarrito_store; }

        //totales
        $sumar = $this->sumar($request);

        // inicializar
        $punto_venta = PointSale::selectidcmd_billing($request->punto_venta);
        $tipo_venta = SaleType::findOrFail($request->tipo_venta);

        // tercero
        $tercero = Customer::select2_id($request->tercero);
        $razonsocial = $tercero->descripcion; // clasic, formulario: MOV_FACTURAVENTA, método: validar
        // clasic, objeto: Cntclieprov1 - txtcodigo, método: Valid
        $razonsocial = ( !empty(trim($tercero->conyuge)) && strlen(trim($tercero->nrodocidentidad)) == 17 ) ? $tercero->descripcion . "/" . $tercero->conyuge : $razonsocial;

        // tipo nota
        $documentocom = Commercial::findOrFail($request->tipo_doc); // clasic, objeto: cbotipodoc, método: InteractiveChange
        $tiponotacredito_id = null;
        $tiponotadebito_id = null;

        if ( trim($documentocom->codsunat) == '08' || trim($documentocom->codsunat) == '98' ) :
            $tiponotadebito_id = $request->tipo_nota;
        endif;

        if ( trim($documentocom->codsunat) == '07' || trim($documentocom->codsunat) == '97' ) :
             $tiponotacredito_id = $request->tipo_nota;
        endif;

        // clasic, formulario: MOV_FACTURAVENTA, método: validar
        $refgremision = ( empty($request->serierem) ) ? '' : trim($request->serierem) . '-' . trim($request->nrorem);
        $noalmacen = ( empty($request->crear_kardex) ) ? 0 : 1;
        if ( $documentocom->codsunat == '08') : $noalmacen = 0;  endif;

        DB::beginTransaction();

        try {

            $docxpagar = new DocumentToPay;
            $docxpagar->periodo_id = Session::get('period_id');
            $docxpagar->fechaproceso = $request->fecha_proceso;
            $docxpagar->unegocio_id = $request->unidad_negocio;
            $docxpagar->horaproceso = date("H:i:s");
            $docxpagar->origen = 'C';
            $docxpagar->tercero_id = $request->tercero;
            $docxpagar->puntoventa_id = (empty($request->punto_venta)) ? null : $request->punto_venta;
            $docxpagar->sucursal_id = (empty($punto_venta->sucursal_id)) ? null : $punto_venta->sucursal_id;
            $docxpagar->almacen_id = (empty($punto_venta->almacen_id)) ? null : $punto_venta->almacen_id;
            $docxpagar->tipotransaccion_id = (empty($tipo_venta->tipotransaccion_id)) ? null : $tipo_venta->tipotransaccion_id; //  tabla: tipoventa, campo : tipotransaccion_id,
            $docxpagar->tipoventa_id = $request->tipo_venta;
            $docxpagar->moneda_id = $request->moneda;
            $docxpagar->documento_id = $request->tipo_doc; 
            $docxpagar->seriedoc = $request->serie_doc;
            $docxpagar->numerodoc = $request->numero_doc;
            $docxpagar->fechadoc = $request->fecha_proceso;
            $docxpagar->tcambio = $request->tcambio;
            $docxpagar->condicionpago_id = (empty($request->condicion_pago)) ? null : $request->condicion_pago;
            $docxpagar->recepcion = (empty($request->fecha_recepcion)) ? null : $request->fecha_recepcion;
            $docxpagar->vencimiento = (empty($request->vencimiento)) ? null : $request->vencimiento;
            $docxpagar->vendedor_id = (empty($request->vendedor)) ? null : $request->vendedor; // cliente
            $docxpagar->glosa = $request->glosa;
            $docxpagar->tiponotacredito_id = $tiponotacredito_id;
            $docxpagar->tiponotadebito_id = $tiponotadebito_id;
            $docxpagar->tipoafectaigv_id = (empty($request->tipo_afectacion_igv)) ? null : $request->tipo_afectacion_igv;
            $docxpagar->impuesto_id = $request->igv;
            $docxpagar->impuesto2_id = (empty($request->percepcion)) ? null : $request->percepcion;
            $docxpagar->base = $sumar['tbase'];
            $docxpagar->inafecto = $sumar['tinafecto'];
            $docxpagar->gratuito = $sumar['tgratuito'];
            $docxpagar->descuento = $sumar['tdescuento'];
            $docxpagar->impuesto = $sumar['igv'];
            $docxpagar->impuesto2 = ( empty($request->impuesto2) ) ? 0 : $request->impuesto2;
            $docxpagar->isc = session()->get('tisc'); // ver sumar()
            $docxpagar->icbper = session()->get('ticbper'); // ver sumar()
            $docxpagar->total = $sumar['ttotal'];
            $docxpagar->ventana = "MOV_FACTURAVENTA";
            $docxpagar->estado = "ACTIVO";
            $docxpagar->refgremision = $refgremision;
            $docxpagar->occliente = ( empty($request->occliente)) ? '' : $request->occliente;
            $docxpagar->noalmacen = $noalmacen; //  tabla: tipoventa, campo : conkardex
            $docxpagar->razonsocial = $razonsocial;  
            $docxpagar->ruc = (empty($request->doc_identidad)) ? '' : $request->doc_identidad;  // cliente // clasic, objeto: Cnclieprov1, método: llenar
            $docxpagar->direccion = (empty($tercero->direccion)) ? '' : $tercero->direccion; // cliente // clasic, objeto: Cnclieprov1, método: llenar
            $docxpagar->ubigeo_id = (empty($tercero->ubigeo_id)) ? 0 : $tercero->ubigeo_id; // cliente // clasic, objeto: Cnclieprov1, método: llenar
            $docxpagar->centraliza = 1; // clasic, formulario: MOV_FACTURAVENTA, método: llenar
            $docxpagar->cotizacion_id = (Session::get('cotizacion_id')) ? Session::get('cotizacion_id') : null;
            $docxpagar->ordentrabajo_id = (Session::get('ordentrabajo_id')) ? Session::get('ordentrabajo_id') : null;
            $docxpagar->usuario = Session::get('usuario');
            $docxpagar->observaciones = trim($request->edotros);
            
            $docxpagar->save();
            $this->save_details($docxpagar);    

            $id = $docxpagar->id;
            
            DB::commit();
            
            $this->grabar_carritos($docxpagar, $request);

            $this->grabardet($docxpagar);
            $this->llenarde($docxpagar, $request);
            $this->setestadoref($docxpagar);

                     
            //$GeneraFEController = new GeneraFEController($id, $this->lcpe, $docxpagar);
            //$respuesta_generafe = $GeneraFEController->generaFE();
            

            if ($this->lcontabiliza == 1){

                $lcRet = $this->provisionesPorPagarProcedure->generaCtacte($id, 'docxpagar', '');
                if($lcRet != null){
                    session()->put('respuesta_contabiliza', $lcRet);
                }

            }

            if ($this->lcentraliza != 0 ){

                $lcRet = $this->provisionesPorPagarProcedure->cntDocxCobrar($id, '');
                if($lcRet != null){
                    session()->put('respuesta_centraliza', $lcRet);
                }

            }

            //sigue el llenarde

            $r['ruta'] = route('edit.' . $this->var, $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        }catch(\Exception $e){
            DB::rollback();
            $r["error"] = "Error al Grabar";
            $r["bd"] = $e->getMessage();
        }

        $this->eliminar_carritos();

        return $r;
    }


    public function edit($id)
    {
        
        session()->put('id', $id);
        session()->forget('items_docxpagar_detalle', []);
        session()->forget('tisc', 0);
        session()->forget('ticbper', 0); 

        // NO LLAMO A LA FUNCIÓN : $this->eliminar_variables_de_sesion(); PORQUE NO NECESITO BORRAR TODO

        $this->eliminar_carritos();
        
        $data['docxpagar'] = DocumentToPay::selectidcmd_billing($id);
        $data['docxpagar_detalles'] = DocumentToPayDetail::selectidcmd($data['docxpagar']->id);
        $data['tercero_docxpgar'] = Customer::select2_id($data['docxpagar']->tercero_id);
        $data['notacreditodebito'] = $this->obtener_notacreditodebito($data['docxpagar']);
        $data['referencia'] = Reference::selectidcmd_cb($id);
        //dd($data['referencia']);
        $data_llenarde = $this->llenarDeBillingController->obtener_data_edit($data['docxpagar']);
        
        $data['ocompra'] = $data_llenarde['ocompra'];
        $data['serierem'] = $data_llenarde['serierem'];
        $data['numerorem'] = $data_llenarde['numerorem'];
        $data['referencia_salidaalmacen_id'] = $data_llenarde['referencia_salidaalmacen_id'];
        $data['ocompra_ventana'] = $data_llenarde['ventana'];

        $data['docxpagar_formapago'] = DocumentToPayPaymentWay::selectidcmd($id);
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['today'] =  Carbon::today()->format('Y-m-d');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['unegocios'] = BusinessUnit::where('estado',1)->get();
        $data['puntosventa'] = PointSale::where('estado',1)->get();
        $data['tiposventa'] = SaleType::where('estado',1)->get();
        $data['monedas'] = Currency::where('estado',1)->get();
        $data['condicionespago'] = PaymentCondition::where('estado',1)->get();
        $data['tiposafectaigv'] = AffectIgv::where("estado", 1)->get();
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        $data['documentoscom'] = Commercial::selectfrmcmd($this->ventana);
        $data['vendedores'] = Seller::where("estado", 1)->get();
        $data['tiposdetraccion'] = DetractionType::where("estado", 1)->get();
        $data['formaspago'] = PaymentMethod::where("estado", 1)->get();
        $data['mediospago'] = PaymentType::where("estado", 1)->get();
        $data['bancos'] = Bank::where("estado", 1)->get();

        $data["sunat_estado"] = '';
        $data["sunat_codigo"] = '';
        $data["sunat_descripcion"] = '';

        if(Session::get('respuesta_sunat')){

            $data["sunat_estado"] = Session::get('respuesta_sunat')['estado'];
            $data["sunat_codigo"] = Session::get('respuesta_sunat')['codigo'];
            $data["sunat_descripcion"] = Session::get('respuesta_sunat')['descripcion'];

        }
        
        $data["respuesta_contabiliza"] = '';

        if(Session::get('respuesta_contabiliza')){

            $data["respuesta_contabiliza"] = Session::get('respuesta_contabiliza');

        }


        $data["respuesta_centraliza"] = '';

        if(Session::get('respuesta_centraliza')){

            $data["respuesta_centraliza"] = Session::get('respuesta_centraliza');

        }

        $data_movctacte = $this->movctacteBillingController->obtener_data_edit($data['docxpagar'], $data['referencia']);
        $data['saldomn'] = $data_movctacte['saldomn'];
        $data['saldome'] = $data_movctacte['saldome'];
        $data['documento_aplicacion'] = $data_movctacte['documento_aplicacion'];
        $data['importe_aplicacion'] = $data_movctacte['importe_aplicacion'];


        $data['detraccion'] = DocumentoToPayDetraction::find($id);
        $data_detraccion = $this->detractionBillingController->obtener_refencia_detracion($data['docxpagar']);
        $data['referencia_detraccion'] = $data_detraccion['referencia_detraccion'];
        $data['referencia_detraccion_id'] = $data_detraccion['referencia_detraccion_id'];
        $data['factor_detraccion'] = '';

        if($data['detraccion']){
            $data['factor_detraccion'] = DetractionType::find($data['detraccion']->tipodetraccion_id)->valor;
        }
        

        $data_refanticipo = $this->obtener_ref_anticipo($id);
        $data['ref_anticipo'] = $data_refanticipo['ref_anticipo'];
        $data['ref_anticipo_id'] = $data_refanticipo['ref_anticipo_id'];

        
        $docxpagar_otrabajo = DocumentToPayWork::selectparentcmd($id);
        $data['docxpagar_otrabajo'] = ($docxpagar_otrabajo) ? $docxpagar_otrabajo : null;


        $data['view'] = link_view('Ventas', 'Transacción', 'Facturación', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['docxpagar']->estado, $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        
        $this->detailBillingController->llenar_docxpagar_detalle($data['docxpagar_detalles']);
        $this->llenar_carritos($data['docxpagar'], $data['referencia']);
        
        session()->forget('respuesta_sunat');
        session()->forget('respuesta_contabiliza');
        session()->forget('respuesta_centraliza');

        return view('billings.edit', $data);
    }

 
    public function update(Request $request, $id)
    {
        
        $rules = array(

            'unidad_negocio' => 'required',
            'condicion_pago' => 'required',
            'glosa' => 'required'

        );
        $message = array(
            
            'condicion_pago.required' => 'La condición de pago es requerida',
            'unidad_negocio.required' => 'La unidad de negocio es requerida',
            'glosa.required' => 'La glosa es requerida'

        );
        $this->validate($request, $rules, $message);


        try {

            $docxpagar = DocumentToPay::findOrFail($id);
            $docxpagar->unegocio_id = $request->unidad_negocio;
            $docxpagar->condicionpago_id = (empty($request->condicion_pago)) ? null : $request->condicion_pago;
            $docxpagar->recepcion = (empty($request->fecha_recepcion)) ? null : $request->fecha_recepcion;
            $docxpagar->vendedor_id = (empty($request->vendedor)) ? null : $request->vendedor; // cliente
            $docxpagar->glosa = $request->glosa;
            $docxpagar->occliente = ( empty($request->occliente)) ? '' : $request->occliente;
            $docxpagar->usuario = Session::get('usuario');
            $docxpagar->observaciones = ( empty($request->edotros) ) ? null : $request->edotros;

            $id = null;
            $id = DB::transaction(function () use ($docxpagar, $request) {

                $docxpagar->save();                
                return $docxpagar->id;

            });

            $this->grabar_carritos($docxpagar, $request);

            //$this->grabardet($docxpagar);
            //$this->llenarde($docxpagar, $request);
            //$this->setestadoref($docxpagar);

            if ($this->lcontabiliza == 1){

                $lcRet = $this->provisionesPorPagarProcedure->generaCtacte($id, 'docxpagar', '');
                if($lcRet != null){
                    return response()->json(['error' => $lcRet]);
                }

            }

            if ($this->lcentraliza != 0 ){

                $lcRet = $this->provisionesPorPagarProcedure->cntDocxCobrar($id, '');
                if($lcRet != null){
                    return response()->json(['error' => $lcRet]);
                }

            }            

            $r["success"] = "Se guardaron los cambios correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $this->eliminar_carritos();

        return $r;

    }


    public function mostrar_referencias(Request $request){ //mov_facturaventa - btncotizacion - evento click ok
        
        return DataTables::of($this->referenceBillingController->mostrar_referencias_cabecera($request))->make();

    }

    public function mostrar_referencias_detalle(Request $request){

        $this->referenceBillingController->agregar_quitar_detalle($request);

        return DataTables::of($this->referenceBillingController->obtener_referencia_detalle())->make();

    }

    public function tercero_direccion($tercero_id){ //ok
        $direcciones = CustomerAddress::busquedacmd($tercero_id);
        return $direcciones;
    }


    public function lista_detalles(Request $request){

        $detalles = $this->detailBillingController->obtener_docxpagar_detalle();

        return DataTables::of($detalles)->make();
    }

    public function buscar_producto(Request $request){

        if (session()->get('id') != 0){ return DataTables::of([])->make(); } // SOLO EN EL CREAR

        return $this->detailBillingController->buscar_producto($request);

    }

    private function eliminar_carritos(){

        $this->warehouseReferenceBilling->eliminar_carritos();
        $this->detailBillingController->eliminar_carritos();
        $this->paymentMethodBillingController->eliminar_carritos();
        $this->movctacteBillingController->eliminar_carritos();
        $this->documentApllyBillingController->eliminar_carritos();
        $this->referenceBillingController->eliminar_carritos();

        Cart::instance($this->cart_notacreditodebito_cabecera)->destroy();
        Cart::instance($this->cart_notacreditodebito_detalle)->destroy();
        Cart::instance($this->cart_referencia)->destroy();
    }

    public function existe_stock(Request $request){

        return $this->detailBillingController->existe_stock($request);

    }

    public function valida_serie(Request $request) // no valido el backend cuando NO pide serie, la serie deberia regresar en blanco (preguntar)
    {

        return $this->detailBillingController->valida_serie($request);

    }


    public function agregar_productos(Request $request){

        $data = json_decode($request->data);
        $productos_id = array_column(array_column($data, 'ids'),'value'); // id productos seleccionados
        $count = count($data);

        if ( $count > 0 ){ // existen productos seleccionados ?

            if ( $error = $this->detailBillingController->agregar_productos($request, $data, $productos_id) ){
                 return $error;
            }

            $sumar = $this->sumar($request); 
        }

        return ( isset($sumar) ) ? $sumar : null;

    }

    public function validar_cantidad(Request $request) // objeto grilla-cantidad, método: Valid
    {

       if ($cantidad = $this->detailBillingController->validar_cantidad($request)){

            return $cantidad;

       }else {

            return $this->sumar($request);

       }

    }

    public function limpiar_datatable(){
        return DataTables::of([])->make();
    }

    public function validar_precio(Request $request){

        if ($cantidad = $this->detailBillingController->validar_precio($request)){

            return $cantidad;

       }else {

            return $this->sumar($request);

       }


    }

    public function validar_descuento(Request $request){


        if ($cantidad = $this->detailBillingController->validar_descuento($request)){

            return $cantidad;

       }else {

            return $this->sumar($request);

       }        

    }


    public function eliminar_docxpagar_detalle(Request $request){
        
        if (session()->get('id') == 0){ // SOLO SE ELIMINAN LOS ITEMS EN EL CREAR

            $this->detailBillingController->eliminar_docxpagar_detalle($request);

        }

        return $this->sumar($request);

    }


    private function save_details($docxpagar){
        
        $this->detailBillingController->save_details($docxpagar);

    }


    private function setestadoref($docxpagar){ // clasic, formulario: mov_facturaventa, método: setestadoref
        
        $cart_referencia = collect(Cart::instance($this->cart_referencia)->content())->last();
        $setEstadoRefBillingController = new SetEstadoRefBillingController($docxpagar, $cart_referencia);
        $setEstadoRefBillingController->setestadoref();

    }


    public function mostrar_credito_debito(Request $request){

        $id = session()->get('id');

        $AuxiliaryApplyNCController = new AuxiliaryApplyNCController($request->tercero, 'C', new DocumentToPay());
        $documentos_cabecera = $AuxiliaryApplyNCController->buscar_notacreditodebito_cabecera($request->desde, $request->hasta, $request->serie, $request->numero, $id);

        Cart::instance($this->cart_notacreditodebito_cabecera)->destroy();

        $seleccionado = $this->llenar_notacreditodebito_cabecera($documentos_cabecera);

        if ($seleccionado) { // si ya existe un documento agregado(selecionado)
            $request->request->add(['documento_id' => $seleccionado->id]);
            $this->mostrar_credito_debito_detalle($request);
         }

    }

    private function llenar_notacreditodebito_cabecera($documentos_cabecera){

        $selccionado = array();

        foreach($documentos_cabecera as $documento){

            //id = cart referencia (crsreferencia) - referencia_id

            $selccionado = collect(Cart::instance($this->cart_referencia)->content())->firstWhere('id', $documento->id);
            $elegido = ($selccionado) ? 1 : 0;

            Cart::instance($this->cart_notacreditodebito_cabecera)->add(['id' => $documento->id, 'name' => $documento->documento, 'qty' => 1, 'price' => 0.00,
            'options' => [

                'fecha' => $documento->fecha,
                'nombre' => $documento->nombre,
                'moneda_id' => $documento->moneda_id,
                'tcambio' => $documento->tcambio,
                'ventana' => $documento->ventana,
                'glosa' => $documento->glosa,
                'elegido' => $elegido

            ]]);

        }

        return $selccionado;

    }

    public function listado_notacreditodebito_cabecera(){

        return DataTables::of(Cart::instance($this->cart_notacreditodebito_cabecera)->content())->make();

    }

    public function mostrar_credito_debito_detalle(Request $request){

        if ( !isset($request->documento_id) ) { return null; }

        $AuxiliaryApplyNCController = new AuxiliaryApplyNCController($request->tercero, 'C', new DocumentToPay());
        $documentos_detalle = $AuxiliaryApplyNCController->buscar_notacreditodebito_detalle($request->documento_id);

        Cart::instance($this->cart_notacreditodebito_detalle)->destroy();

        $this->llenar_notacreditodebito_detalle($documentos_detalle);

    }


    private function llenar_notacreditodebito_detalle($documentos_detalle){

        foreach($documentos_detalle as $documento){
            // name = crsreferencia - referencia_id
            Cart::instance($this->cart_notacreditodebito_detalle)->add(['id' => $documento['item'], 'name' => $documento['id'], 'qty' => 1, 'price' => 0.00,
            'options' => [
                //"id" => $documento['id'],
                //"item" => $documento['item'],
                "producto_id" => $documento['producto_id'],
                "umedida_id" => $documento['umedida_id'],
                "ume_cod" => $documento['ume_cod'],
                "cuenta_id" => $documento['cuenta_id'],
                "prd_cod" => $documento['prd_cod'],
                "prd_dsc" => $documento['prd_dsc'],
                "cantidad" => $documento['cantidad'],
                "esafecto" => $documento['esafecto'],
                "consumo" => $documento['consumo'],
                "cuenta" => $documento['cuenta'],
                "centrocosto_id" => $documento['centrocosto_id'],
                "cco_cod" => $documento['cco_cod'],
                "cco_dsc" => $documento['cco_dsc'],
                "actividad_id" => $documento['actividad_id'],
                "act_cod" => $documento['act_cod'],
                "act_dsc" => $documento['act_dsc'],
                "tipoprecio" => $documento['tipoprecio'],
                "precio" => $documento['precio'],
                "preciolista" => $documento['preciolista'],
                "importe" => $documento['importe'],
                "lote" => $documento['lote'],
                "vencelote" => $documento['vencelote'],
                "serie" => $documento['serie'],
                "atendido" => $documento['atendido'],
                "aplicar" => $documento['aplicar'],
                "stock" => $documento['stock'],
                "referencia_id" => $documento['referencia_id'], // no tiene que ver con el cart referencia(clasic, crsreferencia)
                "docrefer" => $documento['docrefer'],
                "tipoafectaigv_id" => $documento['tipoafectaigv_id'],
                "afeigv_cod" => $documento['afeigv_cod'],
                "proyecto_id" => $documento['proyecto_id'],
                "pry_cod" => $documento['pry_cod'],
                "pry_dsc" => $documento['pry_dsc'],
                'elegido' => 1 // agregado para la selección

            ]]);

        }

    }

    public function listado_notacreditodebito_detalle(){

        return DataTables::of(Cart::instance($this->cart_notacreditodebito_detalle)->content())->make();

    }

    public function agregar_creditodebito(Request $request){ // clasic, objeto: btnaplica, método: click
        
        $this->documentApllyBillingController->eliminar_carritos();
        $this->detailBillingController->cart_productos_destroy();
        $this->detailBillingController->cart_docxpagar_detalle_destroy();
        
        Cart::instance($this->cart_referencia)->destroy();

        $data = json_decode($request->data);
        $detalle_id = array_column(array_column($data, 'ids'),'value'); // id productos seleccionados
        $count = count($data);

        if ( $count > 0 ){ // existen productos seleccionados ?

            $this->validar_cantidad_creditodebito_detalle($data); // validar cantidad y unir la data con el carrito de nota crédito débito detalle
            // id =  item
            $detalles = collect(Cart::instance($this->cart_notacreditodebito_detalle)->content())->whereIn('id', $detalle_id); // credido o debito detalle seleccionados
            // id = documento_id
            $cabecera = collect(Cart::instance($this->cart_notacreditodebito_cabecera)->content())->firstWhere('id', collect($detalles)->first()->name);

            $documentocom = Commercial::where("codigo", trim(substr($cabecera->name, 0, strpos($cabecera->name, ' '))))->first();

            $impuesto = Taxes::select('valor')->where('id', $request->igv)->first();

            $impuesto_valor = $impuesto->valor / 100;

            
            // validar documento comercial
            if ( !in_array(trim($documentocom->codsunat), array('01', '03', '08')) ) {

                return response()->json(['errors' => array('detalle' => 'Documento no permitido')],422);

            }

            // validar tipo moneda
            if ( $cabecera->options->moneda_id != $request->moneda ){

                return response()->json(['errors' => array('detalle' => 'Error el documento por aplicar es de moneda diferente')],422);

            }

            // docxpagar de nota crédito débito
            $docxpagar_creditodebito = DocumentToPay::selectidcmd_billing($cabecera->id); // id del documento que viene de nota crédito o débito

            $item = $this->detailBillingController->obtener_item();

            $importe = $this->llenar_docxpagar_detalle_notacreditodebito($detalles, $item, $impuesto_valor);

            // glosa
            $glosa = '';
            $chkaplicar = null;
            $documentocom_del_formulario = Commercial::select('codsunat')->where('id', $request->tipo_doc)->first();

            if ( trim($documentocom_del_formulario->codsunat) == '08' || trim($documentocom_del_formulario->codsunat) == '98' ) :
                $glosa = substr('N.DEBITO ' . trim($docxpagar_creditodebito->glosa) . ' ' .  $cabecera->name, 0, 200);
            endif;

            if ( trim($documentocom_del_formulario->codsunat) == '07' || trim($documentocom_del_formulario->codsunat) == '97' ) :
                $glosa = substr('N.CREDITO '  . trim($docxpagar_creditodebito->glosa) . ' ' . $cabecera->name, 0, 200);
                $chkaplicar = 1;
            endif;


            $this->llenar_referencia_creditodebito($cabecera->id, $cabecera->name);

            // Tipo de cambio nota débito, cuando referencia no tiene detracción
            $tcambio = $cabecera->tcambio; // en principio el tcambio seria el que viene del documento de crédito o débito si no se cumple la siguiente condición:
            if ( (trim($documentocom_del_formulario->codsunat) == '07' || trim($documentocom_del_formulario->codsunat) == '97') && $cabecera->id != 0) {

                $detraccion = DocumentoToPayDetraction::selectparentcmd($cabecera->id);
                if ($detraccion) {
                    // si la referencia no tienen detracción
                    if ( empty($detraccion->totaldetraccion) ) { $tcambio = $request->tcambio; }

                }

            }

            //totales
            $sumar = $this->sumar($request);

            // validar serie
            $serie = null;
            if ( ctype_alpha(substr($request->serie_doc, 0, 1)) ){

                if ( substr($request->serie_doc, 0, 1) != 'E'){

                    if ( in_array(trim(substr($cabecera->name, 0, 2)), array('01', 'FT')) && substr($request->serie_doc, 0, 1) != 'F' ){

                        $serie = "F" . substr($request->serie_doc, 1, 3);

                    }

                    if ( in_array(trim(substr($cabecera->name, 0, 2)), array('03', 'BV')) && substr($request->serie_doc, 0, 1) != 'B'){

                        $serie = 'B' . substr($request->serie_doc, 1, 3);

                    }

                }

            }
            
            $respuesta['importe_creditodebito'] = $importe;
            $respuesta['tipoafectaigv_id'] = (empty($docxpagar_creditodebito->tipoafectaigv_id)) ? null : $docxpagar_creditodebito->tipoafectaigv_id;
            $respuesta['impuesto_id'] = $docxpagar_creditodebito->impuesto_id;
            $respuesta['impuesto2_id'] = $docxpagar_creditodebito->impuesto2_id;
            $respuesta['vendedor_id'] = $docxpagar_creditodebito->vendedor_id;
            $respuesta['glosa'] = $glosa;
            $respuesta['documento'] = $cabecera->name;
            $respuesta['tcambio'] = $tcambio;
            $respuesta = array_merge($respuesta, $sumar);
            $respuesta['chkaplicar'] = $chkaplicar;
            $respuesta['serie'] = $serie;

            return $respuesta;

        }

    }

    private function llenar_docxpagar_detalle_notacreditodebito($detalles, $item, $impuesto_valor){

        //data select
        $centro_costo = Costs::select('*')->where("estado", 1)->get();
        $tipo_afecta = AffectIgv::select('*')->where("estado", 1)->get();

        $importe_total = 0;

        foreach($detalles as $detalle){

            // Si el documento es de tipo de venta anticipo y precio de venta incluye impuestos

            $importe = $detalle->options->aplicar * $detalle->options->precio;
            $preciolista = $detalle->options->preciolista;
            $precio = $detalle->options->precio;

            if ( $detalle->options->esanticipo == 1 && $this->lconimptos == 1 && $detalle->options->esafecto = 1) {

                $importe = $importe / ( 1 + $impuesto_valor);
                $preciolista = $importe / $detalle->options->cantidad;
                $precio = $preciolista;

            }

            $parancelaria = Parancelaria::selectparentcmd($detalle->options->producto_id);
            $isc = 0;

            if($parancelaria){

                $isc = ( $parancelaria->tipo == 'N' ) ? $detalle->options->cantidad * $parancelaria->isc : $detalle->options->importe * ($parancelaria->isc / 100);

            }

            $this->detailBillingController->llenar_docxpagar_detalle_notacreditodebito($item, $detalle, $importe, $preciolista, $precio, $isc, $centro_costo, $tipo_afecta);
            $item++;

            $importe_total =  $this->calcular_importe_notacreditodebito($importe_total, $detalle->options->referencia_id, $importe, $impuesto_valor);
        }
        
        return $importe_total;

    }

    private function validar_cantidad_creditodebito_detalle($data){

        foreach($data as $d){

            $cart = collect(Cart::instance($this->cart_notacreditodebito_detalle)->content())->firstWhere('id', $d->ids->value);

            $aplicar = $d->aplicar->value;  // input modal nota crédito débito

            if ( $cart->options->cantidad < $d->aplicar->value){

                $aplicar = $cart->options->cantidad;

            }

            $item = Cart::instance($this->cart_notacreditodebito_detalle)->get($cart->rowId);

            $options = $item->options->merge([
                'aplicar' => $aplicar,
            ]);

            Cart::instance($this->cart_notacreditodebito_detalle)->update($cart->rowId, [
                'options' => $options]);

        }

    }

    private function calcular_importe_notacreditodebito($importe_total, $referencia_id, $importe, $impuesto_valor){

        if ( $this->lconimptos == 1 ){

            $importe_total +=  ($referencia_id == 0) ? $importe : ($importe * ( 1 + $impuesto_valor));

        }else{

            $importe_total += ($importe * ( 1 + $impuesto_valor));

        }

        return $importe_total;

    }

    private function llenar_referencia_creditodebito($referencia_id, $documento){
        // id = documento->id de nota crédito débito
        Cart::instance($this->cart_referencia)->add(['id' => $referencia_id, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
        'options' => [
            'parent_id' => session()->get('id'), // SE ENVIA EN EL SAVE
            'referencia_id' => $referencia_id,
            'docrefer' => $documento,
            'tipodoc' => substr($documento, 0, 2),
            'seriedoc' => substr($documento, 3, 5),
            'numerodoc' => substr(trim($documento), -8),
            'importe' => 0 // se calcula en la vista
        ]]);

    }

    public function creditodebito_seleccionado(){

        $data = array();

        $referencia = collect(Cart::instance($this->cart_referencia)->content())->last();

        if ($referencia){

            $data['serie'] = str_replace("-", "", $referencia->options->seriedoc);
            $data['numero'] = $referencia->options->numerodoc;

        }

        return $data;

    }

    private function save_referencia($id, $importe, $aplicar){

        if(Session::get('id') != 0) { return null; } // NO SE REALIZA NINGUNA ACCIÓN EN EL EDITAR

        $cart_referencia = collect(Cart::instance($this->cart_referencia)->content())->last();

        if ($cart_referencia){
            $referencia = new Reference;
            $referencia->parent_id = $id;
            $referencia->referencia_id = $cart_referencia->id;
            $referencia->aplicar = $aplicar;
            $referencia->importe = $importe;
            $referencia->usuario = Session::get('usuario');
            $referencia->save();
        }

    }


    public function codigo_sunat_documentocom(Request $request){
        
        if (session()->get('id') != 0){ return null; }// SOLO EN EL CREAR

        $documentocom = Commercial::select('codsunat')->where('id', $request->tipo_doc)->first();

        //ELIMINAR REFERENCIAS DE SALIDAS DE ALMACEN
        $this->warehouseReferenceBilling->eliminar_carritos();

        return ($documentocom) ? $documentocom->codsunat : null;
    }

    public function validar_serierem(Request $request){
        
        if (session()->get('id') != 0){ return null; } // SOLO EN EL CREAR

        if ( empty($request->serierem) ){return null;}

        $guias_remision = new ReferenceGuideBillingController($this->lcpe, session()->get('id'), $request->serierem, $request->nrorem);
        $guias_remision->evaluar_serierem();

        return $guias_remision->obtener_serierem_numerorem();

    }

    public function validar_nrorem(Request $request){

        if (session()->get('id') != 0){ return null; } // SOLO EN EL CREAR

        if ( empty($request->nrorem) ){return null;}

        $guias_remision = new ReferenceGuideBillingController($this->lcpe, session()->get('id'), $request->serierem, $request->nrorem);
        return $guias_remision->obtener_numerorem();


    }

    private function llenarde(Model $docxpagar, $request){

        $this->llenarDeBillingController->llenarde_guardar($docxpagar, $request);

    }

    public function ingresoalmacen_referencia_cabecera(Request $request){
        
        $this->warehouseReferenceBilling->ejecutar_gettransferenciaxatender($request);
        $this->warehouseReferenceBilling->llenar_cabecera();

        return DataTables::of($this->warehouseReferenceBilling->obtener_cabecera())->make();
        
    }
    

    public function ingresoalmacen_referencia_detalle(Request $request){

        $this->warehouseReferenceBilling->llenar_quitar_detalle($request);    
        return DataTables::of($this->warehouseReferenceBilling->obtener_detalle())->make();

    }

    public function agregar_salidaalmacen_referencia(Request $request){

        $data = json_decode($request->data);
        $count = count($data);
        
        $respuesta = array();
        
        if ( $count > 0 ){ // existen items seleccionados ?
            
            $respuesta = $this->warehouseReferenceBilling->agregar_salidaalmacen_referencia($request, $this->detailBillingController, $this->referenceBillingController, $data);

        }
        

        $respuesta = array_merge($respuesta, $this->sumar($request));

        return $respuesta;

    }

    private function grabardet($docxpagar){

        $grabardet = new GrabarDetBillingController($docxpagar);
        $grabardet->grabardet($this->warehouseReferenceBilling);

    }

    private function grabar_carritos($docxpagar, $request){

        $aplicar = (empty($request->aplicar_credito_debito)) ? 0 : 1; // tabla: referencia, campo: aplicar
        $this->save_referencia($docxpagar->id, $request->importe, $aplicar);
        $this->documentApllyBillingController->grabar_carritos($docxpagar, $request);


        $this->warehouseReferenceBilling->grabar_carritos($docxpagar);
        $this->paymentMethodBillingController->grabar_carritos($docxpagar, $request);


        // NO ES UN CARRITO
        $this->detractionBillingController->grabar_carritos($docxpagar, $request);


        $this->referenceBillingController->grabar_carritos($docxpagar);

    }

    private function llenar_carritos($docxpagar, $referencia){

        // NO SE ACTUALIZA LA NOTA DE CRÉDITO DÉBITO EN EL EDITAR(NO LLENAR CARRITO)
        $this->warehouseReferenceBilling->llenar_carritos($docxpagar);
        $this->paymentMethodBillingController->llenar_carritos($docxpagar);
        $this->referenceBillingController->llenar_carritos($docxpagar);

    }

    public function listado_tmpguias(){

       return DataTables::of($this->warehouseReferenceBilling->obtener_tmpguias())->make();
       
    }

    public function listado_cronologia_cpe(){

        return DataTables::of($this->cpeBillingController->listado_cpe())->make();

    }

    public function listado_hitorial_aplicaciones(){

        return DataTables::of($this->movctacteBillingController->obtener_movctacte())->make();

    }

    public function listado_documentos_aplicar(Request $request){

        $this->documentApllyBillingController->listado_documentos_aplicar($request, Cart::instance($this->cart_referencia)->content());
        return $this->documentApllyBillingController->obtener_total_aplicar($request);      

    }
    
    public function obtener_listado_documentos_aplicar(){

        return DataTables::of($this->documentApllyBillingController->obtener_documentos_aplicar())->make();

    }

    public function eliminar(Request $request){

        // FALTA ELIMINAR LAS TABLAS HIJAS (EN TODOS LO MODULOS)

        $this->docxpagar = DocumentToPay::find($request->id);

        if($respuesta = $this->eliminar_documentos_electronicos($this->docxpagar)){return $respuesta;}


        if($respuesta = $this->eliminar_documentos_aplicados($this->docxpagar)){return $respuesta;}


        if($respuesta = $this->t_eliminar_registro($request)){
            if(isset($respuesta['success']) == false){ // SI HAY UN ERROR MOSTRARLO
                return $respuesta;
            }
                    
        }

        if($respuesta = $this->eliminar_actualizar_referencias($this->docxpagar)){return $respuesta;}

        $r["success"] = "Registro eliminado Correctamente";

        return $r;

    }

    public function contabilizar_centralizar_setestadoref($id){ // SE EJECUTA EN EL TRAIT DE MOVEMENT

        if ($this->lcontabiliza == 1){

            $lcRet = $this->provisionesPorPagarProcedure->generaCtacte($id, 'docxpagar', '');

            if($lcRet != null){
                return response()->json(['errors' => array('detalle' => $lcRet )],422);
            }

        }

        $this->setestadoref($this->docxpagar);

    }

    public function comprobar_estado(Request $request){

        $docxpagar = DocumentToPay::find($request->id);

        if($respuesta = $this->anular_documentos_electronicos($docxpagar)){return $respuesta;}

        if($respuesta = $this->anular_documentos_aplicados($docxpagar)){return $respuesta;}

        return $this->t_comprobar_estado($request);

    }

    public function anular(Request $request){

        $this->docxpagar = DocumentToPay::find($request->id);

        if($respuesta = $this->t_anular_registro($request)){

            if(isset($respuesta['success']) == false){ // SI HAY UN ERROR MOSTRARLO
                return $respuesta;
            }

        }

        $this->setanulacpe($this->docxpagar);

        $r["success"] = "Estado actualizado Correctamente";

        return $r;

    }

    public function centralizar($id){ // SE EJECUTA EN EL TRAIT DE MOVEMENT

        if ($this->lcentraliza != 0 ){

            $lcRet = $this->provisionesPorPagarProcedure->cntDocxCobrar($id, '');
            if($lcRet != null){
                return response()->json(['errors' => array('detalle' => $lcRet )],422);
            }

        }

    }

}   
