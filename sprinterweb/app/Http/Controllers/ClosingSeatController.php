<?php

namespace App\Http\Controllers;

use App\AccountingNote\Collections\AccountingNoteCollection;
use App\AccountingNote\Entities\AccountingNote;
use App\Costs\Entities\Costs;
use App\Currency\Entities\Currency;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Period\Entities\Period;
use App\Procedures\ContabilidadProcedure;
use App\Subdiaries\Entities\Subdiaries;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use mysql_xdevapi\Exception;
use PDOException;
use Yajra\DataTables\DataTables;

class ClosingSeatController extends Controller
{
    protected $var = 'closingseat';
    protected $instancia = 'detalle_closingseat';
    private $ventana = 'MOV_NOTACONTABLE_CIERRE';
    private $openingseatcollection;
    private $contabilidadprocedure;

    public function __construct(AccountingNoteCollection $openingseatcollection, ContabilidadProcedure $contabilidadprocedure)
    {
        $this->obtener_cliente();
        $this->openingseatcollection = $openingseatcollection;
        $this->contabilidadprocedure = $contabilidadprocedure;
    }

    public function list()
    {
        $notes = AccountingNote::lista_nota_contable($this->ventana);
        $this->openingseatcollection->actions($notes, $this->var);
        return DataTables::of($notes)->rawColumns(['actions', 'estado'])->make();
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
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Cierre','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('closingseat.list', $data);
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
        $data['subdiarios'] = Subdiaries::where('cierre', 1)->get();
        $data['currencies'] = Currency::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['costos'] = Costs::all();
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Cierre','');
        $data['header'] = headeroptions($this->var, 'crea','','');
        return view('closingseat.create', $data);
    }

    public function listar_detalle(Request $request)
    {
        $data['proceso'] = $request->proceso;
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['carts'] = Cart::instance($data['instancia'])->content();
        $data['total'] = Cart::instance($data['instancia'])->count();
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Asiento Cierre','');

        return view('closingseat.' . $data['instancia'] . '', $data);
    }

    public function agregar(Request $request)
    {
        $item = $request->txt_auxiliar;
        $parent_id = $request->txt_aux_id;
        $id_cuenta = $request->account;

        if ($id_cuenta != null) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }
        $id_costo = $request->cost;
        if ($id_costo != null) {
            $costo = Costs::findOrFail($id_costo);
            $costo_codigo = $costo->codigo;
            $costo_descripcion = $costo->descripcion;
        } else {
            $costo_codigo = '';
            $costo_descripcion = '';
        }
        $cargomn = $request->cargomn;
        $cargome = $request->cargome;
        $abonomn = $request->abonomn;
        $abonome = $request->abonome;
        $glosa = $request->glosam;

        Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
            'options' => [
                'parent_id' => $parent_id,
                'item' => $item,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'costo_id' => $id_costo,
                'costo_codigo' => $costo_codigo,
                'costo_descripcion' => $costo_descripcion,
                'cargomn' => $cargomn,
                'cargome' => $cargome,
                'abonomn' => $abonomn,
                'abonome' => $abonome,
                'glosa' => $glosa,
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

    public function editar_detalle(Request $request)
    {
        $id_carrito = $request->txt_id_modelo_modal;
        $item = $request->txt_auxiliar;
        $parent_id = $request->txt_aux_id;
        $id_cuenta = $request->account;
        if ($id_cuenta != null) {
            $cuenta = AccountingPlan::findOrFail($id_cuenta);
            $cuenta_codigo = $cuenta->codigo;
            $cuenta_descripcion = $cuenta->descripcion;
        } else {
            $cuenta_codigo = '';
            $cuenta_descripcion = '';
        }
        $id_costo = $request->cost;
        if ($id_costo != null) {
            $costo = Costs::findOrFail($id_costo);
            $costo_codigo = $costo->codigo;
            $costo_descripcion = $costo->descripcion;
        } else {
            $costo_codigo = '';
            $costo_descripcion = '';
        }
        $cargomn = $request->cargomn;
        $cargome = $request->cargome;
        $abonomn = $request->abonomn;
        $abonome = $request->abonome;
        $glosa = $request->glosam;

        Cart::instance($this->instancia)->update($id_carrito, [
            'options' => [
                'parent_id' => $parent_id,
                'item' => $item,
                'cuenta_id' => $id_cuenta,
                'cuenta_codigo' => $cuenta_codigo,
                'cuenta_descripcion' => $cuenta_descripcion,
                'costo_id' => $id_costo,
                'costo_codigo' => $costo_codigo,
                'costo_descripcion' => $costo_descripcion,
                'cargomn' => $cargomn,
                'cargome' => $cargome,
                'abonomn' => $abonomn,
                'abonome' => $abonome,
                'glosa' => $glosa,

            ]]);

        $data["estado"] = "ok";
        $data["instancia"] = $this->instancia;

        return $data;
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
            $asientocierre = new AccountingNote();
            $asientocierre->sucursal_id = 1;
            $asientocierre->periodo_id = Session::get('period_id');
            $asientocierre->subdiario_id = $request->subdiary;
            $asientocierre->fechaproceso = $request->processdate;
            $asientocierre->tcambio = $request->changerate;
            $asientocierre->moneda_id = $request->currency;
            $asientocierre->totalmn = $request->totalmn;
            $asientocierre->totalme = $request->totalme;
            $asientocierre->glosa = $request->comment;
            $asientocierre->estado = 1;
            $asientocierre->usuario = Auth::id();
            $asientocierre->ventana = $this->ventana;

            DB::transaction(function () use ($asientocierre) {
                $asientocierre->save();
                $mensaje = $this->genera_asiento_cierre($asientocierre->id);

                if ($mensaje == null) {
                    $this->contabiliza_notacontable($asientocierre->id);
                    $this->centraliza_notacontable($asientocierre->id);
                } else {
                    throw new Exception($mensaje);
                }
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        } catch (Exception $e) {
            $r["estado"] = "error";
            $r["mensaje"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;
        Cart::instance($this->instancia)->destroy();

        return $r;
    }

    private function genera_asiento_cierre($id_cierre)
    {
        $pdo = DB::connection()->getPdo();
        $pdo->exec('CALL generacierre(' . $id_cierre . ',@pRet)');
        $res = $pdo->query('select @pRet as mensaje')->fetch();
        return $res['mensaje'];
    }

    private function contabiliza_notacontable($id_notacontable)
    {
        $this->contabilidadprocedure->generaCtacte($id_notacontable, 'notacontable', '@pRet');
    }

    private function centraliza_notacontable($id_notacontable)
    {
        $this->contabilidadprocedure->cntEsAutomatica($id_notacontable, '@pRet');
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
        try {
            Cart::instance($this->instancia)->count();
            $asientocierre = AccountingNote::findorfail($id);
            $asientocierre->sucursal_id = 1;
            $asientocierre->periodo_id = Session::get('period_id');
            $asientocierre->subdiario_id = $request->subdiary;
            $asientocierre->fechaproceso = $request->processdate;
            $asientocierre->tcambio = $request->changerate;
            $asientocierre->moneda_id = $request->currency;
            $asientocierre->totalmn = $request->totalmn;
            $asientocierre->totalme = $request->totalme;
            $asientocierre->glosa = $request->glosa;
            $asientocierre->usuario = Auth::id();
            $asientocierre->ventana = $this->ventana;

            DB::transaction(function () use ($asientocierre) {
                $asientocierre->save();
                $this->contabiliza_notacontable($asientocierre->id);
                $this->centraliza_notacontable($asientocierre->id);
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
        }

        $r["proceso"] = $request->proceso;
        Cart::instance($this->instancia)->destroy();

        return $r;
    }

    public function activar(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                AccountingNote::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

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
}
