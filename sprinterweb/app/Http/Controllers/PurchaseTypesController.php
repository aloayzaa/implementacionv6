<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\PurchasesType\Entities\PurchasesType;
use App\Subdiaries\Entities\Subdiaries;
use App\TransactionType\Entities\TransactionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;
use App\Http\Requests\PurchaseRequest;

class PurchaseTypesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'purchasetypes';
    private $tabla_cabecera = 'tipocompra';

    protected $user;
    protected $privilegios = '04.01.03';

    public function __construct()
    {
        $this->obtener_cliente();
        $this->user = 'ADMINISTRADOR';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $data['var'] = $this->var;
        $data['proceso'] = $request->proceso;
        $data['route'] = $request->route;
        $data['view'] = link_view('Gestión de Compras', 'Configuración', 'Tipos de Compra', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('purchasetypes.list', $data);
    }

    public function list()
    {
        $purchasetypes = PurchasesType::all();
        return DataTables::of($purchasetypes)->make();
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
        $data['route'] = route($this->var);
        $data['view'] = link_view('Gestión de Compras', 'Configuración', 'Tipos de Compra', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['transactions'] = TransactionType::all()->where('estado',1);
        $data['subdiarios'] = Subdiaries::all()->where('estado', 1);

        return view('purchasetypes.create', $data);
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
            $tipocompra = new PurchasesType();
            $tipocompra->codigo = $request->code_tipocompras;
            $tipocompra->descripcion = $request->description_tipocompras;
            $tipocompra->tipotransaccion_id = $request->type_tipocompras;
            $tipocompra->subdiario_id = $request->asociado_tipocompras;
            $tipocompra->esservicio = validaCheck($request->chkservice);
            $tipocompra->esimporta = validaCheck($request->chkimportation);
            $tipocompra->esretencion = validaCheck($request->chktax);
            $tipocompra->estado = 1;
            $tipocompra->usuario = Session::get('usuario');
            //$tipocompra->usuario = Auth::id();

            $id = null;
            $id = DB::transaction(function () use ($tipocompra) {
                $tipocompra->save();
                return $tipocompra->id;
            });

            $r["ruta"] = route('edit.purchasetypes', $id);

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
        $data['proceso'] = 'edita';
        $data['var'] = $this->var;
        $tipocompra = PurchasesType::findOrFail($id);
        $data['tipocompra'] = $tipocompra;
        $data['route'] = route($this->var);
        $data['view'] = link_view('Gestión de Compras', 'Configuración', 'Tipos de Compra', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($tipocompra->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);
        $data['transactions'] = TransactionType::all();
        $data['subdiarios'] = Subdiaries::all();

        return view('purchasetypes.edit', $data);
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
            $tipocompra = PurchasesType::findOrFail($id);
            $tipocompra->codigo = $request->code_tipocompras;
            $tipocompra->descripcion = $request->description_tipocompras;
            $tipocompra->tipotransaccion_id = $request->type_tipocompras;
            $tipocompra->subdiario_id = $request->asociado_tipocompras;
            $tipocompra->esservicio = validaCheck($request->chkservice);
            $tipocompra->esimporta = validaCheck($request->chkimportation);
            $tipocompra->esretencion = validaCheck($request->chktax);
            $tipocompra->usuario = Session::get('usuario');
            //$tipocompra->usuario = Auth::id();

            DB::transaction(function () use ($tipocompra) {
                $tipocompra->save();
            });

            $r["ruta"] = route('edit.purchasetypes', $id);
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

    public function activar(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                PurchasesType::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
