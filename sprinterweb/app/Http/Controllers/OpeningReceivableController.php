<?php

namespace App\Http\Controllers;

use App\AccountCte\Entities\MovementCte;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\Documents\Collections\OpeningReceivableCollection;
use App\Documents\Collections\OpeningToPayCollection;
use App\Documents\Entities\Commercial;
use App\DocumentToPay\Entities\DocumentToPay;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Movement;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\SalesTypes\Entities\SaleType;
use App\Shopping\Entities\TypeBut;
use App\Taxes\Entities\Taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\Facades\DataTables;

class OpeningReceivableController extends Controller
{
    use CartTrait, Movement, UserPrivileges;
    private $var = 'openingReceivable';
    private $openingReceivableCollection;
    private $provisionesPorPagarProcedure;
    private $ventana='MOV_APERTURAXCOBRAR';
    private $tabla_cabecera ='docxpagar';
    protected  $privilegios='03.02.01';
    protected $user;

    public function __construct(OpeningReceivableCollection $openingReceivableCollection, ProvisionesPorPagarProcedure $provisionesPorPagarProcedure)
    {
        $this->obtener_cliente();
        $this->openingReceivableCollection = $openingReceivableCollection;
        $this->provisionesPorPagarProcedure = $provisionesPorPagarProcedure;
        $this->user ='ADMINISTRADOR';

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
        $data['view'] = link_view('Ventas', 'Transacciòn', 'Apertura Por Cobrar','');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('openingsReceivable.list', $data);
    }

    public function list()
    {
        $openings = DocumentToPay::lista_aperturaReceivable();
//        $this->openingReceivableCollection->actions($openings);
        return DataTables::of($openings)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['proceso'] = 'crea';
        $pay = DocumentToPay::get()->last();
        if (isset($pay)) {
            $data['number'] = str_pad(($pay->numero + 1), 2, "0", STR_PAD_LEFT);;
        } else {
            $data['number'] = '01';
        }
        $period = Period::where('descripcion', Session::get('period'))->first();
        $data['period'] = $period;
        $data['date'] = date('Y-m-d');
        $data['sales'] = SaleType::all();
        $data['customers'] = Customer::all();
        $data['currencies'] = Currency::all();
        $data['route'] = route('openingReceivable');
        $data['glosa'] = 'Apertura por cobrar';
//        $data['taxes'] = Taxes::where('id', 1)->orWhere('id', 2)->get();
        $data['documents'] = Commercial::opening();
        $data['view'] = link_view('Ventas', 'Transacciòn', 'Apertura Por Cobrar','');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);


        return view('openingsReceivable.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $opening = new DocumentToPay();
            $period = Period::where('descripcion', $request->period)->first();
            $opening->periodo_id = $period->id;
            $opening->numero = $request->number;
            $date = date("Y/m/d", strtotime($request->date));
            $opening->fechaproceso = $date;
//            $opening->unegocio_id = 1;
            $opening->horaproceso = date('h:i:s');
            $opening->origen = 'Apertura por cobrar';
            $opening->tercero_id = $request->customer;
//            $opening->puntoventa_id = 1;
//            $opening->sucursal_id = 1;
//            $opening->almacen_id = 1;
//            $opening->tipotransaccion_id = 1;
//            $opening->tipocompra_id = $request->buy;
            $opening->tipoventa_id = $request->sale;
            $opening->moneda_id = $request->currency;
            /*            $opening->tcmoneda = '';*/
            $opening->documento_id = $request->document;
            $opening->seriedoc = $request->series;
            $opening->numerodoc = $request->numberOfSeries;
            $opening->fechadoc = $date;
            $opening->tcambio = $request->changerate;
//            $opening->condicionpago_id = 1;
            $opening->recepcion = $request->dateInitial;
            $opening->vencimiento = $request->dateEnd;
//            $opening->vendedor_id = 1;
            $opening->glosa = $request->glosa;
//            $opening->tiponotacredito_id = 1;
//            $opening->tiponotadebito_id = 1;
//            $opening->tipoafectaigv_id = 1;
//            $opening->impuesto_id = $request->tax;
//            $opening->impuesto2_id = 1;
//            $opening->impuesto3_id = 1;
//            $opening->impuesto4_id = 1;
//            $opening->impuesto5_id = 1;
//            $opening->tipobienservicio_id = 1;
//            $opening->tipoadq = 1;
//            $opening->base = 1;
//            $opening->inafecto = 1;
//            $opening->gratuito = 1;
//            $opening->descuento = 1;
//            if ($request->tax == 1) {
//                $opening->impuesto = $request->igv;
//            } else {
//                $opening->impuesto = 0.00;
//            }
//            $opening->impuesto2 = 1;
//            $opening->impuesto3 = 1;
//            $opening->impuesto4 = 1;
//            $opening->impuesto5 = 1;
//            $opening->isc = 1;
            $opening->total = $request->total;
//            $opening->ventana = 1;
            $opening->estado = 1;
            $opening->esapertura = 1;
//            $opening->conpercepcion = 1;
//            $opening->refgremision = 1;
//            $opening->oscliente = 1;
//            $opening->noalmacen = 1;
//            $opening->periodoemi = 1;
            $customer = Customer::findOrFail($request->customer);
            $opening->razonsocial = $customer->descripcion;
            $opening->ruc = $request->ruc;
            $opening->direccion = $request->address;
//            $opening->centraliza = 1;
//            $opening->referencial = 1;
//            $opening->ingresoalmacen_id = 1;
//            $opening->liquidacion_id = 1;
//            $opening->detraccion_id = 1;
//            $opening->ordencompra_id = 1;
//            $opening->cotizacion_id = 1;
//            $opening->ordentrabajo_id = 1;
//            $opening->pedidoalmacen_id = 1;
//            $opening->guiatransporte_id = 1;
//            $opening->docplanilla_id = 1;
            $opening->usuario = Session::get('usuario');
            $opening->observaciones = 'Apertura por cobrar';

            DB::transaction(function () use ($opening) {
                $opening->save();
//                $this->inserta_movctacte($opening);
                $this->executeGenerateCte($opening->id, 'docxpagar', '@a');
            });


            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = 'crea';

        return $r;
    }

    private function inserta_movctacte($opening)
    {
        $movCte = new MovementCte();
        $movCte->fechaproceso = $opening->fechaproceso;
        $movCte->docxpagar_id = $opening->id;
        $movCte->referencia_id = $opening->numero;
        $movCte->saldomn = $opening->total;
        $movCte->saldome = $opening->total / $opening->tcambio;
        $movCte->glosa = $opening->glosa;
        $movCte->ventana = '';
        $movCte->ventanaref = '';
        $movCte->tablaref = 'docxpagar';

        $movCte->save();
    }

    private function executeGenerateCte($parent, $ventan, $arroba)
    {
        $this->provisionesPorPagarProcedure->generaCtacte($parent, $ventan, $arroba);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['proceso'] = 'ver';
        $data['var'] = $this->var;
        $data['opening'] = DocumentToPay::findOrFail($id);
        $period = Period::select('descripcion')->where('id', $data['opening']->periodo_id)->get();
        $data['opening']->periodo_id = $period[0]->descripcion;
        $data['sales'] = SaleType::all();
        $data['documents'] = Commercial::opening();
        $data['customers'] = Customer::all();
        $data['currencies'] = Currency::all();
//        $data['taxes'] = Taxes::where('id', 1)->orWhere('id', 2)->get();
//        $data['movCte'] = MovementCte::where('docxpagar_id', $data['opening']->id)->first();
        $data['view'] = link_view('Ventas', 'Transacciòn', 'Apertura Por Cobrar','');

        return view('openingsReceivable.edit', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['opening'] = DocumentToPay::findOrFail($id);
        $period = Period::select('descripcion')->where('id', $data['opening']->periodo_id)->get();
        $data['opening']->periodo_id = $period[0]->descripcion;
        $data['sales'] = SaleType::all();
        $data['documents'] = Commercial::opening();
        $data['customers'] = Customer::all();
        $data['currencies'] = Currency::all();
//        $data['taxes'] = Taxes::where('id', 1)->orWhere('id', 2)->get();
        $data['view'] = link_view('Ventas', 'Transacciòn', 'Apertura Por Cobrar','');

        return view('openingsReceivable.edit', $data);
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
        try {
            $opening = DocumentToPay::findOrFail($id);
//            dd($id);
//            dd($request->all());
            $period = Period::where('descripcion', $request->period)->first();
            $opening->periodo_id = $period->id;
            $period = Period::where('descripcion', $request->period)->first();
            $opening->periodo_id = $period->id;
            $opening->numero = $request->number;
            $date = date("Y/m/d", strtotime($request->date));
            $opening->fechaproceso = $date;
//            $opening->unegocio_id = 1;
            $opening->horaproceso = date('h:i:s');
            $opening->origen = 'Apertura por cobrar';
            $opening->tercero_id = $request->customer;
//            $opening->puntoventa_id = 1;
//            $opening->sucursal_id = 1;
//            $opening->almacen_id = 1;
//            $opening->tipotransaccion_id = 1;
//            $opening->tipocompra_id = $request->buy;
            $opening->tipoventa_id = $request->sale;
            $opening->moneda_id = $request->currency;
            /*            $opening->tcmoneda = '';*/
            $opening->documento_id = $request->document;
            $opening->seriedoc = $request->series;
            $opening->numerodoc = $request->numberOfSeries;
            $opening->fechadoc = $date;
            $opening->tcambio = $request->changerate;
//            $opening->condicionpago_id = 1;
            $opening->recepcion = $request->dateInitial;
            $opening->vencimiento = $request->dateEnd;
//            $opening->vendedor_id = 1;
            $opening->glosa = $request->glosa;
//            $opening->tiponotacredito_id = 1;
//            $opening->tiponotadebito_id = 1;
//            $opening->tipoafectaigv_id = 1;
//            $opening->impuesto_id = $request->tax;
//            $opening->impuesto2_id = 1;
//            $opening->impuesto3_id = 1;
//            $opening->impuesto4_id = 1;
//            $opening->impuesto5_id = 1;
//            $opening->tipobienservicio_id = 1;
//            $opening->tipoadq = 1;
//            $opening->base = 1;
//            $opening->inafecto = 1;
//            $opening->gratuito = 1;
//            $opening->descuento = 1;
//            $opening->impuesto = $request->igv;
//            $opening->impuesto2 = 1;
//            $opening->impuesto3 = 1;
//            $opening->impuesto4 = 1;
//            $opening->impuesto5 = 1;
//            $opening->isc = 1;
            $opening->total = $request->total;
//            $opening->ventana = 1;
            $opening->estado = 1;
            $opening->esapertura = 1;
//            $opening->conpercepcion = 1;
//            $opening->refgremision = 1;
//            $opening->oscliente = 1;
//            $opening->noalmacen = 1;
//            $opening->periodoemi = 1;
            $customer = Customer::findOrFail($request->customer);
            $opening->razonsocial = $customer->descripcion;
            $opening->ruc = $request->ruc;
            $opening->direccion = $request->address;
//            $opening->centraliza = 1;
//            $opening->referencial = 1;
//            $opening->ingresoalmacen_id = 1;
//            $opening->liquidacion_id = 1;
//            $opening->detraccion_id = 1;
//            $opening->ordencompra_id = 1;
//            $opening->cotizacion_id = 1;
//            $opening->ordentrabajo_id = 1;
//            $opening->pedidoalmacen_id = 1;
//            $opening->guiatransporte_id = 1;
//            $opening->docplanilla_id = 1;
            $opening->usuario = Auth::id();
            $opening->observaciones = 'Apertura por cobrar';

            DB::transaction(function () use ($opening) {
                $opening->save();
//                $this->edita_movctacte($opening);
                $this->executeGenerateCte($opening->id, 'docxpagar', '@a');
            });


            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = 'edita';

        return $r;
    }

    private function edita_movctacte($opening)
    {
        $movCte = MovementCte::where('docxpagar_id', $opening->id)->first();

        //$movctacte->fechaproceso=fecha_a_ingles($request->txt_fecha);
        //$movctacte->docxpagar_id=$id_apertura;
        //$movctacte->referencia_id=$id_apertura;

        $movCte->saldomn = $opening->total;
        $movCte->saldome = $opening->total / $opening->tcambio;
        $movCte->glosa = $opening->glosa;
        //$movctacte->ventana=$this->ventana;
        //$movctacte->ventanaref=$this->ventana;
        //$movctacte->tablaref='docxpagar';

        $movCte->save();
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

    public function validateCustomer(Request $request, ConsumerController $consumerController)
    {
        try {
            $customer = Customer::findOrFail($request->customer);

            if ((strlen($customer->codigo) == 8) || (strlen($customer->codigo) == 11)) {
                if ((strlen($customer->codigo) == 8)) {
                    $consumer = $consumerController->consultar_dni($customer->codigo);

                    if ($consumer != 'dni') {
                        $data['estado'] = 'si';
                        $data['third'] = $consumer;

                    } else {
                        $data['estado'] = 'no';
                    }
                } else if ((strlen($customer->codigo) == 11)) {
                    $consumer = $consumerController->consultar_ruc_contribuyente_factura($customer->codigo);

                    if (isset($consumer)) {
                        $data['estado'] = 'si';
                        $data['third'] = $consumer;
                    } else {
                        $data['estado'] = 'no';
                    }
                }
            } else {
                $data = 0;
            }
        } catch (PDOException $e) {
            $data = $e->getMessage();
        }

        return $data;
    }

    public function calculateIgv(Request $request)
    {
        $igv = $request->total * 0.18;

        return $igv;
    }

    public function activeCancel(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                DocumentToPay::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

    public function numberSerie(Request $request)
    {
        $documents = DocumentToPay::where('documento_id', $request->document)->get();
        $current = $request->serie . ' ' . $request->number;

        foreach ($documents as $document) {
            $pay = $document->seriedoc . ' ' . $document->numerodoc;
            if ($current == $pay) {
                $data['resp']  = 1;
            }
        }

        if (isset($data['resp'])) {
            $data['message'] = 'El documento ya existe';

            return $data;
        } else {
            $data['resp'] = 0;

            return $data;
        }

    }

}
