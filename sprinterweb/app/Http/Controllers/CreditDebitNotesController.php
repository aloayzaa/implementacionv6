<?php

namespace App\Http\Controllers;

use App\Activity\Entities\Activity;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\CurrentAccountMovement\Entities\CurrentAccountMovement;
use App\Customers\Entities\Customer;
use App\DocumentToPay\Collections\CreditDebitNotesCollection;
use App\DocumentToPay\Entities\DocumentToPay;
use App\DocumentToPay\Entities\DocumentToPayDetail;
use App\AccountingPlan\Entities\AccountingPlan;
use App\GoodsServicesType\Entities\GoodsServicesType;
use App\MeasurementUnits\Entities\UnidadesMedida;
use App\MovementTypes\Entities\MovementType;
use App\PaymentCondition\Entities\PaymentCondition;
use App\Period\Entities\Period;
use App\Procedures\ProvisionesPorPagarProcedure;
use App\Products\Entities\Productos;
use App\Project\Entities\Project;
use App\PurchaseDocument\Entities\PurchaseDocument;
use App\PurchasesType\Entities\PurchasesType;
use App\Reference\Entities\Reference;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Taxes\Entities\Taxes;
use App\Warehouses\Entities\WarehouseIncome;
use App\Warehouses\Entities\WareHouses;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class CreditDebitNotesController extends Controller
{
    private $var = 'creditdebitnotes';
    private $instancia = 'detalle_nota_credito_debito';
    private $instancia_carro_procesa = 'detalle_nota_credito_debito_procesa';
    private $ventana = 'MOV_NCREDITOCOMPRA';
    private $provisionesPorPagarProcedure;
    private $creditdebitnotescollection;


    public function __construct(ProvisionesPorPagarProcedure $provisionesPorPagarProcedure, CreditDebitNotesCollection $creditdebitnotescollection)
    {
        $this->obtener_cliente();
        $this->provisionesPorPagarProcedure = $provisionesPorPagarProcedure;
        $this->creditdebitnotescollection = $creditdebitnotescollection;
    }

    private function ejecuta_generactacte($parent, $ventan, $arroba)
    {
        $this->provisionesPorPagarProcedure->generaCtacte($parent, $ventan, $arroba);
    }

    private function ejecuta_cntdocxpagar($parent, $arroba)
    {
        $this->provisionesPorPagarProcedure->cntDocxPagar($parent, $arroba);
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
        $data['view'] = link_view('Compras', 'Transacción', 'Notas de Crédito/Débito', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');
        return view('creditdebitnotes.list', $data);
    }

    public function list()
    {
        $docxpagar = DocumentToPay::lista_nota_credito_debito();
        return DataTables::of($docxpagar)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_carro_procesa)->destroy();

        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['instancia'] = $this->instancia;
        $data['documentosCompra'] = PurchaseDocument::crsdocumentocom($this->ventana);
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['tiposBienServicio'] = GoodsServicesType::all();
        $data['tipocompras'] = PurchasesType::all();
        $data['monedas'] = Currency::all();
        $data['condicionpagos'] = PaymentCondition::all();
        $data['terceros'] = Customer::all();
        $data['date'] = date('Y-m-d');
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['route'] = route('creditdebitnotes');
        $data['view'] = link_view('Compras', 'Transacción', 'Notas de Crédito/Débito', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');
        $data['sucursales'] = Subsidiaries::all();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        return view('creditdebitnotes.create', $data);
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
            $instancia = $this->instancia;
            $ncreditoDebito = new DocumentToPay();
            $ncreditoDebito->periodo_id = Session::get('period_id');
            $ncreditoDebito->numero = $request->number;
            $ncreditoDebito->fechaproceso = $request->processdate;
            $ncreditoDebito->unegocio_id = 1;
            $ncreditoDebito->horaproceso = todaydate();
            $ncreditoDebito->origen = 'P';
            $ncreditoDebito->tercero_id = $request->customer;
            $ncreditoDebito->sucursal_id = 1;
            $ncreditoDebito->tipotransaccion_id = 1;
            $ncreditoDebito->tipocompra_id = $request->purchasetype;
            $ncreditoDebito->moneda_id = $request->currency;
            $ncreditoDebito->tcmoneda = 0.00;
            $ncreditoDebito->documento_id = $request->document;
            $ncreditoDebito->seriedoc = $request->docseries;
            $ncreditoDebito->numerodoc = $request->docnumber;
            $ncreditoDebito->fechadoc = $request->date;
            $ncreditoDebito->tcambio = $request->changerate;
            $ncreditoDebito->condicionpago_id = $request->paymentcondition;
            $ncreditoDebito->vencimiento = $request->date;
            $ncreditoDebito->glosa = $request->comment;
            $ncreditoDebito->impuesto_id = $request->igv;
            $ncreditoDebito->impuesto3_id = $request->rent;
            $ncreditoDebito->impuesto4_id = $request->tasa;
            $ncreditoDebito->tipobienservicio_id = $request->cbo_clasificacion_bien;
            $ncreditoDebito->tipoadq = $request->acquisition;
            $ncreditoDebito->base = $request->base;
            $ncreditoDebito->inafecto = $request->inactive;
            $ncreditoDebito->impuesto = $request->igvtotal;
            $ncreditoDebito->total = $request->total;
            $ncreditoDebito->ventana = $this->ventana;
            $ncreditoDebito->estado = 1;
            $ncreditoDebito->noalmacen = 0;
            $generaKardex = 0;

            $tercero = Customer::findOrFail($request->customer);
            if ($tercero) {
                $ncreditoDebito->razonsocial = $tercero->descripcion;
                $ncreditoDebito->ruc = $tercero->codigo;
            }

            $ncreditoDebito->usuario = Auth::id();

            DB::transaction(function () use ($ncreditoDebito, $request, $generaKardex) {
                $ncreditoDebito->save();
                $this->insertar_detalle($ncreditoDebito->id, $request);
                if ($generaKardex == 1) {//si se da check en GENERA KARDEX, inserta en ingresoAlmacen
                    $this->inserta_ingreso_almacen($ncreditoDebito->id, $request);
                }
                $this->ejecuta_generactacte($ncreditoDebito->id, 'docxpagar', '@a');
                $this->ejecuta_cntdocxpagar($ncreditoDebito->id, '@a');
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = 'crea';

        Cart::instance($instancia)->destroy();

        return $r;
    }

    private function insertar_detalle($parent_id, $request)
    {
        $carts = Cart::instance($this->instancia_carro_procesa)->content();
        foreach ($carts as $carro) {
            $docxpagar_id = $carro->options->has('docxpagar_id') ? $carro->options->docxpagar_id : '';
        }
        $docxpagar_detalle = DocumentToPayDetail::where('parent_id', $docxpagar_id)->first();
        $cont = 1;

        $detalle = new DocumentToPayDetail;
        $detalle->parent_id = $parent_id;
        $detalle->item = $cont;
        $detalle->cuenta_id = $docxpagar_detalle->cuenta_id;
        $detalle->glosa = $docxpagar_detalle->glosa;
        $detalle->importe = $docxpagar_detalle->importe;
        $detalle->usuario = Auth::id();

        DB::transaction(function () use ($detalle, $parent_id, $docxpagar_id, $request) {
            $detalle->save();
            $this->inserta_tbl_referencia($parent_id, $docxpagar_id, $request);
        });
    }

    private function inserta_tbl_referencia($parent_id, $referencia_id, $request)
    {
        $carts = Cart::instance($this->instancia_carro_procesa)->content();

        foreach ($carts as $carro) {
            $referencia = new Reference();
            $referencia->parent_id = $parent_id;
            $referencia->referencia_id = $referencia_id;
            $referencia->aplicar = validaCheck($request->applycheck);
            $referencia->importe = $request->amount;
            $referencia->usuario = Auth::id();

            DB::transaction(function () use ($referencia) {
                $referencia->save();
            });
        }
    }

    private function inserta_ingreso_almacen($docxpagar_id, $request)
    {
        $ingreso = new WarehouseIncome();
        $tipo_cambio = $request->changerate;
        $ingreso->fecha = todaydate();
        $ingreso->unegocio_id = $request->txt_id_unidad_negocio;
        $ingreso->tcambio = $tipo_cambio;
        $ingreso->movimientotipo_id = $request->txt_id_tipo_movimiento;
        $movimiento_tipo = MovementType::findOrFail($request->txt_id_tipo_movimiento);
        $tipo_Mov = $movimiento_tipo->factor;
        if ($tipo_Mov == 1) {
            $ingreso->tipo = "I";
        } else {
            $ingreso->tipo = "S";
        }

        $ingreso->tercero_id = $request->customer;
        $ingreso->moneda_id = $request->currency;

        $moneda = Currency::findOrFail($request->currency);
        $tipo_moneda = $moneda->tipo;
        if ($request->txt_tc == '') {
            $tipo_cambio_moneda = 0.000;
        } else {
            $tipo_cambio_moneda = $request->txt_tc;
        }

        $ingreso->tcmoneda = $tipo_cambio_moneda;
        $ingreso->fechadoc = todaydate();
        $ingreso->glosa = $request->comment;
        $ingreso->almacen_id = $request->txt_id_almacen;

        $almacen = WareHouses::findOrFail($request->txt_id_almacen);
        $ingreso->sucursal_id = $almacen->sucursal_id;
        $ingreso->total = $request->base;
        //CONDICION MONTO TIPO MONEDA
        if ($tipo_moneda == 'N') {
            $ingreso->totalmn = $request->base;
            $ingreso->totalme = ($request->base / $tipo_cambio);
        } elseif ($tipo_moneda == 'E') {
            $ingreso->totalmn = ($request->base * $tipo_cambio);
            $ingreso->totalme = $request->base;
        } else {
            $ingreso->totalmn = ($request->base * $tipo_cambio_moneda);
            $ingreso->totalme = ($request->base * $tipo_cambio_moneda) / $tipo_cambio;
        }

        $ingreso->ventana = 'MOV_INGRESOALMACEN';
        $ingreso->usuario = Auth::id();
        $ingreso->docxpagar_id = $docxpagar_id;

        DB::transaction(function () use ($ingreso) {
            $ingreso->save();
        });
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
        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia_carro_procesa)->destroy();
        $data['proceso'] = 'edita';
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['documentosCompra'] = PurchaseDocument::crsdocumentocom($this->ventana);
        $data['tiposBienServicio'] = GoodsServicesType::all();
        $data['impuestos'] = Taxes::crsimpuesto();
        $data['impuestos2'] = Taxes::crsimpuesto2();
        $data['impuestos3'] = Taxes::crsimpuesto3();
        $data['impuestos4'] = Taxes::crsimpuesto4();
        $data['docxpagar'] = DocumentToPay::findOrFail($id);
        $data['ingresoAlmacen'] = WarehouseIncome::where('docxpagar_id', $id)->first();
        $data['movctactes'] = CurrentAccountMovement::MuestraHistorial($id);
        $data['docxpagarReferencia'] = '';
        $data['referencia'] = Reference::where('parent_id', $id)->first();
        if ($data['referencia']) {
            $data['docxpagarReferencia'] = DocumentToPay::findOrFail($data['referencia']->referencia_id);
        }
        $data['tipocompras'] = PurchasesType::all();
        $data['monedas'] = Currency::all();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['route'] = route('creditdebitnotes');
        $data['terceros'] = Customer::all();
        $data['condicionpagos'] = PaymentCondition::all();

        $this->carga_carros($id, $this->instancia);
        $data['view'] = link_view('Compras', 'Transacción', 'Notas de Crédito/Débito', '');
        $data['sucursales'] = Subsidiaries::all();
        return view('creditdebitnotes.edit', $data);
    }

    private function carga_carros($id, $instancia)
    {
        $docxpagarDetalle = DocumentToPayDetail::where('parent_id', '=', $id)->get();
        foreach ($docxpagarDetalle as $detalle) {

            $lote = $detalle->lote;
            $venc_lote = $detalle->vencimiento;
            $producto_id = $detalle->producto_id;
            if ($producto_id) {
                $producto = Productos::findOrFail($producto_id);
                $producto_id = $producto->id;
                $producto_codigo = $producto->codigo;
                $producto_descripcion = $producto->descripcion;
            } else {
                $producto_id = '';
                $producto_codigo = '';
                $producto_descripcion = '';
            }

            $cuenta_id = $detalle->cuenta_id;
            if ($cuenta_id) {
                $cuenta = AccountingPlan::findOrFail($cuenta_id);
                $cuenta_id = $cuenta->id;
                $cuenta_codigo = $cuenta->codigo;
                $cuenta_descripcion = $cuenta->descripcion;
            } else {
                $cuenta_id = '';
                $cuenta_codigo = '';
                $cuenta_descripcion = '';
            }

            $umedida_id = $detalle->umedida_id;
            if ($umedida_id) {
                $umedida = UnidadesMedida::findOrFail($umedida_id);
                $umedida_id = $umedida->id;
                $umedida_codigo = $umedida->codigo;
                $umedida_descripcion = $umedida->descripcion;
            } else {
                $umedida_id = '';
                $umedida_codigo = '';
                $umedida_descripcion = '';
            }

            $cantidad = $detalle->cantidad;
            $importe = $detalle->importe;

            $cCosto_id = $detalle->centrocosto_id;
            if ($cCosto_id) {
                $cCosto = Costs::findOrFail($cCosto_id);
                $cCosto_id = $cCosto->id;
                $cCosto_codigo = $cCosto->codigo;
                $cCosto_descripcion = $cCosto->descripcion;
            } else {
                $cCosto_id = '';
                $cCosto_codigo = '';
                $cCosto_descripcion = '';
            }

            $actividad_id = $detalle->actividad_id;
            if ($actividad_id) {
                $actividad = Activity::findOrFail($actividad_id);
                $actividad_id = $actividad->id;
                $actividad_codigo = $actividad->codigo;
                $actividad_descripcion = $actividad->descripcion;
            } else {
                $actividad_id = '';
                $actividad_codigo = '';
                $actividad_descripcion = '';
            }

            $proyecto_id = $detalle->proyecto_id;
            if ($proyecto_id) {
                $proyecto = Project::findOrFail($proyecto_id);
                $proyecto_id = $proyecto->id;
                $proyecto_codigo = $proyecto->codigo;
                $proyecto_descripcion = $proyecto->descripcion;
            } else {
                $proyecto_id = '';
                $proyecto_codigo = '';
                $proyecto_descripcion = '';
            }

            Cart::instance($instancia)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $detalle->parent_id,
                    'item' => $detalle->item,
                    'producto_id' => $producto_id,
                    'producto_descripcion' => $producto_descripcion,
                    'producto_codigo' => $producto_codigo,
                    'lote_descripcion' => $lote,
                    'lote_vence' => $venc_lote,
                    'cuenta_id' => $cuenta_id,
                    'cuenta_descripcion' => $cuenta_descripcion,
                    'cuenta_codigo' => $cuenta_codigo,
                    'umedida_id' => $umedida_id,
                    'umedida_descripcion' => $umedida_descripcion,
                    'umedida_codigo' => $umedida_codigo,
                    'cantidad' => $cantidad,
                    'importe' => $importe,
                    'ccosto_id' => $cCosto_id,
                    'ccosto_codigo' => $cCosto_codigo,
                    'ccosto_descripcion' => $cCosto_descripcion,
                    'actividad_id' => $actividad_id,
                    'actividad_codigo' => $actividad_codigo,
                    'actividad_descripcion' => $actividad_descripcion,
                    'proyecto_id' => $proyecto_id,
                    'proyecto_codigo' => $proyecto_codigo,
                    'proyecto_descripcion' => $proyecto_descripcion
                ]
            ]);
        }

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
            $instancia = $request->instancia;
            $ncreditoDebito = DocumentToPay::findOrFail($id);
            $ncreditoDebito->periodo_id = Session::get('period_id');
            $ncreditoDebito->numero = $request->txt_numero;
            $ncreditoDebito->fechaproceso = $request->processdate;
            $ncreditoDebito->unegocio_id = $request->txt_id_unidad_negocio;
            $ncreditoDebito->horaproceso = todaydate();
            $ncreditoDebito->origen = 'P';
            $ncreditoDebito->tercero_id = $request->customer;
            $ncreditoDebito->sucursal_id = $request->txt_id_sucursal;
            $ncreditoDebito->tipotransaccion_id = 1;
            $ncreditoDebito->tipocompra_id = $request->purchasetype;
            $ncreditoDebito->moneda_id = $request->currency;
            $ncreditoDebito->tcmoneda = $request->txt_tc;
            $ncreditoDebito->documento_id = $request->documentref;
            $ncreditoDebito->seriedoc = $request->docseries;
            $ncreditoDebito->numerodoc = $request->docnumber;
            $ncreditoDebito->fechadoc = $request->date;
            $ncreditoDebito->tcambio = $request->changerate;
            $ncreditoDebito->condicionpago_id = $request->paymentcondition;
            $ncreditoDebito->vencimiento = $request->date;
            $ncreditoDebito->glosa = $request->comment;
            $ncreditoDebito->impuesto_id = $request->igv;
            $ncreditoDebito->impuesto3_id = $request->cbo_renta;
            $ncreditoDebito->impuesto4_id = $request->cbo_tasa;
            $ncreditoDebito->tipobienservicio_id = $request->cbo_clasificacion_bien;
            $ncreditoDebito->tipoadq = $request->acquisition;
            $ncreditoDebito->base = $request->base;
            $ncreditoDebito->inafecto = $request->inactive;
            $ncreditoDebito->impuesto = $request->igvtotal;
            $ncreditoDebito->total = $request->total;
            $ncreditoDebito->ventana = $this->ventana;
            $ncreditoDebito->noalmacen = 0;
            $generaKardex = 0;
            $ncreditoDebito->usuario = Auth::id();

            DB::transaction(function () use ($ncreditoDebito, $request, $generaKardex) {
                $ncreditoDebito->save();
                $this->editar_detalle($ncreditoDebito->id, $request);
                if ($generaKardex == 1) {//si se da check en GENERA KARDEX, inserta en ingresoAlmacen
                    $ingreso_id = $request->txt_ingreso_almacen;
                    $this->editar_ingreso_almacen($ncreditoDebito->id, $ingreso_id, $request);
                }

                $this->ejecuta_generactacte($ncreditoDebito->id, 'docxpagar', '@a');
                $this->ejecuta_cntdocxpagar($ncreditoDebito->id, '@a');
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        Cart::instance($instancia)->destroy();

        return $r;
    }

    private function editar_detalle($parent_id, $request)
    {
        $docxpagar_id = $request->documentref;
        $docxpagar_detalle = DocumentToPayDetail::where('parent_id', $docxpagar_id)->first();
        $cont = 1;
        $idCantidad = Reference::idCantidadRefactorizado($parent_id, $docxpagar_id);

        if ($idCantidad->idCantidad == 0) {
            $detalle = new DocumentToPayDetail();
            $detalle->parent_id = $parent_id;
            $detalle->item = $cont;
            $detalle->cuenta_id = $docxpagar_detalle->cuenta_id;
            $detalle->glosa = $docxpagar_detalle->glosa;
            $detalle->importe = $docxpagar_detalle->importe;
            $detalle->usuario = Auth::id();

            DB::transaction(function () use ($detalle, $parent_id, $docxpagar_id, $request) {
                $detalle->save();
                $this->inserta_tbl_referencia($parent_id, $docxpagar_id, $request);
            });

        } else {
            $data = array(
                'cuenta_id' => $docxpagar_detalle->cuenta_id,
                'glosa' => $docxpagar_detalle->glosa,
                'importe' => $docxpagar_detalle->importe,
                'usuario' => Auth::id(),
            );

            DocumentToPayDetail::editarRefactorizado($parent_id, 1, $data);
            $this->editar_tbl_referencia($parent_id, $docxpagar_id, $request);
        }
    }

    private function editar_tbl_referencia($parent_id, $referencia_id, $request)
    {
        $data = array(
            'aplicar' => $request->chkaplicar,
            'importe' => $request->txt_importe_ref,
            'usuario' => Auth::id(),
        );

        Reference::editarReferencia($parent_id, $referencia_id, $data);
    }

    private function editar_ingreso_almacen($docxpagar_id, $ingreso_id, $request)
    {
        $tipo_cambio = $request->changerate;
        if (!$ingreso_id) {
            $ingreso = new WarehouseIncome();
            $ingreso->fecha = todaydate();
            $ingreso->unegocio_id = $request->txt_id_unidad_negocio;
            $ingreso->tcambio = $request->txt_tcambio_doc;
            $ingreso->movimientotipo_id = $request->txt_id_tipo_movimiento;
            $movimiento_tipo = MovementType::findOrFail($request->txt_id_tipo_movimiento);
            $tipo_Mov = $movimiento_tipo->factor;
            if ($tipo_Mov == 1) {
                $ingreso->tipo = "I";
            } else {
                $ingreso->tipo = "S";
            }
            $ingreso->tercero_id = $request->txt_id_razon_soc;
            $ingreso->moneda_id = $request->txt_id_moneda;

            $moneda = Currency::findOrFail($request->txt_id_moneda);
            $tipo_moneda = $moneda->tipo;
            if ($request->txt_tc == '') {
                $tipo_cambio_moneda = 0.000;
            } else {
                $tipo_cambio_moneda = $request->txt_tc;
            }
            $ingreso->tcmoneda = $tipo_cambio_moneda;
            $ingreso->fechadoc = todaydate();
            $ingreso->glosa = $request->txt_glosa;
            $ingreso->almacen_id = $request->txt_id_almacen;
            $almacen = WareHouses::findOrFail($request->txt_id_almacen);
            $ingreso->sucursal_id = $almacen->sucursal_id;
            $ingreso->total = $request->txt_afecto;
            //CONDICION MONTO TIPO MONEDA
            if ($tipo_moneda == 'N') {
                $ingreso->totalmn = $request->txt_afecto;
                $ingreso->totalme = ($request->txt_afecto / $tipo_cambio);
            } elseif ($tipo_moneda == 'E') {
                $ingreso->totalmn = ($request->txt_afecto * $tipo_cambio);
                $ingreso->totalme = $request->txt_afecto;
            } else {
                $ingreso->totalmn = ($request->txt_afecto * $tipo_cambio_moneda);
                $ingreso->totalme = ($request->txt_afecto * $tipo_cambio_moneda) / $tipo_cambio;
            }
            $ingreso->usuario = Auth::id();
            $ingreso->docxpagar_id = $docxpagar_id;

            DB::transaction(function () use ($ingreso) {
                $ingreso->save();
            });

        } else {
            //EDITA INGREOS ALMACEN
            $ingreso = WarehouseIncome::findOrFail($ingreso_id);
            $ingreso->fecha = todaydate();
            $ingreso->unegocio_id = $request->txt_id_unidad_negocio;
            $ingreso->tcambio = $request->txt_tcambio_doc;
            $ingreso->movimientotipo_id = $request->txt_id_tipo_movimiento;
            $movimiento_tipo = MovementType::findOrFail($request->txt_id_tipo_movimiento);
            $tipo_Mov = $movimiento_tipo->factor;
            if ($tipo_Mov == 1) {
                $ingreso->tipo = "I";
            } else {
                $ingreso->tipo = "S";
            }
            $ingreso->tercero_id = $request->txt_id_razon_soc;
            $ingreso->moneda_id = $request->txt_id_moneda;

            $moneda = Currency::findOrFail($request->txt_id_moneda);
            $tipo_moneda = $moneda->tipo;
            if ($request->txt_tc == '') {
                $tipo_cambio_moneda = 0.000;
            } else {
                $tipo_cambio_moneda = $request->txt_tc;
            }
            $ingreso->tcmoneda = $tipo_cambio_moneda;

            $ingreso->fechadoc = todaydate();
            $ingreso->glosa = $request->txt_glosa;
            $ingreso->almacen_id = $request->txt_id_almacen;

            $almacen = WareHouses::findOrFail($request->txt_id_almacen);
            $ingreso->sucursal_id = $almacen->sucursal_id;
            $ingreso->total = $request->txt_afecto;
            //CONDICION MONTO TIPO MONEDA
            if ($tipo_moneda == 'N') {
                $ingreso->totalmn = $request->txt_afecto;
                $ingreso->totalme = ($request->txt_afecto / $tipo_cambio);
            } elseif ($tipo_moneda == 'E') {
                $ingreso->totalmn = ($request->txt_afecto * $tipo_cambio);
                $ingreso->totalme = $request->txt_afecto;
            } else {
                $ingreso->totalmn = ($request->txt_afecto * $tipo_cambio_moneda);
                $ingreso->totalme = ($request->txt_afecto * $tipo_cambio_moneda) / $tipo_cambio;
            }
            $ingreso->usuario = Auth::id();

            DB::transaction(function () use ($ingreso) {
                $ingreso->save();
            });
        }
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

    public function obtener_referencia_ncd(Request $request)
    {
        $resultados = DocumentToPay::filtrar_referencia_ncd($request->customer, $request->date_modal);
        $this->creditdebitnotescollection->actions($resultados, route('list.referencesdetails'));
        return DataTables::of($resultados)->rawColumns(['select'])->make();
    }

    public function obtener_referenciadetalle_ncd(Request $request)
    {
        $resultados = DocumentToPay::filtrar_referenciadetalle_ncd($request->id_docxpagar);
        $this->creditdebitnotescollection->actionsdetails($resultados);
        return DataTables::of($resultados)->rawColumns(['select', 'aplicar'])->make();
    }

    public function agregar_carro_nota_credito_referencia(Request $request)
    {
        $instancia = $this->instancia_carro_procesa;
        Cart::instance($instancia)->destroy();

        $id_docxpagar = $request->id_docxpagar;
        $aplicar = $request->apply;

        Cart::instance($instancia)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'docxpagar_id' => $id_docxpagar,
                'aplicar' => $aplicar,
            ]
        ]);

        $r["estado"] = "ok";
        $r["instancia"] = $instancia;

        return $r;
    }

    public function total_instancia_change(Request $request)
    {
        $afecto = $request->base;
        $inafecto = $request->inactive;
        $id_percepcion = $request->igv;
        $tributo_percepcion = Taxes::findOrFail($id_percepcion);
        $impuesto = $afecto * ($tributo_percepcion->valor / 100);
        $total = $afecto + $inafecto + $impuesto;

        $r['impuesto'] = $impuesto;
        $r['total'] = $total;

        return $r;
    }

    public function obtener_datos_docxpagar()
    {
        $docxpagar_id = null;
        $instancia = $this->instancia_carro_procesa;
        $carts = Cart::instance($instancia)->content();

        foreach ($carts as $cart) {
            $docxpagar_id = $cart->options->has('docxpagar_id') ? $cart->options->docxpagar_id : '';
        }

        $docxpagar = DocumentToPay::findOrFail($docxpagar_id);
        $r["documento"] = $docxpagar->documentocom['codigo'] . ' ' . $docxpagar->seriedoc . '-' . $docxpagar->numerodoc;
        $r["docxpagar_id"] = $docxpagar_id;
        $r["estado"] = "ok";
        $r["instancia"] = $instancia;

        return $r;
    }

    public function customerdata(Request $request)
    {
        $tercero = Customer::findOrFail($request->customer);
        $data['tercero'] = $tercero;
        return $data;
    }

    public function verifica_documento_registrado(Request $request)
    {
        $tercero_id = $request->customer;
        $seriedoc = $request->docseries;
        $documento_id = $request->document;
        $origen = $request->origen;
        $numero = $request->numero;
        $numero = DocumentToPay::verifica_documento_registro($tercero_id, $seriedoc, $documento_id, $origen, $numero);
        return $numero;
    }

    public function activar_anular_creditdebitnote(Request $request, $id)
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
}
