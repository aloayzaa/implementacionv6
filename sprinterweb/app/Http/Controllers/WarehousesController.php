<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Subsidiaries\Entities\Subsidiaries;
use App\Warehouses\Entities\WareHouses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;
use App\Ubigeos\Entities\Ubigeo;
use App\Http\Requests\MaestroRequest;

class WarehousesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'warehouses';
    private $tabla_cabecera = 'almacen';
    protected $user;
    protected $privilegios = 'almacenes';

    public function __construct()
    {
        $this->obtener_cliente();
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
        $data['route'] = $request->route;
        $data['proceso'] = $request->proceso;
        $data['view'] = link_view('Maestros', 'Otros', 'Almacenes', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('warehouses.list', $data);
    }

    public function list()
    {
        $warehouses = WareHouses::all();
        return DataTables::of($warehouses)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['var'] = $this->var;
        $data['route'] = route('warehouses');
        $data['proceso'] = 'crea';
        $data['sucursales'] = Subsidiaries::all()->where('estado', 1);
        $data['ubigeo'] = Ubigeo::all()->where('estado', 1);
        $data['view'] = link_view('Maestros', 'Otros', 'Almacenes', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('warehouses.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\ResponseMAS
     */
    public function store(MaestroRequest $request)
    {
        //dd($request->all());
        try {
            $almacenes = new WareHouses();
            $almacenes->codigo = $request->code_almacen;
            $almacenes->descripcion = $request->description_almacen;
            $almacenes->direccion = ($request->address == '') ? '' : $request->address;
            $almacenes->ubigeo_id = ($request->ubigeo == '') ? '' : $request->ubigeo;
            $almacenes->sucursal_id = $request->subsidiary_almacen;
            $almacenes->usuario = Session::get('usuario');
            $almacenes->esconsignacion = (isset($request->esconsignacion) && $request->esconsignacion == "on") ? 1 : 0;
            $almacenes->codsunat = ($request->stablishment_almacen == '') ? '' : $request->stablishment_almacen;
            $almacenes->nombrecorto = $request->shortname_almacen;
            $almacenes->codwarrant = ($request->codwarrant == '') ? '' : $request->codwarrant;

            $id = null;
            $id = DB::transaction(function () use ($almacenes) {
                $almacenes->save();
                $id = $almacenes->id;
                return $id;
            });

            $r["ruta"] = route('edit.warehouses', $id);
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
        $data['var'] = $this->var;
        $data['route'] = route('warehouses');
        $data['proceso'] = 'edita';
        $warehouse = WareHouses::findOrFail($id);
        $data['warehouse'] = $warehouse;
        $data['sucursales'] = Subsidiaries::all()->where('estado', 1);
        $data['ubigeo'] = Ubigeo::all()->where('estado', 1);
        $data['view'] = link_view('Maestros', 'Otros', 'Almacenes', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($warehouse->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('warehouses.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(MaestroRequest $request, $id)
    {
        try {
            $almacenes = WareHouses::findOrfail($id);
            $almacenes->codigo = $request->code_almacen;
            $almacenes->descripcion = $request->description_almacen;
            $almacenes->direccion = ($request->address == '') ? '' : $request->address;
            $almacenes->ubigeo_id = ($request->ubigeo == '') ? '' : $request->ubigeo;
            $almacenes->sucursal_id = $request->subsidiary_almacen;
            $almacenes->usuario = Session::get('usuario');
            $almacenes->esconsignacion = (isset($request->esconsignacion) && $request->esconsignacion == "on") ? 1 : 0;
            $almacenes->codsunat = ($request->stablishment_almacen == '') ? '' : $request->stablishment_almacen;
            $almacenes->nombrecorto = $request->shortname_almacen;
            $almacenes->codwarrant = ($request->codwarrant == '') ? '' : $request->codwarrant;

            $id = null;
            $id = DB::transaction(function () use ($almacenes) {
                $almacenes->save();
                $id = $almacenes->id;
                return $id;
            });

            $r["ruta"] = route('edit.warehouses', $id);
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
                WareHouses::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
