<?php

namespace App\Http\Controllers;

use App\AccountingNote\Collections\AccountingNoteCollection;
use App\AccountingNote\Entities\AccountingNote;
use App\AccountingNote\Entities\AccountingNoteDetail;
use App\AccountingNote\Entities\AccountingNoteOpening;
use App\Currency\Entities\Currency;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Period\Entities\Period;
use App\Subdiaries\Entities\Subdiaries;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;

class OpeningSeatController extends Controller
{
    protected $var = 'openingseat';
    private $openingseatcollection;
    private $ventana = 'MOV_APERTURACONTABLE';
    protected $instancia = 'detalle_asiento_apertura';

    public function __construct(AccountingNoteCollection $openingseatcollection)
    {
        $this->obtener_cliente();
        $this->openingseatcollection = $openingseatcollection;
    }

    public function list()
    {
        $seats = AccountingNote::lista_nota_contable($this->ventana);
        $this->openingseatcollection->actions($seats, $this->var);
        return DataTables::of($seats)->rawColumns(['estado', 'actions'])->make();
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
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento de Apertura','');
        $data['header'] = headeroptions($this->var, 'list');
        return view('openingseat.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data['proceso'] = 'crea';
        $data['var'] = $this->var;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['instancia'] = $this->instancia;
        $data['subdiarios'] = Subdiaries::where('apertura', 1)->get();
        $data['currencies'] = Currency::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        Cart::instance($this->instancia)->destroy();
        $data['view'] = $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento de Apertura','');
        $data['header'] = headeroptions($this->var, 'crea');
        return view('openingseat.create', $data);
    }

    public function listar_detalle(Request $request)
    {
        $data['proceso'] = $request->proceso;
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['carts'] = Cart::instance($data['instancia'])->content();
        $data['total'] = Cart::instance($data['instancia'])->count();
        $data['view'] = $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento de Apertura','');

        return view('openingseat.' . $data['instancia'] . '', $data);
    }

    public function agregar(Request $request)
    {
        $item = $request->txt_auxiliar;
        $parent_id = $request->txt_aux_id;
        $id_cuenta = $request->account;

        if ($id_cuenta) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }

        Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $parent_id,
                'item' => $item,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'cargomn' => $request->cargomn,
                'cargome' => $request->cargome,
                'abonomn' => $request->abonomn,
                'abonome' => $request->abonome,
                'estado' => 1,
            ]]);

        return $this->instancia;
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
            $totcargomn = (float)$totcargomn + (float)$cargomn1;
            $cargome1 = $cart->options->has('cargome') ? $cart->options->cargome : '';
            $totcargome = (float)$totcargome + (float)$cargome1;
            $abonomn1 = $cart->options->has('abonomn') ? $cart->options->abonomn : '';
            $totabonomn = (float)$totabonomn + (float)$abonomn1;
            $abonome1 = $cart->options->has('abonome') ? $cart->options->abonome : '';
            $totabonome = (float)$totabonome + (float)$abonome1;
        }

        $r["totcargomn"] = $totcargomn;
        $r["totcargome"] = $totcargome;
        $r["totabonomn"] = $totabonomn;
        $r["totabonome"] = $totabonome;

        return $r;
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
            $asientoapertura = new AccountingNote();
            $asientoapertura->sucursal_id = 1;
            $asientoapertura->periodo_id = Session::get('period_id');
            $asientoapertura->subdiario_id = $request->subdiary;
            $asientoapertura->fechaproceso = $request->processdate;
            $asientoapertura->tcambio = $request->changerate;
            $asientoapertura->moneda_id = $request->currency;
            $asientoapertura->totalmn = $request->totalmn;
            $asientoapertura->totalme = $request->totalme;
            $asientoapertura->glosa = $request->comment;
            $asientoapertura->estado = 1;
            $asientoapertura->usuario = Auth::id();
            $asientoapertura->ventana = $this->ventana;

            DB::transaction(function () use ($asientoapertura) {
                $asientoapertura->save();
                $this->insertar_detalle_asiento_apertura($asientoapertura->id, $this->instancia);
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

    public function insertar_detalle_asiento_apertura($id_apertura, $instancia)
    {
        $carts = Cart::instance($instancia)->content();
        $item = 1;

        foreach ($carts as $detalle) {
            $notacontable_detalle = new AccountingNoteDetail();
            $notacontable_detalle->parent_id = $id_apertura;
            $notacontable_detalle->item = $item;
            $notacontable_detalle->cuenta_id = $detalle->options->has('cuenta_id') ? $detalle->options->cuenta_id : '';
            $notacontable_detalle->glosa = $detalle->options->has('cuenta_descripcion') ? $detalle->options->cuenta_descripcion : '';
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

    public function ver_detalle(Request $request)
    {
        $cart = Cart::instance($request->instancia)->get($request->id);
        if ($cart) {
            $data["estado"] = "ok";
            $data["id"] = $request->id;
            $data["id_detalle"] = $request->id_detalle;
            $data["id_parent"] = $request->id_parent;;
            $data["cuenta_id"] = $cart->options->has('cuenta_id') ? $cart->options->cuenta_id : '';
            $data["cuenta_codigo"] = $cart->options->has('cuenta_codigo') ? $cart->options->cuenta_codigo : '';
            $data["cuenta_descripcion"] = $cart->options->has('cuenta_descripcion') ? $cart->options->cuenta_descripcion : '';
            $data["cargomn"] = $cart->options->has('cargomn') ? $cart->options->cargomn : '';
            $data["cargome"] = $cart->options->has('cargome') ? $cart->options->cargome : '';
            $data["abonomn"] = $cart->options->has('abonomn') ? $cart->options->abonomn : '';
            $data["abonome"] = $cart->options->has('abonome') ? $cart->options->abonome : '';
            $data["state"] = $cart->options->has('estado') ? $cart->options->estado : '';

        } else {
            $data["estado"] = "Ocurrió un error inesperado";
        }

        return $data;
    }

    public function borrar_item(Request $request)
    {
        $idCantidad = AccountingNoteDetail::idCantidad($request->id_detalle, $request->parent_id);
        if ($idCantidad->idCantidad == 0) {
            Cart::instance($request->instancia)->remove($request->id_tmp);
        } else {
            Cart::instance($request->instancia)->remove($request->id_tmp);
            AccountingNoteDetail::where(['parent_id' => $request->parent_id, 'item' => $request->id_detalle])->delete();
            AccountingNoteOpening::where(['parent_id' => $request->parent_id, 'cuenta_id' => $request->cuenta_id])->delete();
        }

        return $request->instancia;
    }

    public function editar_detalle(Request $request)
    {
        $id_carrito = $request->txt_id_modelo_modal;
        $item = $request->txt_auxiliar;
        $parent_id = $request->txt_aux_id;
        $id_cuenta = $request->account;
        $estado = $request->estado_modal;

        if ($id_cuenta != null) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }

        Cart::instance($this->instancia)->updupdate($id_carrito, [
            'options' => [
                'parent_id' => $parent_id,
                'item' => $item,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'cargomn' => $request->cargomn,
                'cargome' => $request->cargome,
                'abonomn' => $request->abonomn,
                'abonome' => $request->abonome,
                'estado' => $estado,
            ]]);

        $data["estado"] = "ok";
        $data["instancia"] = $this->instancia;
        return $data;
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
        $data['asientoapertura'] = AccountingNote::findOrFail($id);
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['subdiarios'] = Subdiaries::where('apertura', 1)->get();
        $data['currencies'] = Currency::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $data['instancia'] = $this->instancia;
        $this->carga_carros($id, $this->instancia);
        $data['view'] = $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento de Apertura','');
        $data['header'] = headeroptions($this->var, 'edita');
        return view('openingseat.edit', $data);
    }

    private function carga_carros($parent_id, $instancia)
    {
        Cart::instance($this->instancia)->destroy();

        $detalles = AccountingNoteDetail::where('parent_id', $parent_id)->get();

        foreach ($detalles as $detalle) {
            Cart::instance($instancia)->add(['id' => 1, 'name' => 'nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'parent_id' => $parent_id,
                    'item' => $detalle->item,
                    'cargomn' => $detalle->cargomn,
                    'cargome' => $detalle->cargome,
                    'abonomn' => $detalle->abonomn,
                    'abonome' => $detalle->abonome,
                    'cuenta_id' => $detalle->cuenta['id'],
                    'cuenta_codigo' => $detalle->cuenta['codigo'],
                    'cuenta_descripcion' => $detalle->cuenta['descripcion'],
                    'estado' => 1,
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
            $asientoapertura = AccountingNote::findOrFail($id);
            $asientoapertura->sucursal_id = 1;
            $asientoapertura->periodo_id = Session::get('period_id');
            $asientoapertura->subdiario_id = $request->subdiary;
            $asientoapertura->fechaproceso = $request->processdate;
            $asientoapertura->tcambio = $request->changerate;
            $asientoapertura->moneda_id = $request->currency;
            $asientoapertura->totalmn = $request->totalmn;
            $asientoapertura->totalme = $request->totalme;
            $asientoapertura->glosa = $request->comment;
            $asientoapertura->usuario = Auth::id();
            $asientoapertura->ventana = $this->ventana;

            DB::transaction(function () use ($asientoapertura) {
                $asientoapertura->save();
                $this->editar_detalle_asiento_apertura($asientoapertura->id, $this->instancia);
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

    public function editar_detalle_asiento_apertura($id_apertura, $detalle_asiento_apertura)
    {
        $carts = Cart::instance($detalle_asiento_apertura)->content();

        foreach ($carts as $detalle) {
            $parent_id = $id_apertura;
            $item = $detalle->options->has('item') ? $detalle->options->item : '';
            $cuenta_id = $detalle->options->has('cuenta_id') ? $detalle->options->cuenta_id : '';
            $glosa = $detalle->options->has('cuenta_descripcion') ? $detalle->options->cuenta_descripcion : '';
            $cargomn = $detalle->options->has('cargomn') ? $detalle->options->cargomn : '';
            $cargome = $detalle->options->has('cargome') ? $detalle->options->cargome : '';
            $abonomn = $detalle->options->has('abonomn') ? $detalle->options->abonomn : '';
            $abonome = $detalle->options->has('abonome') ? $detalle->options->abonome : '';

            $idCantidad = AccountingNoteDetail::idCantidad($item, $id_apertura);
            //ultimo item
            $ultimoItem = AccountingNoteDetail::ultimoItem($id_apertura);
            if ($ultimoItem) {
                $ultimo = $ultimoItem->item + 1;
            } else {
                $ultimo = 1;
            }

            if ($idCantidad->idCantidad == 0) {
                $movimiento_caja_detalle = new AccountingNoteDetail;
                $movimiento_caja_detalle->parent_id = $parent_id;
                $movimiento_caja_detalle->item = $ultimo;
                $movimiento_caja_detalle->cuenta_id = $cuenta_id;
                $movimiento_caja_detalle->glosa = $glosa;
                $movimiento_caja_detalle->cargomn = $cargomn;
                $movimiento_caja_detalle->cargome = $cargome;
                $movimiento_caja_detalle->abonomn = $abonomn;
                $movimiento_caja_detalle->abonome = $abonome;
                $movimiento_caja_detalle->usuario = Auth::id();
                $movimiento_caja_detalle->save();
            } else {
                $data = array(
                    'cuenta_id' => $cuenta_id,
                    'glosa' => $glosa,
                    'cargomn' => $cargomn,
                    'cargome' => $cargome,
                    'abonomn' => $abonomn,
                    'abonome' => $abonome,
                    'usuario' => Auth::id(),
                );
                AccountingNoteDetail::editar($parent_id, $item, $data);
            }
        };
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
}
