<?php

namespace App\Http\Controllers;

use App\AccountingNote\Collections\AccountingNoteCollection;
use App\AccountingNote\Entities\AccountingNote;
use App\AccountingNote\Entities\AccountingNoteDetail;
use App\BusinessUnit\Entities\BusinessUnit;
use App\Currency\Entities\Currency;
use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Period\Entities\Period;
use App\Procedures\ContabilidadProcedure;
use App\Subdiaries\Entities\Subdiaries;
use App\Subsidiaries\Entities\Subsidiaries;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use mysql_xdevapi\Exception;
use PDOException;
use App\Http\Requests\AccountingRequest;
use Yajra\DataTables\Facades\DataTables;


class AdjustmentExchangeController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'adjustmentexchange';
    protected $instancia = 'detalle_adjustmentexchange';
    private $ventana = 'MOV_NOTACONTABLE_AJUSTE'; //Nombre ventana
    private $openingseatcollection;
    private $contabilidadprocedure;

    protected $user;
    protected $privilegios = '06.02.04';

    public function __construct(AccountingNoteCollection $openingseatcollection, ContabilidadProcedure $contabilidadprocedure)
    {
        $this->obtener_cliente();
        $this->openingseatcollection = $openingseatcollection;
        $this->contabilidadprocedure = $contabilidadprocedure;
        $this->user = 'ADMINISTRADOR';
    }

    public function list()
    {
        //$notes = AccountingNote::lista_nota_contable($this->ventana);
        $notas = AccountingNote::list_nota_contable($this->ventana);
//        $this->openingseatcollection->actions($notas, $this->var);
        //return DataTables::of($notas)->rawColumns(['actions', 'estado'])->make();
        return DataTables::of($notas)->make();
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
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Ajuste de Diferencia de Cambio','');
        $data['header'] = headeroptions($this->var, 'list','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        return view('adjustmentexchange.list', $data);
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
        $data['subdiarios'] = Subdiaries::where('apertura', 0)->where('cierre', 0)->get();
        $data['unegocio'] = BusinessUnit::all()->where('estado',1);
        $data['sucursal'] = Subsidiaries::all()->where('estado',1);
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['currencies'] = Currency::all();
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Ajuste de Diferencia de Cambio','');
        $data['header'] = headeroptions($this->var, 'crea','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $this->eliminar_instancias();
        return view('adjustmentexchange.create', $data);
    }

    public function listar_detalle(Request $request)
    {
        $data['proceso'] = $request->proceso;
        $data['instancia'] = $this->instancia;
        $data['var'] = $this->var;
        $data['carts'] = Cart::instance($data['instancia'])->content();
        $data['total'] = Cart::instance($data['instancia'])->count();
        $data['view'] = link_view('Contabilidad', 'Transacción', 'Ajuste de Diferencia de Cambio','');

        return view('adjustmentexchange.' . $data['instancia'] . '', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountingRequest $request)
    {
        try {
            $notacontable = new AccountingNote();
            $notacontable->periodo_id = Session::get('period_id');
            $notacontable->sucursal_id = $request->sucursal_id;
            $notacontable->subdiario_id = $request->subdiary;
            $notacontable->fechaproceso = $request->processdate;
            $notacontable->unegocio_id = $request->cbo_unegocio;
            $notacontable->tcambio = $request->changerate;
            $notacontable->moneda_id = $request->currency;
            $notacontable->glosa = ($request->comment == '') ? '' : $request->comment;
            $notacontable->estado = 'ACTIVO';
            $notacontable->ventana = $this->ventana;
            $notacontable->usuario = Auth::id();
            $id = null;
            $id = DB::transaction(function () use ($notacontable) {
                $notacontable->save();
                $mensaje1 = $this->insertar_detalle_ajuste_diferencia($notacontable->id);
                if ($mensaje1 == "") {
                    $this->contabiliza_notacontable($notacontable->id);
                    $this->centraliza_notacontable($notacontable->id);
                    return $notacontable->id;
                }
                else{
                    $r["estado"]="error";
                    $r["mensaje"]=$mensaje1;
                }
            });
            $r["ruta"] = route('edit.adjustmentexchange', $id);
            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    private function insertar_detalle_ajuste_diferencia($id_notacontable)
    {
        $pdo = DB::connection()->getPdo();
        $pdo->exec('CALL sprinter.generaajustedc(' . $id_notacontable . ',@pRet)');
        $res = $pdo->query('select @pRet as mensaje')->fetch();
        return $res['mensaje'];
    }

    private function contabiliza_notacontable($id_notacontable)
    {
        $ptabla='notacontable';
        $pRet='@pRet';
        $this->contabilidadprocedure->generaCtacte($id_notacontable, $ptabla, $pRet);
    }

    private function centraliza_notacontable($id_notacontable)
    {
        $pRet='@pRet';
        $this->contabilidadprocedure->cntEsAutomatica($id_notacontable, $pRet);
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
        $this->eliminar_instancias();
        $data["notacontable"] = AccountingNote::findOrFail($id);
//        $data["notacontable_detalle"] = AccountingNoteDetail::verDatos($id);
        $data['var'] = $this->var;
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['unegocio'] = BusinessUnit::all()->where('estado',1);
        $data['sucursal'] = Subsidiaries::all()->where('estado',1);
        $fecha = new Carbon(date('Y-m-d'));
        $data['fecha'] =    $fecha->format('Y-m-d');
        $data['instancia'] = $this->instancia;
        $data['subdiarios'] = Subdiaries::where('apertura', 0)->where('cierre', 0)->get();
        $data['currencies'] = Currency::all();
        $data["proceso"] = 'edita';

        $data['view'] = link_view('Contabilidad', 'Transacción', 'Ajuste de Diferencia de Cambio','');
        $data['header'] = headeroptions($this->var, 'edita','','', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        $this->agregar_carrito_detalle($id);
        return view('adjustmentexchange.edit', $data);
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
            $notacontable = AccountingNote::findOrFail($id);
            $notacontable->periodo_id = Session::get('period_id');
            $notacontable->sucursal_id = $request->sucursal_id;
            $notacontable->subdiario_id = $request->subdiary;
            $notacontable->fechaproceso = $request->processdate;
            $notacontable->unegocio_id = $request->cbo_unegocio;
            $notacontable->tcambio = $request->changerate;
            $notacontable->moneda_id = $request->currency;
            $notacontable->glosa = $request->comment;
            $notacontable->ventana = $this->ventana;
            $notacontable->usuario = Auth::id();

            DB::transaction(function () use ($notacontable) {
                $notacontable->save();
                $mensaje1 = $this->insertar_detalle_ajuste_diferencia($notacontable->id);
                if ($mensaje1 == "") {
                    $this->contabiliza_notacontable($notacontable->id);
                    $this->centraliza_notacontable($notacontable->id);
                }
                /*else {
                    throw new Exception($mensaje1);
                }*/
            });

            $r["estado"] = "ok";
            $r["mensaje"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["mensaje"] = "Error al Editar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

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

    public function listdetail()
    {
        return DataTables::of(collect(Cart::instance($this->instancia)->content()))->make();
    }

    public function agregar_carrito_detalle($id){

        $datos = AccountingNoteDetail::datos($id);

        foreach ($datos as $nota_detalle) {
            Cart::instance($this->instancia)->add(['id' => 1, 'name' => 'un nombre', 'qty' => 1, 'price' => 0.00,
                'options' => [
                    'id_parent' => $id,
                    'item' => $nota_detalle->item,
                    'codigo' => $nota_detalle->codigo,
                    'nombre' => $nota_detalle->nombre,
                    'docrefer' => $nota_detalle->docrefer,
                    'cuenta' => $nota_detalle->cuenta,
                    'cargomn' => $nota_detalle->cargomn,
                    'abonomn' => $nota_detalle->abonomn,
                    'cargome' => $nota_detalle->cargome,
                    'abonome' => $nota_detalle->abonome,
                    'glosa' => $nota_detalle->glosa,
                    'cco_cod' => $nota_detalle->cco_cod,
                    'cco_dsc' => $nota_detalle->cco_dsc,
                    'act_cod' => $nota_detalle->act_cod,
                    'act_dsc' => $nota_detalle->act_dsc,
                    'pry_cod' => $nota_detalle->pry_cod,
                    'pry_dsc' => $nota_detalle->pry_dsc,
                ]
            ]);
        }
    }

    public function eliminar_instancias()
    {
        Cart::instance($this->instancia)->destroy();
    }

//    public function sumar(Request $request){
//        $ln1=$ln2=$ln3=$ln4=0;
//        $importe = $cargome = $abonome = $cargomn = $abonomn = 0;
//        $lntcambio = $request->changerate;
//        $subdiario = Subdiaries::select('bimoneda', 'deajuste')->where('id', $request->subdiary)->first();
//        $lctipomoneda =  Currency::select('tipo')->where('id', $request->currency)->first();
//        $detalle = AccountingNoteDetail::all();
//        foreach ($detalle as $dnotacontable){
//            if ($subdiario->bimoneda == 0 and $subdiario->deajuste == 0){
//                if ($lctipomoneda == 'N'){
//                    if ($dnotacontable->referencia_id > 0){
//                        if ($dnotacontable->cargomn > 0){
//                            $importe = $dnotacontable / $lntcambio;
//                            if ($importe > $dnotacontable->importeme || $dnotacontable->cargomn == $dnotacontable->importemn){
//                                $importe = $dnotacontable->importeme;
//                            }
//                            $cargome = $importe;
//                        }else{
//                            $importe =  $dnotacontable->abonomn / $lntcambio;
//                            if ($importe > $dnotacontable->importeme || $dnotacontable->abonomn = $dnotacontable->importemn){
//                                $importe = $dnotacontable->importeme;
//                            }
//                        }
//                    }else{
//                        $cargome = $dnotacontable->cargomn / $lntcambio;
//                        $abonome = $dnotacontable->abonomn / $lntcambio;
//                    }
//                }else{
//                    if ($dnotacontable->refencia_id > 0){
//                        if ($dnotacontable->cargomn > 0){
//                            $importe = $dnotacontable->cargome * $lntcambio;
//                            if ($importe > $dnotacontable->importemn || $dnotacontable->cargome == $dnotacontable->importeme){
//                                $importe = $dnotacontable->importemn;
//                            }
//                            $dnotacontable->cargomn = $importe;
//                        }else{
//                            $importe = $dnotacontable->abonome * $lntcambio;
//                            if ($importe > $dnotacontable->importemn || $dnotacontable->abonome == $dnotacontable->importeme){
//                                $importe = $dnotacontable->importeme;
//                            }
//                            $abonomn = $dnotacontable->importemn;
//                        }
//                    }else{
//                        $cargomn = $dnotacontable->cargome * $lntcambio;
//                        $abonomn = $dnotacontable->abonome * $lntcambio;
//                    }
//                }
//            }
//            $ln1 += $cargomn;
//            $ln2 += $abonomn;
//            $ln3 += $cargome;
//            $ln4 += $abonome;
//        }
//        $data['txtcargomn'] = $ln1;
//        $data['txtcargome'] = $ln3;
//        $data['txtabonomn'] = $ln2;
//        $data['txtabonome'] = $ln4;
//        $data['txtnum1'] = $ln1 - $ln2;
//        $data['txtnum2'] = $ln3 - $ln4;
//        return $data;
//    }
//
    public function totalizar(Request $request){
        $ln1=$ln2=$ln3=$ln4=0;
        $lnTotmn = $lnTotme = $lntTot = 0;
        $cargome = $abonome = $cargomn = $abonomn = 0;
        $lctipomoneda =  Currency::select('tipo')->where('id', $request->currency)->first();
        //validando si el asiento está descuadrado
        $detalle = AccountingNoteDetail::all();
        foreach ($detalle as $dnotacontable){
            $lnTotmn += ($dnotacontable->cargomn - $dnotacontable->abonomn);
            $lnTotme += ($dnotacontable->cargome - $dnotacontable->abonome);
            if ($lctipomoneda->tipo == 'N'){
                if ($lnTotmn <> 0){
                    return;
                }
            }else{
                if ($lnTotme <> 0){
                    return;
                }
            }
            if ($lctipomoneda->tipo == 'N'){
                $lntTot += ($dnotacontable->cargome - $dnotacontable->abonome);
                if ($lntTot <> 0){
                    //falta especificar algo
                    if ($dnotacontable->cargome > 0){
                        $cargome = $dnotacontable->cargome + $lntTot * -1;
                    }
                    if ($dnotacontable->abonome > 0){
                        $abonome = $dnotacontable->abonome + $lntTot;
                    }
                }
            }else{
                $lntTot += ($dnotacontable->cargomn - $dnotacontable->abonomn);
                if ($lntTot <> 0){
                    if ($dnotacontable->cargomn > 0){
                        $cargomn = $dnotacontable->cargomn + $lntTot * -1;
                    }
                    if ($dnotacontable->abonomn > 0){
                        $abonomn = $dnotacontable->abonomn + $lntTot;
                    }
                }
            }
            $ln1 += $cargomn;
            $ln2 += $abonomn;
            $ln3 += $cargome;
            $ln4 += $abonome;
        }

        $data['txtcargomn'] = $ln1;
        $data['txtcargome'] = $ln3;
        $data['txtabonomn'] = $ln2;
        $data['txtabonome'] = $ln3;
        $data['totalmn'] = $ln1;
        $data['totalme'] = $ln3;
        return $data;
    }

}
