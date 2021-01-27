<?php

namespace App\Http\Controllers;

use App\Activity\Entities\Activity;
use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Customers\Entities\CustomerContact;
use App\Deductions\Entities\Deduction;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Http\Requests\PurchaseRequest;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\Products\Entities\Productos;
use App\Project\Entities\Project;
use App\ServiceOrders\Collection\ServiceOrdersCollection;
use App\ServiceOrders\Entities\ServiceOrders;
use App\ServiceOrders\Entities\ServiceOrdersDetail;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Taxes\Entities\Taxes;
use App\WorkOrder\Entities\WorkOrder;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;
use App\Procedures\PurchaseOrderProcedure;

class ServiceOrdersController extends Controller
{
    use CartTrait, Movement, UserPrivileges;
    private $var = 'serviceorders';
    private $instancia = 'serviceorders_detail';
    private $instancia_proviciones = 'proviciones';
    private $serviceorderscollection;
    private $ventana = 'MOV_ORDENSERVICIO';
    private $purchaseOrderProcedure;
    private $tabla_cabecera = 'ordencompra';

    protected $privilegios = '04.02.03';
    protected $user;

    public function __construct(PurchaseOrderProcedure $purchaseOrderProcedure)
    {
        $this->obtener_cliente();
        $this->purchaseOrderProcedure = $purchaseOrderProcedure;
        //$this->serviceorderscollection = $serviceorderscollection;
        $this->user = 'ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Gestión de Compras', 'Transacción', 'Órdenes de Servicio', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('serviceorders.list', $data);
    }

    public function list()
    {
        $resultado = ServiceOrders::lista_ordenservicios();
        return DataTables::of($resultado)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        session()->put('items', []);
        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['condicionpagos'] = PaymentCondition::all()->where('estado', 1);
        $data['monedas'] = Currency::all()->where('estado', 1);
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos3'] = Taxes::crsimpuesto3();
        $data['centroscosto'] = Costs::all()->where('estado', 1);
        $data['proyectos'] = Project::all()->where('estado', 1);
        $data['actividades'] = Activity::all()->where('estado', 1);
        $data['unegocio'] = BusinessUnit::all()->where('estado',1);
        $data['puntoventa'] = PointSale::obtener_almacen();
        $data['numero'] = $this->generar_numero_serie(1);
        $data['serie'] = str_pad(1,5, 0, STR_PAD_LEFT);
        $data['route'] = route('serviceorders');
        $data['view'] = link_view('Compras', 'Transacción', 'Órdenes de Servicio', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $this->eliminar_instancias();

        return view('serviceorders.create', $data);
    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseRequest $request)
    {
        try {
            $servicio = new ServiceOrders();
//            $sucursal = Customer::select('sucursal_id')->where('id', $request->tercero_id)->first();
            $servicio->periodo_id = Session::get('period_id');
            $servicio->serie = $request->txt_series;
            $servicio->numero = $request->number_series;
            $servicio->tipo = 'S';
            $servicio->fechaproceso = (isset($request->processdate)) ? $request->processdate : '';
            $servicio->unegocio_id = (isset($request->cbo_unegocio)) ? $request->cbo_unegocio : null;
            $servicio->tercero_id = (isset($request->tercero_id)) ? $request->tercero_id : null;
//            $servicio->sucursal_id = $sucursal->sucursal_id;
            $servicio->sucursal_id = 1;
            $servicio->almacen_id = null;
            $servicio->moneda_id = (isset($request->currency)) ? $request->currency : null;
            $servicio->tcambio = (isset($request->changerate)) ? $request->changerate : '';
            $servicio->condicionpago_id = (isset($request->condpayment)) ? $request->condpayment : null;
            $servicio->fechaentrega = (isset($request->txt_fecha_entrega)) ? $request->txt_fecha_entrega : '';
            $servicio->lugarentrega = (isset($request->txt_direccion)) ? $request->txt_direccion : '';
            $servicio->solicitadopor = (isset($request->txt_contacto)) ? $request->txt_contacto : '';
            $servicio->ctacte = (isset($request->ctacte)) ? $request->ctacte : '';
            $servicio->glosa = (isset($request->comment)) ? $request->comment : '';
            $servicio->impuesto_id = (isset($request->igv)) ? $request->igv : null;
            $servicio->impuesto3_id = (isset($request->rent)) ? $request->rent : null;
            $servicio->base = (isset($request->txt_base)) ? $request->txt_base : '';
            $servicio->inafecto = (isset($request->txt_inactive)) ? $request->txt_inactive : '';
            $servicio->impuesto = (isset($request->txt_igvtotal)) ? $request->txt_igvtotal : '';
            $servicio->impuesto3 = (isset($request->txt_rentimport)) ? $request->txt_rentimport : '';
            $servicio->total = (isset($request->txt_total)) ? $request->txt_total : '';
            $servicio->ventana = $this->ventana;
            $servicio->estado = 'ACTIVO';
            $servicio->incluyeimpto = ($request->chktax == 'on') ? 1 : 0;
            $servicio->esservicio = 1;
            $servicio->tipopeso = 0;
            $servicio->usuario = Auth::id();
            $servicio->undtransporte_id = (isset($request->cbo_placa)) ? $request->cbo_placa : null;
            $servicio->puntoventa_id = 1;
            $servicio->tipodetraccion_id = (isset($request->txt_detraccion)) ? $request->txt_detraccion : null;
            $servicio->referencial = ($request->txt_valor_referencial == '') ? '' : $request->txt_valor_referencial;
            $servicio->totaldetraccion = (isset($request->txt_importe)) ? $request->txt_importe : '';
            $servicio->notas = (isset($request->txt_notas)) ? $request->txt_notas : '';
            $id = null;
            $id = DB::transaction(function () use ($request,$servicio) {
                $servicio->save();
                $this->save_details($request->afecto,$servicio->id);
                return $servicio->id;
            });
            $r["ruta"] = route('edit.serviceorders', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        $this->eliminar_instancias();

        return $r;
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
        session()->put('items', []);
        $this->eliminar_instancias();
        $servicio = ServiceOrders::findOrFail($id);
        $data['servicio'] = $servicio;
        $data['detalle'] = ServiceOrdersDetail::select('esafecto')->where('parent_id', $servicio->id)->first();
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['period'] = Period::where('id', $servicio->periodo_id)->first();
        $fecha = new Carbon($servicio->fechaproceso);
        $data['fecha'] = $fecha->format('Y-m-d');
        $data['condicionpagos'] = PaymentCondition::all()->where('estado', 1);
        $data['monedas'] = Currency::all()->where('estado', 1);
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos3'] = Taxes::crsimpuesto3();
        $data['terceros'] = Customer::select('id', 'codigo', 'descripcion', 'ruc')->where('id',$servicio->tercero_id)->first();
        $data['tercero_contacto'] = CustomerContact::cargar_contacto($servicio->tercero_id);
        $data['centroscosto'] = Costs::all()->where('estado', 1);
        $data['proyectos'] = Project::all()->where('estado', 1);
        $data['actividades'] = Activity::all()->where('estado', 1);
        $data['unegocio'] = BusinessUnit::all()->where('estado',1);
        $data['puntoventa'] = PointSale::obtener_almacen();
        $data['depositar'] = Customer::tercero_cuenta($servicio->tercero_id);
        $numero = ServiceOrders::all()->where('tipo', 'S')->where('serie',intval($servicio->serie))->last();
        $data['numero'] = $numero_serie = str_pad($numero->numero, 5, '0', STR_PAD_LEFT);
        $data['serie'] = str_pad($servicio->serie,5, 0, STR_PAD_LEFT);
        $data['tipodetraccion'] = Deduction::select('id','codigo','descripcion')->where('id', $servicio->tipodetraccion_id)->first();
        $data['route'] = route('serviceorders');
        $data['view'] = link_view('Compras', 'Transacción', 'Órdenes de Servicio', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $servicio->estado, $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->llenar_carrito($id);
        return view('serviceorders.edit', $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseRequest $request, $id)
    {
        try {
            $servicio = ServiceOrders::findOrFail($id);
//            $sucursal = Customer::select('sucursal_id')->where('id', $request->tercero_id)->first();
            $servicio->periodo_id = Session::get('period_id');
            $servicio->serie = $request->txt_series;
            $servicio->numero = $request->number_series;
//            $servicio->tipo = 'S';
            $servicio->fechaproceso = (isset($request->processdate)) ? $request->processdate : '';
            $servicio->unegocio_id = (isset($request->cbo_unegocio)) ? $request->cbo_unegocio : null;
            $servicio->tercero_id = (isset($request->tercero_id)) ? $request->tercero_id : null;
//            $servicio->sucursal_id = $sucursal->sucursal_id;
//            $servicio->almacen_id = null;
            $servicio->moneda_id = (isset($request->currency)) ? $request->currency : null;
            $servicio->tcambio = (isset($request->changerate)) ? $request->changerate : '';
            $servicio->condicionpago_id = (isset($request->condpayment)) ? $request->condpayment : null;
            $servicio->fechaentrega = (isset($request->txt_fecha_entrega)) ? $request->txt_fecha_entrega : '';
            $servicio->lugarentrega = (isset($request->txt_direccion)) ? $request->txt_direccion : '';
            $servicio->solicitadopor = (isset($request->txt_contacto)) ? $request->txt_contacto : '';
            $servicio->ctacte = (isset($request->ctacte)) ? $request->ctacte : '';
            $servicio->glosa = (isset($request->comment)) ? $request->comment : '';
            $servicio->impuesto_id = (isset($request->igv)) ? $request->igv : null;
            $servicio->impuesto3_id = (isset($request->rent)) ? $request->rent : null;
            $servicio->base = (isset($request->txt_base)) ? $request->txt_base : '';
            $servicio->inafecto = (isset($request->txt_inactive)) ? $request->txt_inactive : '';
            $servicio->impuesto = (isset($request->txt_igvtotal)) ? $request->txt_igvtotal : '';
            $servicio->impuesto3 = (isset($request->txt_rentimport)) ? $request->txt_rentimport : '';
            $servicio->total = (isset($request->txt_total)) ? $request->txt_total : '';
//            $servicio->ventana = $this->ventana;
//            $servicio->estado = 'ACTIVO';
            $servicio->incluyeimpto = ($request->chktax == 'on') ? 1 : 0;
//            $servicio->esservicio = 1;
//            $servicio->tipopeso = 0;
            $servicio->usuario = Auth::id();
            $servicio->undtransporte_id = (isset($request->cbo_placa)) ? $request->cbo_placa : null;
//            $servicio->puntoventa_id = 1;
            $servicio->tipodetraccion_id = (isset($request->txt_detraccion)) ? $request->txt_detraccion : null;
            $servicio->referencial = ($request->txt_valor_referencial == '') ? '' : $request->txt_valor_referencial;
            $servicio->totaldetraccion = (isset($request->txt_importe)) ? $request->txt_importe : '';
            $servicio->notas = (isset($request->txt_notas)) ? $request->txt_notas : '';
            $id = null;
            $id = DB::transaction(function () use ($request, $servicio) {
                $servicio->save();
                $this->save_details($request->afecto, $servicio->id);
                return $servicio->id;
            });
            $r["ruta"] = route('edit.serviceorders', $id);
            $r["success"] = "Se guardaron los cambios correctamente";
        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
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

    public function save_details($afecto, $id)
    {
        try {
            $items = Session::get('items');
            if ($items){
                foreach ($items as $item) {
                    ServiceOrdersDetail::where(['parent_id' => $id, 'item' => $item])->delete();
                    session()->put('items', []);
                }
            }
            foreach (Cart::instance($this->instancia)->content() as $cart) {
                $existe = ServiceOrdersDetail::where("parent_id", $id)->where("item", $cart->options->item)->exists();
                if ($existe) { //no existe
                    $item = $cart->options->item;
                    $data['producto_id'] = ($cart->options->producto_id != '') ? $cart->options->producto_id : '';
                    $data['umedida_id'] = ($cart->options->umedida_id != '') ? $cart->options->umedida_id : '';
                    $data['cantidad'] = ($cart->options->cantidad == '') ? '' : $cart->options->cantidad;
                    $data['precio'] = ($cart->options->precio1 == '') ? '' : $cart->options->precio1;
                    $data['valor'] = ($cart->options->precio1 == '') ? '' : $cart->options->precio1;
                    $data['subtotal'] = ($cart->options->subtotal == '') ? '' : $cart->options->subtotal;
                    $data['importe'] = ($cart->options->importe == '') ? '' : $cart->options->importe;
                    $data['glosa'] = ($cart->options->glosa == '') ? '' : $cart->options->glosa;
                    $data['esafecto'] = $afecto;
                    $data['usuario'] = Auth::id();
                    $data['centrocosto_id'] = ($cart->options->ccosto_id != '') ? $cart->options->ccosto_id : null;
                    $data['proyecto_id'] = ($cart->options->proyecto_id != '') ? $cart->options->proyecto_id : null;
                    $data['actividad_id'] = ($cart->options->actividad_id != '') ? $cart->options->actividad_id : null;
                    $data['ordentrabajo_id'] = ($cart->options->op_id != '') ? $cart->options->op_id : null;
                    ServiceOrdersDetail::saveDetails($id, $cart->options->item, $data);
//                'op_id' => ($request->op_id) ? '' : $request->op_id,
//                'op_codigo' => ($request->op_codigo == '') ? '' : $request->op_codigo,
                } else { //Editando
                    $detalle = new ServiceOrdersDetail();
                    $detalle->parent_id = $id;
                    $detalle->item = $cart->options->item;
                    $detalle->producto_id = ($cart->options->producto_id != '') ? $cart->options->producto_id : null;
                    $detalle->umedida_id = ($cart->options->umedida_id != '') ? $cart->options->umedida_id : null;
                    $detalle->cantidad = ($cart->options->cantidad == '') ? '' : $cart->options->cantidad;
                    $detalle->precio = ($cart->options->precio1 == '') ? '' : $cart->options->precio1;
                    //solo para prrueba
                    $detalle->valor = ($cart->options->precio1 == '') ? '' : $cart->options->precio1;
                    //$detalle->valor = $cart->options->has('costounit') ? $cart->options->costounit : '';
                    $detalle->subtotal = ($cart->options->subtotal == '') ? '' : $cart->options->subtotal;
                    $detalle->importe = ($cart->options->importe == '') ? '' : $cart->options->importe;
                    $detalle->glosa = ($cart->options->glosa == '') ? '' : $cart->options->glosa;
                    $detalle->esafecto = $afecto;
                    $detalle->usuario = Auth::id();
                    $detalle->centrocosto_id = ($cart->options->ccosto_id != '') ? $cart->options->ccosto_id : null;
                    $detalle->proyecto_id = ($cart->options->proyecto_id != '') ? $cart->options->proyecto_id : null;
                    $detalle->actividad_id = ($cart->options->actividad_id != '') ? $cart->options->actividad_id : null;
                    $detalle->ordentrabajo_id = ($cart->options->op_id != '') ? $cart->options->op_id : null;
                    $detalle->save();
                }
            }
        }catch (PDOException $e){
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
            return $r;
        }
    }

    public function list_detalle_documento()
    {
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }
    public function eliminar_instancias()
    {
        Cart::instance($this->instancia)->destroy();
    }

    public function lista_provisiones(Request $request){
        $docxpagar = DocumentToPay::data_ordencompra($request->ordencompra_id);
        $data_env = array();
        foreach($docxpagar as $d){
            if($d->moneda['tipo'] != 'N'){
                $moneda_extranjera = $d->total;
                $moneda_nacional = $d->total * $d->tcambio;
            }else{
                $moneda_extranjera = $d->total / $d->tcambio;
                $moneda_nacional = $d->total;
            }
            $data = array(
                'fecha' => $d->fechaproceso,
                'referencia' => $d->referencia,
                'glosa' => $d->glosa,
                'importemn' => formatear_numero($moneda_nacional,2),
                'importeme' => formatear_numero($moneda_extranjera,2),
            );
            $data_env[] = $data;
        }

        return DataTables::of($data_env)->make();

    }

    public static function sumar_footer_referencias(Request $request){
        //$request->ordencompra_id
//        $ingresoalmacen = WarehouseIncome::data_ordencompra($request->ordencompra_id);
        $docxpagar = DocumentToPay::data_ordencompra($request->ordencompra_id);
//
//        $sum_ingresoalmacen_n = 0;
//        $sum_ingresoalmacen_e = 0;
        $sum_provision_n = 0;
        $sum_provision_e = 0;
//        foreach ($ingresoalmacen as $i) {
//            $sum_ingresoalmacen_n += $i->totalmn;
//            $sum_ingresoalmacen_e += $i->totalme;
//        }
        foreach($docxpagar as $d){
            if($d->moneda['tipo'] != 'N'){
                $moneda_extranjera = $d->total;
                $moneda_nacional = $d->total * $d->tcambio;
            }else{
                $moneda_extranjera = $d->total / $d->tcambio;
                $moneda_nacional = $d->total;
            }
            $sum_provision_n += $moneda_nacional;
            $sum_provision_e += $moneda_extranjera;
        }
//        $data['sum_ingresoalmacen_n'] = $sum_ingresoalmacen_n;
//        $data['sum_ingresoalmacen_e'] = $sum_ingresoalmacen_e;
        $data['sum_provision_n'] = formatear_numero($sum_provision_n,2);
        $data['sum_provision_e'] = formatear_numero($sum_provision_e,2);
        return $data;
    }

    public function generar_numero_serie($serie){
        $numero = ServiceOrders::all()->where('tipo', 'S')->where('serie',intval($serie))->last();
        if (isset($numero->numero) || $numero->numero < 1){
            $numero_serie = str_pad('1', 5, '0', STR_PAD_LEFT);
        }else{
            $numero_serie = str_pad($numero->numero +1, 5, '0', STR_PAD_LEFT);
        }
        return $numero_serie;
    }

    public function impuesto(Request $request)
    {

        $impuesto = $impuesto3 = $impuesto3_id = $chk_incluye = $lnvalor = 0;
        if ($request->igv) {
            $impuesto = Taxes::find($request->igv);
            if ($impuesto->tipocalculo == 'P') {
                $lnvalor = ($request->txt_inactive + $request->txt_base) * ($impuesto->valor / 100);
            } else {
                $lnvalor = $impuesto->valor;
            }
        }
        if ($lnvalor > 0) {
            $impuesto3 = 0;
            $impuesto3_id = 0;
            $impuesto = $lnvalor;
            if(isset($request->chktax)) {$data['chk_incluye'] = 1;}else{$data['chk_incluye'] = 0;}
        } else {
            $impuesto = 0;
            $chk_incluye = 0;
            $r["chk_incluye"] = $chk_incluye;
        }
        $r["impuesto"] = formatear_numero($lnvalor, 2);
        $r["impuesto3"] = formatear_numero($impuesto3, 2);

        return $r;
    }

    public function impuesto3(Request $request)
    {
        $impuestos = $impuesto2 = $impuesto_id = $base =  $impuesto2_id = $impuesto3 = $lnvalor = 0;
        if ($request->rent) {
//            dd($request->rent);
            $impuesto = Taxes::find($request->rent);
            if ($impuesto->tipocalculo == 'P') {
                $lnvalor = ($request->txt_inactive + $request->txt_base) * ($impuesto->valor / 100);
            } else {
                $lnvalor = $impuesto->valor;
            }
        }
        if ($lnvalor > 0) {
            $impuestos = 0;
            $impuesto2 = 0;
            $impuesto_id = 0;
            $impuesto2_id = 0;
            $base = 0;
            $impuesto3 = $lnvalor;
            $chk_incluye = 0;
            $r["chk_incluye"] = $chk_incluye;
        } else {
            $impuesto3 = 0;
        }
//        dd($impuestos);
        $r["impuesto"] = formatear_numero($impuestos, 2);
//        $r["base"] = round($base, 2);
        $r["impuesto3"] = formatear_numero($impuesto3, 2);
        return $r;
    }

    public function totalizar(Request $request)
    {
        $lnitem = $lnafecto = $lninafecto = $importe = $lnvalor = 0;
        if (isset($request->igv)){
            if ($request->igv > 0) {
                $valor = Taxes::select('valor')->where('id', $request->igv)->first();
                $lnvalor = $valor->valor / 100;
            }
        }
        if (isset($request->rent)) {
            if ($request->rent) {
                $valor = Taxes::select('valor')->where('id', $request->rent)->first();
                $lnvalor = $valor->valor / 100;
            }
        }
        if ($lnvalor > 0){
            foreach (Cart::instance($this->instancia)->content() as $cart) {
                if (isset($request->chktax) & $request->chktax == 'on') {
                    if ($lnvalor > 0) {
                        $importe = $cart->options->subtotal / (1 + $lnvalor);
                    } else {
                        $importe = $cart->options->subtotal;
                    }
                    if ($cart->options->cantidad > 0) {
                        $precio = $importe / $cart->options->cantidad;
                    }
                } else {
                    $precio = $cart->options->precio1;
                    $importe = $cart->options->subtotal;
                }
                if ($lnvalor == 0) {
                    $lninafecto = $lninafecto + $importe;
                } else {
                    if ($request->afecto == 1) {
                        $lnafecto = $lnafecto + $importe;
                    } else {
                        $lninafecto = $lninafecto + $importe;
                    }
                }
            }
        }else{
            foreach (Cart::instance($this->instancia)->content() as $cart) {
                $lninafecto +=  $cart->options->subtotal;
                $importe = $cart->options->subtotal;
                $precio = 0;
            }
        }

        $r["base"] = formatear_numero($lnafecto, 2);
        $r["inafecto"] = formatear_numero($lninafecto, 2);
        return $r;
    }

    public function sumar(Request $request)
    {
        $lntotal = 0;
        $total = $impuesto = $lntotal = 0;
        $impuesto = $request->igvtotal;
        if (isset($request->chktax) & $request->chktax == 'on' || $request->txt_rentimport < 1) {
            if (Cart::instance($this->instancia)->count() > 0) {
                foreach (Cart::instance($this->instancia)->content() as $cart) {
                    $lntotal += $cart->options->subtotal;
                }
            }else{
                $lntotal += $request->subtotal;
            }
            $total = $lntotal;
            $impuesto = $lntotal - $request->txt_base - $request->inactive;
            $total = $request->txt_base + $request->txt_inactive + $request->txt_igvtotal - $request->txt_rentimport;
        } else {
            $total = $request->txt_base + $request->txt_inactive + $request->txt_igvtotal - $request->txt_rentimport;
        }
        $r["total"] = formatear_numero($total, 2);
        $r["impuesto"] = formatear_numero($impuesto, 2);
        return $r;
    }

    public function agregar_item(PurchaseRequest $request)
    {
        $ultimoCart = Cart::instance($this->instancia)->content()->sortByDesc("id")->first();
        if ($ultimoCart == null) {
            $ultimoBd = ServiceOrdersDetail::where('parent_id', $request->parent_id)->get()->last();
            $item = !$ultimoBd ? 0 : $ultimoBd->item;
        } else {
            $item = $ultimoCart->options->item;
        }
        $producto = Productos::find($request->producto_id);
        $centrocosto = Costs::find($request->centrocosto_id);
        $proyecto = Project::find($request->proyecto_id);
        $actividad = Activity::find($request->actividad_id);
        Cart::instance($this->instancia)->add(['id' => $item +1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $request->parent_id,
                'item' => $item + 1,
                'producto_id' => (isset($request->producto_id)) ? $request->producto_id : '',
                'producto_codigo' => (isset($producto->codigo)) ? $producto->codigo : '',
                'producto_descripcion' => (isset($producto->descripcion)) ? $producto->descripcion : '',
                'umedida_id' => (isset($producto->umedida_id)) ? $producto->umedida_id : '',
                'umedida_codigo' => ($request->um == '') ? '' : $request->um,
                'cantidad' => ($request->cantidad == '') ? '' : formatear_numero($request->cantidad,3),
                'precio1' => ($request->costounidad == '') ? '' : formatear_numero($request->costounidad, 6),
                'importe' => ($request->importe == '') ? '' : formatear_numero($request->importe, 2),
                'op_id' => ($request->op_id) ? '' : $request->op_id,
                'op_codigo' => ($request->op_codigo == '') ? '' : $request->op_codigo,
                'ccosto_id' => ($request->centrocosto_id == '') ? '' : $request->centrocosto_id,
                'ccosto_codigo' => (isset($centrocosto->codigo)) ?  $centrocosto->codigo : '',
                'ccosto_descripcion' => (isset($centrocosto->descripcion)) ? $centrocosto->descripcion : '',
                'glosa' => ($request->glosa == '') ? '' : $request->glosa,
                'proyecto_id' => ($request->proyecto_id == '') ? '' : $request->proyecto_id,
                'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : '',
                'proyecto_descripcion' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : '',
                'actividad_id' => ($request->actividad_id == '') ? '' : $request->actividad_id,
                'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : '',
                'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : '',
                'subtotal' => ($request->importe == '') ? '' : $request->importe,
            ]]);

        return $this->instancia;
    }

    public function update_item(PurchaseRequest $request)
    {
        $item = Cart::instance($this->instancia)->get($request->row_id);
        $producto = Productos::find($request->producto_id);
        $centrocosto = Costs::find($request->centrocosto_id);
        $proyecto = Project::find($request->proyecto_id);
        $actividad = Activity::find($request->actividad_id);
        $options = $item->options->merge([
            'producto_id' => ($request->producto_id == '') ? '' : $request->producto_id,
            'producto_codigo' => (isset($producto->codigo)) ? $producto->codigo : '',
            'producto_descripcion' => (isset($producto->descripcion)) ? $producto->descripcion : '',
            'umedida_id' => (isset($producto->umedida_id)) ? $producto->umedida_id : '',
            'umedida_codigo' => ($request->um == '') ? '' : $request->um,
            'cantidad' => ($request->cantidad == '') ? '' : formatear_numero($request->cantidad, 3),
            'precio1' => ($request->costounidad == '') ? '' : formatear_numero($request->costounidad, 5),
            'importe' => ($request->importe == '') ? '' : formatear_numero($request->importe, 2),
            'op_id' => ($request->op_id) ? '' : $request->op_id,
            'op_codigo' => ($request->op_codigo == '') ? '' : $request->op_codigo,
            'ccosto_id' => ($request->centrocosto_id == '') ? '' : $request->centrocosto_id,
            'ccosto_codigo' => (isset($centrocosto->codigo)) ?  $centrocosto->codigo : '',
            'ccosto_descripcion' => (isset($centrocosto->descripcion)) ? $centrocosto->descripcion : '',
            'glosa' => ($request->glosa == '') ? '' : $request->glosa,
            'proyecto_id' => ($request->proyecto_id == '') ? '' : $request->proyecto_id,
            'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : '',
            'proyecto_descripcion' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : '',
            'actividad_id' => ($request->actividad_id == '') ? '' : $request->actividad_id,
            'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : '',
            'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : '',
            'subtotal' => ($request->importe == '') ? '' : $request->importe,
        ]);
        Cart::instance($this->instancia)->update($request->row_id, [
            'options' => $options
        ]);

    }

    public function eliminar_datos(Request $request)
    {
        Cart::instance($this->instancia)->remove($request->rowId);

        $id = $request->item;
        session()->push('items', $id);
    }

    public function buscar_detraccion(Request $request){
        $resultado = Deduction::select2($request->term);
        return $resultado;
    }

    public function buscar_undtransporte(Request $request){
        $resultado = UndTransport::select2($request->term);
        return $resultado;
    }

    public function importe_adicional(Request $request)
    {
        $lntasa = $lntotal = $totaldetraccion = 0;
        //CONDICION FALTARIA
        if ($request->txt_detraccion > 0) {
            $id = $request->txt_detraccion;
            $valor = Deduction::select('valor')->where('id', $id)->first();
            $lntasa = $valor->valor / 100;
        }
        if ($request->txt_total > $request->txt_valor_referencial){
            $totaldetraccion = $request->txt_total * $lntasa;
        }else{
            $totaldetraccion = $request->txt_valor_referencial * $lntasa;
        }
        $r['importe'] = round($totaldetraccion, 2);
        return $r;
    }

    public function cargar_contactos($id){
        $resultado = CustomerContact::cargar_contacto($id);
        return $resultado;
    }

    private function llenar_carrito($servicio_id){
        $id = ServiceOrdersDetail::where('parent_id',$servicio_id)->get();
        foreach($id as $p){
            $producto = Productos::find($p->producto_id);
            $centrocosto = Costs::find($p->centrocosto_id);
            $proyecto = Project::find($p->proyecto_id);
            $actividad = Activity::find($p->actividad_id);
            $umedida = UnidadesMedida::find($p->umedida_id);
            Cart::instance($this->instancia)->add(['id' => $p->item, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $p->parent_id,
                    'item' => $p->item,
                    'producto_id' => (isset($p->producto_id)) ? $p->producto_id : '',
                    'producto_codigo' => (isset($producto->codigo)) ? $producto->codigo : '',
                    'producto_descripcion' => (isset($producto->descripcion)) ? $producto->descripcion : '',
                    'umedida_id' => (isset($producto->umedida_id)) ? $producto->umedida_id : '',
                    'umedida_codigo' => (isset($umedida->codigo)) ? $umedida->codigo : '',
                    'cantidad' => ($p->cantidad == '') ? '' : formatear_numero($p->cantidad, 3),
                    'precio1' => ($p->precio == '') ? '' : formatear_numero($p->precio, 6),
                    'importe' => ($p->importe == '') ? '' : formatear_numero($p->importe, 2),
                    'op_id' => ($p->op_id) ? '' : $p->op_id,
                    'op_codigo' => ($p->op_codigo == '') ? '' : $p->op_codigo,
                    'ccosto_id' => ($p->centrocosto_id == '') ? '' : $p->centrocosto_id,
                    'ccosto_codigo' => (isset($centrocosto->codigo)) ?  $centrocosto->codigo : '',
                    'ccosto_descripcion' => (isset($centrocosto->descripcion)) ? $centrocosto->descripcion : '',
                    'glosa' => ($p->glosa == '') ? '' : $p->glosa,
                    'proyecto_id' => ($p->proyecto_id == '') ? '' : $p->proyecto_id,
                    'proyecto_codigo' => (isset($proyecto->codigo)) ? $proyecto->codigo : '',
                    'proyecto_descripcion' => (isset($proyecto->descripcion)) ? $proyecto->descripcion : '',
                    'actividad_id' => ($p->actividad_id == '') ? '' : $p->actividad_id,
                    'actividad_codigo' => (isset($actividad->codigo)) ? $actividad->codigo : '',
                    'actividad_descripcion' => (isset($actividad->descripcion)) ? $actividad->descripcion : '',
                    'subtotal' => ($p->subtotal == '') ? '' : $p->subtotal,
                ]
            ]);
        }
    }

    public function archivar(Request $request){
        $ordencompra = ServiceOrders::select('estado')->where('id', $request->id)->first();
        if($ordencompra->estado == 'ARCHIVADO'){
            return  $this->setestado('',$request->id);
        }else{
            return $this->setestado('ARCHIVADO',$request->id);
        }
    }

    public function setestado($estado, $id){
        if($id != 0){
            if(empty($estado)){
                $this->purchaseOrderProcedure->setordencompraestado($id, 'ingresoalmacen');
            }else{
                ServiceOrders::where('id', $id)->update(['estado' => $estado]);
            }
        }

        $r = array();
        $estado = ServiceOrders::select('estado')->where('id',$id)->first();
        if($estado){
            $r['estado'] = 'ok';
            $r['mensaje'] = 'se ha cambiado el estado';
            $r['estado_tabla'] = $estado->estado;
        }
        return $r;
    }

    public function buscar_servicios(Request $request){
        $resultado = Productos::servicios($request->term);
        return $resultado;
    }

    public function depositaren($tercero_id)
    {
        $resultado = Customer::tercero_cuenta($tercero_id);
        return $resultado;
    }

}
