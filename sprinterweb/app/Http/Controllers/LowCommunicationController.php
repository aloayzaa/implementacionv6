<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\Controls\UserPrivileges;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Test;
use App\Http\Traits\Controls\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Currency\Entities\Currency;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\Period\Entities\Period;
use App\Documents\Entities\Commercial;
use App\Reference\Entities\Reference;
use App\DocComunicacion\DocComunicacion;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Panel\Companies\Entities\Pempresa;
use App\AccountCte\Entities\MovementCte;
use App\DocumentToPay\Entities\DocumentoToPayDetraction;
use App\DocumentToPay\Entities\DocumentToPayCPE;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Customers\Entities\Customer;
use App\DocComunicacion\DocComunicacionCPE;
use App\DocComunicacion\DocComunicacionDetalle;
use App\Http\Controllers\CpeGetStatusController;
use App\Http\Controllers\LowCommunicationProcessesController;

class LowCommunicationController extends Controller
{

    use CartTrait, Tables, UserPrivileges, Test;
    private $var = 'lowcommunication';
    private $tabla_cabecera = 'puntoventa';
    private $referencia = 'referencia';
    private $llenardet = 'llenardet';
    private $tag = 'C';
    private $cparametro = '';
    private $nretencion = 0;
    private $provisionToPayProcedure;
    private $tipo;
    private $ventana;

    protected $user;
    protected $privilegios = '03.03.02';

    private $ticket;

    public function __construct(ProvisionesPorPagarProcedure $provisionesPorPagarProcedure)
    {
        $this->obtener_cliente();
        $this->user = Session::get('usuario');
        $this->tipo = 'RA';
        $this->ventana = 'MOV_DOCCOMUNICACION_BAJA';
        $this->provisionToPayProcedure = $provisionesPorPagarProcedure;
        $this->ticket = '';
    }

    public function list(Request $request)
    {
        $lcRet = $this->listadocmd(Session::get('period'), '', strtolower('MOV_DOCCOMUNICACION_BAJA'));
        return Datatables::of($lcRet)->make();
    }

    public function listDeatilLowCommunication(Request $request)
    {
        return DataTables::of(collect(Cart::instance($this->llenardet)->content()))->make();
    }

    public function references(Request $request)
    {
        return DataTables::of(collect(Cart::instance($this->referencia)->content()))->make();
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Ventas', 'Procesos', 'Comunicación Baja', '');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        
        return view('lowcommunication.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session()->put('items', []);
        Cart::instance($this->referencia)->destroy();
        Cart::instance($this->llenardet)->destroy();
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $data['monedas'] = Currency::where('estado', 1)->where('id', 1)->first();
        $data['view'] = link_view('Ventas', 'Procesos', 'Comunicación Baja', '');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['monedas'] = Currency::where('estado', 1)->where('id', 1)->first();
        $fecha = new Carbon(date('Y-m-d'));
        $fecha = $fecha->format('Y-m-d');
        $data['fecha'] = $fecha;

        return view('lowcommunication.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            if (Cart::instance($this->llenardet)->count() == 0) {
                return response()->json(['errors' => 'Ingrese al menos un documento al detalle']);
            }else{
                $doccomunicacion = new DocComunicacion();
                $doccomunicacion->periodo_id = Session::get('period_id');
                $doccomunicacion->tipo = $this->tipo;
                $doccomunicacion->tiporesumen = '';
                $doccomunicacion->numero = $request->numberseries;
                $doccomunicacion->fechaproceso = $request->txt_fecha;
                $doccomunicacion->moneda_id = $request->txt_descripcionmon;
                $doccomunicacion->tcambio = $request->tcambio;
                $doccomunicacion->glosa = $request->txtglosa;
                $doccomunicacion->ventana = $this->ventana;
                $doccomunicacion->estado = 'ACTIVO';
                $doccomunicacion->usuario = $this->user;
                $doccomunicacion->fecha_firma = null;
                $doccomunicacion->fecha_envio = null;
                $doccomunicacion->fecha_recep = null;
                $doccomunicacion->ticket = '';
                $doccomunicacion->respuesta_cod = '';
                $doccomunicacion->respuesta_dsc = '';
                $doccomunicacion->respuesta_hash = '';

                $id = null;
                $id = DB::transaction(function () use ($doccomunicacion) {
                    $doccomunicacion->save();
                    $this->doccomunicacion_detalle($doccomunicacion->id);
                    return $doccomunicacion->id;
                });

                $r["ruta"] = route('edit.lowcommunication', $id);
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

    public function doccomunicacion_detalle($id){
        try{
            $items = Session::get('items');
            if ($items){
                foreach ($items as $item) {
                    DocComunicacionDetalle::where(['parent_id' => $id, 'item' => $item])->delete();
                    session()->put('items', []);
                }
            }
            foreach (Cart::instance($this->llenardet)->content() as $cart) {
                $doccomunicacion_detalle = new DocComunicacionDetalle;
                $doccomunicacion_detalle->parent_id = $id;
                $doccomunicacion_detalle->item = $cart->options->item;
                $doccomunicacion_detalle->tercero_id = $cart->options->tercero_id;
                $doccomunicacion_detalle->referencia_id = $cart->options->documento_id;
                $doccomunicacion_detalle->importe = $cart->options->total;
                $doccomunicacion_detalle->save();
            }
        }catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        session()->put('items', []);
        Cart::instance($this->referencia)->destroy();
        Cart::instance($this->llenardet)->destroy();

        $baja = DocComunicacion::findOrFail($id);
        $data['baja'] = $baja;
        $data['var'] = $this->var;
        $data['proceso'] = 'edita';
        $data['monedas'] = Currency::where('estado', 1)->where('id', 1)->first();
        $data['period'] = Period::where('id', $baja->periodo_id)->first();
        $data['view'] = link_view('Ventas', 'Procesos', 'Comunicación Baja', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $baja->estado, $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['monedas'] = Currency::where('estado', 1)->where('id', 1)->first();
        $fecha = new Carbon($baja->fechaproceso);
        $data['fecha'] = $fecha->format('Y-m-d');
        $data['numero'] = str_pad($baja->numero,5, 0, STR_PAD_LEFT);

        // TODO SOBRE EL CPE
        $doccomunicacion_cpe = DocComunicacionCPE::where('parent_id', $id)->first();
        $fecha_envio = null;
        $fecha_recep = null;
        if(isset($doccomunicacion_cpe->fecha_envio) && $doccomunicacion_cpe->fecha_envio != null && $doccomunicacion_cpe->fecha_recep != null){
            $fecha_envio = new Carbon($doccomunicacion_cpe->fecha_envio);
            $fecha_envio = $fecha_envio->format('Y-m-d');
            $fecha_recep = new Carbon($doccomunicacion_cpe->fecha_recep);
            $fecha_recep = $fecha_recep->format('Y-m-d');
        }

        $data['fecha_envio'] = $fecha_envio;
        
        $data['fecha_recep'] = $fecha_recep;
        
        $data['ticket'] = (isset($doccomunicacion_cpe->ticket)) ? $doccomunicacion_cpe->ticket : '';
        
        $data['cod_cpe'] = (isset($doccomunicacion_cpe->respuesta_cod)) ? $doccomunicacion_cpe->respuesta_cod : '';
        
        $data['dsc_cpe'] = (isset($doccomunicacion_cpe->respuesta_dsc)) ? $doccomunicacion_cpe->respuesta_dsc : '';
        
        $data['hash_cpe'] = (isset($doccomunicacion_cpe->respuesta_hash)) ? $doccomunicacion_cpe->respuesta_hash : '';


        $this->llenar_carrito_detalle($id);

        return view('lowcommunication.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function listadocmd($nPeriodo, $cTipo, $cVentana){
        return DocComunicacion::listadocmd($cVentana);
    }

    public function txtdocreferencia1_valid($documento, $request){
        $id = $documento->options->referencia_id;
        $fecha = '';
        $total = 0;
        $moneda = '';
        $tipodoc = '';
        $seriedoc = '';
        $tipodocref = '';
        $lErr = false;

        $odata = DocumentToPay::selectidcmd_billing($id);
        if($odata <> null){
            $fecha = $odata->fechadoc;
            $tipodoc = trim($odata->codsunat);
            $seriedoc = trim($odata->seriedoc);
            $total = $odata->total * $odata->factor;
            $esgratuito = intval($odata->esgratuito);
            if($esgratuito == 1){
                $total = 0;
            }
            $moneda = $odata->moneda;
        }
        
        $odata = Reference::selectidcmd_cb($id);

        if($odata <> null){
            $tipodocref = trim($odata->tipodoc);
        }

        if($tipodoc == '03' || $tipodocref == '03'){
            return response()->json(['errors' => array('No es un documento válido para dar de baja. Las boletas y notas referidas a boletas se anulan directamente')],422);
        }else{
            $this->actualizar_carrito($documento, 'null', 'null', floatval($total), $moneda); //actualizamos el carrito de referencia

            $lnSaldo = MovementCte::getsaldo($id)->saldo;

            if($lnSaldo < $total){
                $odata = DocumentoToPayDetraction::select('totaldetraccion')->where('parent_id', $id)->first();
                if(isset($odata)){
                    $total = $total - $odata->totaldetraccion;
                    if($lnSaldo < $total){
                        return response()->json(['errors' => array('El documento tiene aplicaciones.')],0);return response()->json(['errors' => array('El documento tiene aplicaciones.')],0);
                    }
                }else{
                    return response()->json(['errors' => array('El documento tiene aplicaciones.')],0);
                }
            }

            $odata = DocumentToPayCPE::selectparentcmd($id);
            if($odata == null){
                return response()->json(['errors' => array('El documento no ha sido informado a Sunat')],0);
            }

            $fecha_valor = date("Y-m-d", strtotime($fecha."+ 3 days"));
            if($fecha_valor < $request->txt_fecha){
                return response()->json(['errors' => array('El documento ha sido emitido hace más de 72 horas')],0);
            }
        }
    }

    function left($str, $length)
    {
        return substr($str, 0, $length);
    }

    public function btncdr_click(Request $request){
        $lnCPE = $lnId = $lnI = 0;
        $lcVentana = $lcErr = $odata = $odataCPE = $oDaraVIN = $llRecibe = $lcFile = '';
        $lnCPE   = Pempresa::get_parametro('CPE_ACTIVAR')->valor; //

        if($lnCPE == 0){
            return '';
        }
        
        //seleccionamos los valores de doccomunicacion
        $lnId = ($request->id == '') ? 0 : $request->id;
        $lcVentana = trim(strtolower($this->ventana));
        $lcErr = 'Error en la generación del CPE';
        $lcRpta = '';
        $respuesta_cod = '';
        $respuesta_dsc = '';
        $doccomunicacion_ = DocComunicacion::findOrFail($lnId);
        
        $doccomunicacion_cpe = $this->selectparentcmd($lnId);

        if(isset($doccomunicacion_cpe->respuesta_cod)){
            $respuesta_cod = $doccomunicacion_cpe->respuesta_cod;
            $respuesta_dsc = $doccomunicacion_cpe->respuesta_dsc;
            $lcRpta = $doccomunicacion_cpe->respuesta_cod;
        }
        switch($lcRpta){
            case '0': 
                $llRecibe = false;
                break;
            case '0098': 
                $this->ticket = trim($doccomunicacion_cpe->ticket);
                $llRecibe = true;
                break;
            case '200':
            case '133':
            case '':
            case '2105':
                $llGenera = new GeneraFEController($lnId, $lnCPE, $doccomunicacion_);
                $llGenera = $llGenera->generaFE();
                if($llGenera['estado'] == 'ok'){
                    $r["ruta"] = route('edit.lowcommunication', $lnId);
                    $r["estado"] = $llGenera['respuesta']['estado'];
                    $r["codigo"] = $llGenera['respuesta']['codigo'];
                    $r["descripcion"] = $llGenera['respuesta']['descripcion'];
                }else{
                    $r["estado"] = $llGenera['estado'];
                    $r["descripcion"] = $llGenera['mensaje'];
                }
                

                return $r;
        }
        if($llRecibe){
            $proceso = new LowCommunicationProcessesController($lnId, $llRecibe, $this->ticket, $respuesta_cod, $respuesta_dsc);
            $proceso = $proceso->processes();
            
            if($llGenera['estado'] == 'ok'){
                $r["ruta"] = route('edit.lowcommunication', $lnId);
                $r["estado"] = $llGenera['respuesta']['estado'];
                $r["codigo"] = $llGenera['respuesta']['codigo'];
                $r["descripcion"] = $llGenera['respuesta']['descripcion'];
            }else{
                $r["estado"] = $llGenera['estado'];
                $r["descripcion"] = $llGenera['mensaje'];
            }

        }else{
            $r["codigo"] = $respuesta_cod;
            $r["descripcion"] = $respuesta_dsc;
        }

        $r["ruta"] = route('edit.lowcommunication', $lnId);
        return $r;
    }

    public function btnrefresh_click(Request $request){
        Cart::instance($this->referencia)->destroy();
        $lntercero = $request->tercero_id_refe;
        $lcorigen = $request->corigen; //thisform.corigen
        $ldfecha = $request->txthasta;
        $lnorigen = $request->txtid; //thisform.txtid.Value
        $lnoperacion = $request->txtoperacion; //thisform.txtoperacion.Value 
        $lntcambio = $request->tcambio; 

        $saldomn = 0;
        $saldome = 0;
        $i = 0;
        $lntotal = formatear_numero(0,2);
        $ol = $this->provisionToPayProcedure->getCtaCte($lntercero, $lcorigen, $ldfecha, $lnorigen, $lnoperacion);
        if($ol){
            foreach($ol as $ol){//
                $i++;
                $lnid = $ol->id;
                $lnelegido = 0;
                $lnaplicar = 0;

                $referencia_id = $lnid;
                
                if($request->importe <> ''){
                    $lnelegido = 1;
                    $lnaplicar = ($request->importe == '') ? 0 : $request->importe; //$ol->importa;
                }
    
                $elegido = $lnelegido;
                $aplicar = $lnaplicar;
    
                if($this->cparametro == 'mov_retencion'){
                    $saldo = $ol->totalmn * $this->nretencion;
    
                    if($saldomn < $ol->saldomn){
                        $saldomn = $saldomn;
                        $saldome = $saldomn / $lntcambio;
                    }
                }else{
                    if($ol->tipomoneda == 'N'){
                        $saldome = $ol->saldomn / $lntcambio;
                        $saldomn = $ol->saldomn;
                    }else{
                        $saldomn = $ol->saldome * $lntcambio;
                        $saldome = $ol->saldome;
                    }
                }
    
                if($lntercero == 0){
                    $razonsocial = $ol->razonsocial;
                }else{
                    $razonsocial = $ol->glosa;
                }

                $this->add_references($elegido, $ol, $saldomn, $saldome, $aplicar, $lntercero);
            }

            foreach (Cart::instance($this->referencia)->content() as $cart) {
                if($cart->options->origen == 'C'){
                    $lntotal += $aplicar;
                }
            }
            if(count($cart->options) > 0){
                $lcorigen = ($lcorigen == 'P') ? 'A' : 'C';
            }

            $txttotal = formatear_numero($lntotal,2);
            
            return array('respuesta' => 'ok', 'txttotal' => $txttotal);
        }
    }

    public function add_references($elegido, $ol, $saldomn, $saldome, $aplicar, $lntercero){

        $i = $i = Cart::instance($this->referencia)->count();
        Cart::instance($this->referencia)->add(['id' => $i +1 , 'name' => 'hola', 'qty' => 1, 'price' => 0.00,
        'options' => [
                'item' => $i +1,
                'elegido' => $elegido,
                'id' => $ol->id,
                'referencia_id' => $ol->id,
                'documento' => $ol->documento,
                'tipodoc' => $ol->tipodoc,
                'seriedoc' => $ol->seriedoc,
                'numerodoc' => $ol->numerodoc,
                'cuenta_id' => $ol->cuenta_id,
                'fecha' => $ol->fecha,
                'tcambio' => $ol->tcambio,
                'vencimiento' => $ol->vencimiento,
                'moneda' => $ol->moneda,
                'tipomoneda' => $ol->tipomoneda,
                'ventana' => $ol->ventana,
                'saldomn' => formatear_numero($saldomn,2), 
                'saldome' => formatear_numero($saldome,2),
                'aplicar' => formatear_numero($aplicar,2),
                'origen' => $ol->origen,
                'factor' => $ol->factor,
                'estado' => $ol->estado,
                'glosa' => $ol->glosa,
                'fechaproceso' => $ol->fechaproceso,
                'codigo' => $ol->codigo,
                'razonsocial' => $ol->razonsocial,
                'totalmn' => $ol->totalmn,
                'tercero_id' => $ol->tercero_id,
                'conretencion' => $ol->conretencion,
                'condetraccion' => $ol->condetraccion,
                'lntercero' => $lntercero,
            ]
        ]);
    }

    public function txtdocreferencia_keypress($request){
        
        //$moneda = Currency::where('id', $request->txt_descripcionmon)->first();
        $lnorigen = 0;
        $ldfecha = $request->txt_fecha;
        $lnmoneda = $request->moneda_id;
        $lntcambio = $request->tcambio;
        $lcglosa = trim($request->glosa);
        $lcorigen = $this->tag.substr("MOV_DOCCOMUNICACION_BAJA",4, 50);
        $lntercero = $request->tercero_id;
        $lnitem = 0;

        if($lntcambio == 0){
            return response()->json(['errors' => array('El dato es indispensable o el dato ingresado no es válido. Rectifique! T. Cambio')],0); 
        }

        $lnoperacion = 0;

        if(!empty($request->tipooperacion)){
            $lnoperacion = ($request->tipooperacion == '') ? 0 : $request->tipooperacion;
        }

        $lctipomoneda = $request->tipo;

        if($lntercero == 0){
            return response()->json(['errors' => array('El dato es indispensable o el dato ingresado no es válido. Rectifique! Item '.$lnitem. ' Código.')],0); 
        }

        if(empty($ldfecha)){
            return response()->json(['errors' => array('El dato es indispensable o el dato ingresado no es válido. Rectifique! Fecha.')],0); 
        }

        // para el auxx_aplicacion
        $data['pCodigo'] = $lntercero;
        $data['pcOrigen'] = $lcorigen; //corigen
        $data['pFecha'] = $ldfecha;
        $data['pnTcambio'] = $lntcambio;
        $data['pnOrigen'] = $lnorigen; //id
        $data['pnOperacion'] = $lnoperacion; //txtoperacion
        $data['pcMoneda'] = $lctipomoneda; //ctipomon
        $data['pTotal'] = 0;

        return $data;
    }

    public function aux_aplicacion(Request $request){

        //Cart::instance($this->referencia)->destroy();
        extract($this->txtdocreferencia_keypress($request));

        $data['txtid'] = $pnOrigen;
        $data['corigen'] = $pcOrigen;
        $data['txthasta'] = $pFecha;
        $data['txtoperacion'] = $pnOperacion;
        $data['ctipomon'] = $pcMoneda;
        $data['txtnum1'] = formatear_numero($pTotal, 2);
        $data['txtcambio'] = $pnTcambio;
        $data['txttotal'] = formatear_numero($pTotal, 2);

        return $data;

    }


    public function o_check1_valid(Request $request){
        $documento = collect(Cart::instance($this->referencia)->content())->firstWhere('id', $request->id);
        $documento = Cart::instance($this->referencia)->get($documento->rowId);
        $lnlimite = ($request->txtnum1 == '') ? 0 : $request->txtnum1;
        $txttotal = $request->txttotal - $documento->options->aplicar;
        $lnaplicar = 0;
        $elegido = 0;

        if($request->check == 'true'){
            if($lnlimite == 0){
                //cuando no hay limite de aplicar
                if($request->check == 'true'){ //if($cart->options->elegido == 1){
                    $lnaplicar = ($request->ctipomon == 'N') ? $documento->options->saldomn : $documento->options->saldome;
                }
            }else{
                //para los casos de nota de credito que tiene un limite para aplicar
                if($request->check == 'true'){ //if($cart->options->elegido == 1){
                    $lnaplica = ($request->txtnum1 - abs($txttotal)) + $documento->options->aplicar;
                    if($lnaplicar <> 0){
                        if($request->ctipomon == 'N'){
                            if($documento->options->saldomn < $lnaplicar){
                                $lnaplicar = $documento->options->saldomn;
                            }
                        }else{
                            if($docuemnto->options->saldome < $lnaplicar){
                                $lnaplicar = $documento->options->saldome;
                            }
                        }
                    }
                }
            }
            $elegido = 1;
            $txttotal = $txttotal + $lnaplicar;
            
        }
        $this->actualizar_carrito($documento, floatval($lnaplicar), intval($elegido), 3, 3);

        return array('txttotal' => formatear_numero($txttotal, 2));
    }

    public function actualizar_carrito($documento, $aplicar, $elegido, $total, $moneda){
        
        $item = Cart::instance($this->referencia)->get($documento->rowId);

        $options = $item->options->merge([
            'aplicar' => (gettype($aplicar) == 'string') ? $documento->options->aplicar : formatear_numero($aplicar, 2),
            'elegido' => (gettype($elegido) == 'string') ? $documento->options->elegido : $elegido,
            'total' => (gettype($total) <> 'string') ? $documento->options->total : $total,
            'moneda' => (gettype($moneda) <> 'string') ? $documento->options->moneda : $moneda,
        ]);

        Cart::instance($this->referencia)->update($documento->rowId, [
            'options' => $options]);
    }

    public function btnok(request  $request){
        if($request->txtnum1 <> 0){
            if($request->txttotal > 0){
                if($request->txtnum1 < $request->txttotal){
                    return array('mensaje' => 'Error: El importe para aplicar es menor o mayor al monto del documento de aplicación. Revise');
                }
            }
        }

        $respuesta = $this->validar($request);
        if(empty($respuesta)){
            $this->llenardet($request);
            return array('respuesta' => 'ok');
        }else{
            return $respuesta;
        }

    }

    public function validar($request){
        ///hacer llamado al detalle 
        $data = json_decode($request->data);
        $doc_select = array_column(array_column($data, 'check_ids'),'value'); // id productos seleccionados
        $count = count($doc_select);
        if($count > 0){ // existen documentos seleccionados

            $documentos = collect(Cart::instance($this->referencia)->content())->whereIn('id', $doc_select); //documentos seleccionados
            
            foreach($documentos as $cart){
                $id = (empty($cart->options->referencia_id)) ? $cart->options->referencia_id : 0;// id de la referencia
                
                $lcDoc = trim($cart->options->documento); //cambiamos la forma de validar, el clasic lo hace en el detalle, ahora validaremos en el modal y tomamos el documento de la referencia(docref del detalle)
                
                if($id != 0){
                    $ocab = DocumentToPay::selectidcmd_billing($id);
                    if(isset($ocab)){
                        $fecha = $ocab->fechadoc;
                        $tipodoc = trim($ocab->codsunat);
                        $seriedoc = trim($ocab->seriedoc);
                        $total = $ocab->total * $ocab->factor;
                        $esgratuito = intval($ocab->esgratuito);
                        if($esgratuito == 1){
                            $total = 0;
                        } 
                        $estado = trim(strtoupper ($ocab->estado));
                        $moneda = $ocab->moneda;
    
                        if($tipodoc != '01' && $tipodoc != '03' && $tipodoc != '07' && $tipodoc != '08'){
                            return response()->json(['errors' => array('El documento ' . $lcDoc . ' no es un documento válido.')],422);
                        }
    
                        $fecha_valor = date("Y-m-d", strtotime($fecha."+ 3 days"));
                        if($fecha_valor < $request->txt_fecha){
                            return response()->json(['errors' => array('El documento ' . $lcDoc . ' ha sido emitido hace más de 72 horas')],422);
                        }
    
                        $lnSaldo = MovementCte::getsaldo($id)->saldo;
                        if($lnSaldo != 0 && $estado != 'ANULADO'){
                            if($lnSaldo < $total){
                                $odata = DocumentoToPayDetraction::select('totaldetraccion')->where('parent_id', $id)->first();
                                if(isset($odata->totaldetraccion)){
                                    $total = $total - $odata->totaldetraccion;
                                    if($lnSaldo < $total){
                                        return response()->json(['errors' => array('El documento ' . $lcDoc . ' tiene aplicaciones.')],422);
                                    }
                                }else{
                                    return response()->json(['errors' => array('El documento ' . $lcDoc . ' tiene aplicaciones.')],422);
                                }
                            }
                        }
                        
                        if($this->left($seriedoc, 1) != 'B'){
                            $odet = DocumentToPayCPE::selectparentcmd($id);
                            if($odet == null){
                                return response()->json(['errors' => array('El documento ' . $lcDoc . ' no ha sido informado a Sunat')],422);
                            }
                        }
                    }
                }

                return $this->txtdocreferencia1_valid($cart, $request);
            }

        }else{
            return array('mensaje' => 'No hay documentos seleccionados');
        }
    }

    public function llenardet($request){

        $data = json_decode($request->data);
        $doc_select = array_column(array_column($data, 'check_ids'),'value'); // id productos seleccionados

        $documentos = collect(Cart::instance($this->referencia)->content())->whereIn('id', $doc_select); //documentos seleccionados
        //buscamos al tercerp
        $tercero = Customer::select('codigo', 'descripcion')->where('id', $request->tercero_id)->first();
        foreach($documentos as $cart){
            $i = Cart::instance($this->llenardet)->count();
            Cart::instance($this->llenardet)->add(['id' => $cart->options->item, 'name' => 'hola', 'qty' => 1, 'price' => 0.00,
            'options' => [
                    'item' => $i + 1,
                    'documento_id' => $cart->options->referencia_id,
                    'tercero_id' => $request->tercero_id,
                    'codigo' => $tercero->codigo,
                    'descripcion' => $tercero->descripcion,
                    'docrefer' => $cart->options->documento,
                    'moneda' => $cart->options->moneda,
                    'total' => $cart->options->aplicar,
                ]
            ]);
        }
    }

    public function o_check_total_valid(Request $request){
        $lntotal = 0;
        $elegido = 0;
        $aplicar = 0;
        foreach(Cart::instance($this->referencia)->content() as $cart){
            if($request->check == 'true'){ //if($cart->options->elegido == 1){
                $aplicar = ($request->ctipomon == 'N') ? $cart->options->saldomn : $cart->options->saldome;
                $elegido = 1;
            }
            $this->actualizar_carrito($cart, floatval($aplicar), intval($elegido), 3, 3);
            $lntotal += $cart->options->aplicar;
        }

        return array('txttotal' => formatear_numero($lntotal, 2));
    }

    public function selectparentcmd($pId){
        $doccomunicacion_cpe = DocComunicacionCPE::where('parent_id', $pId)->first();
        return $doccomunicacion_cpe;
    }

    private function llenar_carrito_detalle($doccomunicacion_id){
        $doccomunicacion_detail = DocComunicacionDetalle::where('parent_id',$doccomunicacion_id)->get();
        foreach($doccomunicacion_detail as $doccomunicacion){

            $tercero = Customer::find($doccomunicacion->tercero_id);

            $docxpagar = DocumentToPay::find($doccomunicacion->referencia_id);

            $documentocom = Commercial::select('codigo')->where('id', $docxpagar->documento_id)->first();

            $moneda = Currency::select('simbolo')->where('id', $docxpagar->moneda_id)->first();

            Cart::instance($this->llenardet)->add(['id' => $doccomunicacion->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'item' => $doccomunicacion->item,
                    'documento_id' => $doccomunicacion->referencia_id,
                    'tercero_id' => $doccomunicacion->tercero_id,
                    'codigo' => $tercero->codigo,
                    'descripcion' => $tercero->descripcion,
                    'docrefer' => $documentocom->codigo." ". $docxpagar->seriedoc."-".$docxpagar->numero,
                    'moneda' => $moneda->simbolo,
                    'total' => $doccomunicacion->importe,
                ]
            ]);
        }
    }

    public function eliminar_datos(Request $request)
    {
        Cart::instance($this->llenardet)->remove($request->rowId);

        $id = $request->item;
        session()->push('items', $id);
    }
}
