<?php

namespace App\Http\Controllers;

use App\AccountingNote\Collections\AccountingNoteCollection;
use App\AccountingNote\Entities\AccountingNote;
use App\AccountingNote\Entities\AccountingNoteDetail;
use App\BusinessUnit\Entities\BusinessUnit;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\Customers\Entities\Customer;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ContabilidadProcedure;
use App\Subdiaries\Entities\Subdiaries;
use App\Subsidiaries\Entities\Subsidiaries;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class DailySeatController extends Controller
{
    use CartTrait, UserPrivileges;
    protected $var = 'dailyseat';
    protected $instancia = 'detalle_asientodiario';
    private $ventana = 'MOV_NOTACONTABLE_APLIC';
    protected $user;
    protected $privilegios = '06.02.02';
    private $contabilidadprocedure;

    public function __construct(ContabilidadProcedure $contabilidadprocedure)
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
        $this->contabilidadprocedure = $contabilidadprocedure;
    }

    public function list()
    {
       // $seats = AccountingNote::lista_nota_contable_diario();
        $seats = AccountingNote::listNotas((Session::get('period_id')));
        return DataTables::of($seats)->make();
    }

    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Diario Manual','');
        $data['header'] = headeroptions($this->var, 'list', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('dailyseat.list', $data);
    }


    public function create()
    {
        $data['var'] = $this->var;
        $data['sucursales'] = Subsidiaries::all();
        $data['unidades'] = BusinessUnit::all();
        $data['monedas'] = Currency::all();
        $data['terceros'] = Customer::all();
        $data['costos'] = Costs::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['subdiarios'] = Subdiaries::where('apertura', 0)->where('cierre', 0)->get();
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['header'] = headeroptions($this->var, 'crea', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Diario Manual','');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);

        return view('dailyseat.create', $data);
    }

    public function edit(Request $request, $id)
    {

        $asiento = AccountingNote::findOrFail($id);

        $asientoDetalle = AccountingNoteDetail::data($id);

        $data['proceso'] = 'edit';
        $data['asiento'] = $asiento;

        $data['sucursales'] = Subsidiaries::all();
        $data['unidades'] = BusinessUnit::all();
        $data['monedas'] = Currency::all();
        $data['terceros'] = Customer::all();
        $data['costos'] = Costs::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['subdiarios'] = Subdiaries::where('apertura', 0)->where('cierre', 0)->get();

        $data['header'] = headeroptions($this->var, 'edita', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['view'] = link_view('Almacenes', 'Transacción', 'Pedido al Almacén', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);  //evito poner la instancia en el trait

        $this->llenarCarritoAsientoDiario($asientoDetalle);

        return view('dailyseat.edit', $data);

    }

    public function show(Request $request, $id){

        $asiento = AccountingNote::findOrFail($id);

        $asientoDetalle = AccountingNoteDetail::data($id);

        $data['proceso'] = 'edit';
        $data['asiento'] = $asiento;

        $data['sucursales'] = Subsidiaries::all();
        $data['unidades'] = BusinessUnit::all();
        $data['monedas'] = Currency::all();
        $data['terceros'] = Customer::all();
        $data['costos'] = Costs::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['subdiarios'] = Subdiaries::where('apertura', 0)->where('cierre', 0)->get();
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['header'] = headeroptions($this->var, 'show', '', '',  $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Diario', '');

        Cart::instance($this->instancia)->destroy();
        Cart::instance($this->instancia);  //evito poner la instancia en el trait

        $this->llenarCarritoAsientoDiario($asientoDetalle);

        return view('dailyseat.edit', $data);
    }

    public function listar_carrito()
    {
        return $this->lista($this->instancia);
    }













    public function listar_detalle(Request $request)
    {
        $data['proceso'] = $request->proceso;
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['carts'] = Cart::instance($data['instancia'])->content();
        $data['total'] = Cart::instance($data['instancia'])->count();
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Diario Manual','');

        return view('dailyseat.' . $data['instancia'] . '', $data);
    }

    public function totalizar()
    {
        $totcargomn = 0;
        $totcargome = 0;
        $totabonomn = 0;
        $totabonome = 0;
        $carts = Cart::instance($this->instancia)->content();

        foreach ($carts as $cart) {
            $cargomn1 = $cart->options->has('cargomn') ? $cart->options->cargomn : '';
            $totcargomn = $totcargomn + $cargomn1;
            $cargome1 = $cart->options->has('cargome') ? $cart->options->cargome : '';
            $totcargome = $totcargome + $cargome1;
            $abonomn1 = $cart->options->has('abonomn') ? $cart->options->abonomn : '';
            $totabonomn = $totabonomn + $abonomn1;
            $abonome1 = $cart->options->has('abonome') ? $cart->options->abonome : '';
            $totabonome = $totabonome + $abonome1;
        }

        $r["totcargomn"] = $totcargomn;
        $r["totcargome"] = $totcargome;
        $r["totabonomn"] = $totabonomn;
        $r["totabonome"] = $totabonome;

        return $r;
    }

    public function agregar(Request $request)
    {
        $item = $request->txt_auxiliar;
        $parent_id = $request->txt_aux_id;
        $id_tercero = $request->txt_id_cliente;
        if ($id_tercero != null) {
            $tercero = Customer::findOrFail($id_tercero);
            $tercero_codigo = $tercero->codigo;
            $tercero_descripcion = $tercero->descripcion;
        } else {
            $tercero_codigo = '';
            $tercero_descripcion = '';
        }
        $referencia = $request->txt_referencia;
        $referencia_id = $request->txt_id_referencia;
        $cargomn = $request->txt_cargomn;
        $cargome = $request->txt_cargome;
        $abonomn = $request->txt_abonomn;
        $abonome = $request->txt_abonome;
        $id_cuenta = $request->txt_id_cuenta;
        if ($id_cuenta != '') {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }
        $id_costo = $request->txt_id_centro_costo;
        if ($id_costo != null) {
            $costo = Costs::findOrFail($id_costo);
            $costo_codigo = $costo->codigo;
            $costo_descripcion = $costo->descripcion;
        } else {
            $costo_codigo = '';
            $costo_descripcion = '';
        }

        Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $parent_id,
                'item' => $item,
                'tercero_id' => $id_tercero,
                'tercero_codigo' => $tercero_codigo,
                'tercero_descripcion' => $tercero_descripcion,
                'referencia' => $referencia,
                'referencia_id' => $referencia_id,
                'esautomatica' => 0,
                'cargomn' => $cargomn,
                'cargome' => $cargome,
                'abonomn' => $abonomn,
                'abonome' => $abonome,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'costo_id' => $id_costo,
                'costo_codigo' => $costo_codigo,
                'costo_descripcion' => $costo_descripcion,
            ]]);

        return $this->instancia;
    }

    public function editar_detalle(Request $request)
    {
        $id_carrito = $request->txt_id_modelo_modal;
        $item = $request->txt_auxiliar;
        $parent_id = $request->txt_aux_id;
        $id_tercero = $request->customer;

        if ($id_tercero != null) {
            $tercero = Customer::findOrFail($id_tercero);
            $tercero_codigo = $tercero->codigo;
            $tercero_descripcion = $tercero->descripcion;
        } else {
            $tercero_codigo = '';
            $tercero_descripcion = '';
        }

        $referencia = $request->txt_referencia;
        $referencia_id = $request->txt_id_referencia;
        $cargomn = $request->txt_cargomn;
        $cargome = $request->txt_cargome;
        $abonomn = $request->txt_abonomn;
        $abonome = $request->txt_abonome;
        $id_cuenta = $request->txt_id_cuenta;

        if ($id_cuenta != null) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }

        $id_costo = $request->txt_id_centro_costo;
        if ($id_costo != null) {
            $costo = Costs::findOrFail($id_costo);
            $costo_codigo = $costo->codigo;
            $costo_descripcion = $costo->descripcion;
        } else {
            $costo_codigo = '';
            $costo_descripcion = '';
        }

        Cart::instance($this->instancia)->update($id_carrito, [
            'options' => [
                'parent_id' => $parent_id,
                'item' => $item,
                'tercero_id' => $id_tercero,
                'tercero_codigo' => $tercero_codigo,
                'tercero_descripcion' => $tercero_descripcion,
                'referencia' => $referencia,
                'referencia_id' => $referencia_id,
                'esautomatica' => 0,
                'cargomn' => $cargomn,
                'cargome' => $cargome,
                'abonomn' => $abonomn,
                'abonome' => $abonome,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'costo_id' => $id_costo,
                'costo_codigo' => $costo_codigo,
                'costo_descripcion' => $costo_descripcion,
            ]]);

        $data["estado"] = "ok";
        $data["instancia"] = $this->instancia;

        return $data;
    }


    public function store(Request $request)
    {
        try {
            $notacontable = new AccountingNote();
            $notacontable->periodo_id = Session::get('period_id');
            $notacontable->sucursal_id = 1;
            $notacontable->subdiario_id = $request->subdiary;
            $notacontable->fechaproceso = $request->processdate;
            $notacontable->unegocio_id = 1;
            $notacontable->tcambio = $request->changerate;
            $notacontable->moneda_id = $request->currency;
            $moneda = Currency::findorfail($request->currency);
            if ($moneda->tipo = 'N') {
                if ($request->totalme != 0) {
                    $this->genera_ajuste_difcambio_dolares($request->totalme, $this->instancia, $request);
                }
            } else {
                if ($request->totalmn != 0) {
                    $this->genera_ajuste_difcambio_soles($request->totalmn, $this->instancia, $request);
                }
            }
            $notacontable->glosa = $request->comment;
            $notacontable->estado = 1;
            $notacontable->ventana = $this->ventana;
            $notacontable->usuario = Auth::id();

            $carts = Cart::instance($this->instancia)->content();
            $totcargomn = 0;
            $totcargome = 0;
            foreach ($carts as $cart) {
                $cargomn1 = $cart->options->has('cargomn') ? $cart->options->cargomn : '';
                $totcargomn = $totcargomn + $cargomn1;
                $cargome1 = $cart->options->has('cargome') ? $cart->options->cargome : '';
                $totcargome = $totcargome + $cargome1;
            }
            $notacontable->totalmn = $totcargomn;
            $notacontable->totalme = $totcargome;

            DB::transaction(function () use ($notacontable) {
                $notacontable->save();
                $this->insertar_detalle_aplicaciones($notacontable->id, $this->instancia);
                $this->contabiliza_notacontable($notacontable->id);
                $this->centraliza_notacontable($notacontable->id);
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;
        Cart::instance($this->instancia)->destroy();

        return $r;
    }

    private function genera_ajuste_difcambio_dolares($monto, $instancia, $request)
    {
        if ($request->txt_total_cargome > $request->txt_total_abonome) {
            $cargomn = 0;
            $cargome = 0;
            $abonomn = 0;
            $abonome = $monto;

            $pempresa = validapempresa('DIF_CTAGAN', 'CONTAB', 'Cuenta para Ganancia por Diferencia de Cambio', '');
            $id_cuenta = $pempresa->valor;
            $id_costo = '';
        } else {
            $cargomn = 0;
            $cargome = $monto;
            $abonomn = 0;
            $abonome = 0;

            $pempresa = validapempresa('DIF_CTAPER', 'CONTAB', 'Cuenta para Pérdida por Diferencia de Cambio', '');
            $id_cuenta = $pempresa->valor;
            $pempresa = validapempresa('DIF_CCOSTO', 'CONTAB', 'Centro de Costo para Pérdida por Diferencia de Cambio', '');
            $id_costo = $pempresa->valor;
        }

        if ($id_cuenta != null) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }

        if ($id_costo != null) {
            $costo = Costs::findOrFail($id_costo);
            $costo_codigo = $costo->codigo;
            $costo_descripcion = $costo->descripcion;
        } else {
            $costo_codigo = '';
            $costo_descripcion = '';
        }

        Cart::instance($instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => '',
                'item' => '',
                'tercero_id' => null,
                'tercero_codigo' => '',
                'tercero_descripcion' => '',
                'referencia' => '',
                'referencia_id' => null,
                'esautomatica' => 2,
                'cargomn' => $cargomn,
                'cargome' => $cargome,
                'abonomn' => $abonomn,
                'abonome' => $abonome,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'costo_id' => $id_costo,
                'costo_codigo' => $costo_codigo,
                'costo_descripcion' => $costo_descripcion,
            ]]);
    }

    private function genera_ajuste_difcambio_soles($monto, $instancia, $request)
    {
        if ($request->txt_total_cargomn > $request->txt_total_abonomn) {
            //ganancia
            $cargomn = 0;
            $cargome = 0;
            $abonomn = $monto;
            $abonome = 0;

            $pempresa = validapempresa('DIF_CTAGAN', 'CONTAB', 'Cuenta para Ganancia por Diferencia de Cambio', '');
            $id_cuenta = $pempresa->valor;
            $id_costo = '';
        } else {
            //perdida
            $cargomn = $monto;
            $cargome = 0;
            $abonomn = 0;
            $abonome = 0;

            $pempresa = validapempresa('DIF_CTAPER', 'CONTAB', 'Cuenta para Pérdida por Diferencia de Cambio', '');
            $id_cuenta = $pempresa->valor;
            $pempresa = validapempresa('DIF_CCOSTO', 'CONTAB', 'Centro de Costo para Pérdida por Diferencia de Cambio', '');
            $id_costo = $pempresa->valor;
        }

        if ($id_cuenta != null) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }

        if ($id_costo != null) {
            $costo = Costs::findOrFail($id_costo);
            $costo_codigo = $costo->codigo;
            $costo_descripcion = $costo->descripcion;
        } else {
            $costo_codigo = '';
            $costo_descripcion = '';
        }

        Cart::instance($instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => '',
                'item' => '',
                'tercero_id' => null,
                'tercero_codigo' => '',
                'tercero_descripcion' => '',
                'referencia' => '',
                'referencia_id' => null,
                'esautomatica' => 2,
                'cargomn' => $cargomn,
                'cargome' => $cargome,
                'abonomn' => $abonomn,
                'abonome' => $abonome,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'costo_id' => $id_costo,
                'costo_codigo' => $costo_codigo,
                'costo_descripcion' => $costo_descripcion,
            ]]);
    }

    public function insertar_detalle_aplicaciones($id_aplicacion, $detalle_aplicaciones)
    {
        $carts = Cart::instance($detalle_aplicaciones)->content();
        $item = 1;

        foreach ($carts as $detalle) {
            $notacontable_detalle = new AccountingNoteDetail();
            $notacontable_detalle->parent_id = $id_aplicacion;
            $notacontable_detalle->item = $item;
            $notacontable_detalle->cuenta_id = $detalle->options->has('cuenta_id') ? $detalle->options->cuenta_id : '';
            $notacontable_detalle->centrocosto_id = $detalle->options->has('costo_id') ? $detalle->options->costo_id : '';
            $notacontable_detalle->tercero_id = $detalle->options->has('tercero_id') ? $detalle->options->tercero_id : '';
            $notacontable_detalle->referencia_id = $detalle->options->has('referencia_id') ? $detalle->options->referencia_id : '';
            $notacontable_detalle->esautomatica = $detalle->options->has('esautomatica') ? $detalle->options->esautomatica : '';
            $notacontable_detalle->cargomn = $detalle->options->has('cargomn') ? $detalle->options->cargomn : '';
            $notacontable_detalle->abonomn = $detalle->options->has('abonomn') ? $detalle->options->abonomn : '';
            $notacontable_detalle->cargome = $detalle->options->has('cargome') ? $detalle->options->cargome : '';
            $notacontable_detalle->abonome = $detalle->options->has('abonome') ? $detalle->options->abonome : '';
            $notacontable_detalle->usuario = Auth::id();

            DB::transaction(function () use ($notacontable_detalle) {
                $notacontable_detalle->save();
            });

            $item++;
        };
    }

    private function contabiliza_notacontable($id_notacontable)
    {
        $this->contabilidadprocedure->generaCtacte($id_notacontable, 'notacontable', '@pRet');
    }

    private function centraliza_notacontable($id_notacontable)
    {
        $this->contabilidadprocedure->cntEsAutomatica($id_notacontable, '@pRet');
    }



    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function prueba()
    {
        return view('prueba.prueba');

    }
}
