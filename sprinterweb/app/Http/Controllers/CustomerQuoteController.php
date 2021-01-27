<?php

namespace App\Http\Controllers;

use App\Activity\Entities\Activity;
use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Deductions\Entities\Deduction;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use App\PointSale\Entities\PointSale;
use App\Project\Entities\Project;
use App\Quotation\Entities\Quotation;
use App\Seller\Entities\Seller;
use App\ServiceOrders\Entities\ServiceOrders;
use App\Taxes\Entities\Taxes;

use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class CustomerQuoteController extends Controller
{
    use CartTrait, Movement, UserPrivileges;
    private $var = 'customerquote';
    private $instancia = 'customerquote_detail';
    private $tabla_cabecera = 'cotizacion';
    protected $user;
    protected $privilegios = '03.02.02';
    private $ventana = 'mov_cotizacionventa';


    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }
    public function list(Request $request)
    {
        $periodo = Session::get('period_id');
        $customerquote = Quotation::all($periodo);
        return DataTables::of($customerquote)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Ventas', 'Transacciones', 'Cotizacion Cliente', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('customerquote.list', $data);
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
        $data['unegocioct'] = BusinessUnit::all()->where('estado',1);
        $data['puntoventa'] = PointSale::obtener_almacen();
        $data['vendedores'] = Seller::all();
        $data['numero'] = $this->generar_numero_serie(1);
        $data['route'] = route('customerquote');
        $data['view'] = link_view('Ventas', 'Transacción', 'Cotizacion Cliente', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $this->eliminar_instancias();

        return view('customerquote.create', $data);
    }

    public function store(Request $request)
    {
        try {
            $cotizacion = new Quotation();
//            $sucursal = Customer::select('sucursal_id')->where('id', $request->tercero_id)->first();
            $cotizacion->periodo_id = Session::get('period_id');
            $cotizacion->serie = $request->series_cotiza;
            $cotizacion->numero = $request->number_series;
            $cotizacion->tipo = 'S';
            $cotizacion->fechaproceso = (isset($request->processdate)) ? $request->processdate : '';
            $cotizacion->unegocio_id = (isset($request->cbo_unegocio_coti)) ? $request->cbo_unegocio_coti : null;
            $cotizacion->tercero_id = (isset($request->tercero_id)) ? $request->tercero_id : null;
            $cotizacion->fechaentrega = (isset($request->dateentrega)) ? $request->dateentrega : null;

//            $servicio->sucursal_id = $sucursal->sucursal_id;
            $cotizacion->sucursal_id = 1;
            $cotizacion->almacen_id = null;
            $cotizacion->moneda_id = (isset($request->currency)) ? $request->currency : null;
            $cotizacion->tcambio = (isset($request->changerate)) ? $request->changerate : '';
            $cotizacion->condicionpago_id = (isset($request->condpayment)) ? $request->condpayment : null;
            $cotizacion->fechaentrega = (isset($request->txt_fecha_entrega)) ? $request->txt_fecha_entrega : '';
            $cotizacion->lugarentrega = (isset($request->txt_direccion)) ? $request->txt_direccion : '';
            $cotizacion->solicitadopor = (isset($request->txt_contacto)) ? $request->txt_contacto : '';
            $cotizacion->ctacte = (isset($request->ctacte)) ? $request->ctacte : '';
            $cotizacion->glosa = (isset($request->comment)) ? $request->comment : '';
            $cotizacion->impuesto_id = (isset($request->igv)) ? $request->igv : null;
            $cotizacion->impuesto3_id = (isset($request->rent)) ? $request->rent : null;
            $cotizacion->base = (isset($request->txt_base)) ? $request->txt_base : '';
            $cotizacion->inafecto = (isset($request->txt_inactive)) ? $request->txt_inactive : '';
            $cotizacion->impuesto = (isset($request->txt_igvtotal)) ? $request->txt_igvtotal : '';
            $cotizacion->impuesto3 = (isset($request->txt_rentimport)) ? $request->txt_rentimport : '';
            $cotizacion->total = (isset($request->txt_total)) ? $request->txt_total : '';
            $cotizacion->ventana = $this->ventana;
            $cotizacion->estado = 'ACTIVO';
            $cotizacion->incluyeimpto = ($request->chktax == 'on') ? 1 : 0;
            $cotizacion->esservicio = 1;
            $cotizacion->tipopeso = 0;
            $cotizacion->usuario = Auth::id();
            $cotizacion->undtransporte_id = (isset($request->cbo_placa)) ? $request->cbo_placa : null;
            $cotizacion->puntoventa_id = 1;
            $cotizacion->tipodetraccion_id = (isset($request->txt_detraccion)) ? $request->txt_detraccion : null;
            $cotizacion->referencial = ($request->txt_valor_referencial == '') ? '' : $request->txt_valor_referencial;
            $cotizacion->totaldetraccion = (isset($request->txt_importe)) ? $request->txt_importe : '';
            $cotizacion->notas = (isset($request->txt_notas)) ? $request->txt_notas : '';
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
    public function eliminar_instancias()
    {
        Cart::instance($this->instancia)->destroy();
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
}
