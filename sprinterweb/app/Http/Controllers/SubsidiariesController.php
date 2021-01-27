<?php

namespace App\Http\Controllers;

use App\Http\Traits\CartTrait;
use App\Http\Traits\Controls\Tables;
use App\Http\Traits\Controls\UserPrivileges;
use App\Subsidiaries\Entities\Subsidiaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDOException;
use Yajra\DataTables\DataTables;
use App\Http\Requests\MaestroRequest;

class SubsidiariesController extends Controller
{
    use CartTrait, Tables, UserPrivileges;
    protected $var = 'subsidiaries';
    private $tabla_cabecera = 'sucursal';
    protected $user;
    protected $privilegios = '01.04.01';

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
        $data['view'] = link_view('Maestros', 'Otros', 'Sucursales', '');
        $data['header'] = headeroptions($this->var, 'list', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('subsidiaries.list', $data);
    }

    public function list()
    {
        $subsudiaries = Subsidiaries::all();
        return DataTables::of($subsudiaries)->make();
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
        $data['route'] = route('subsidiaries');
        $data['view'] = link_view('Maestros', 'Otros', 'Sucursales', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('subsidiaries.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {
        //dd($request->all());
        try {
            $sucursales = new Subsidiaries();
            $sucursales->codigo = $request->code;
            $sucursales->descripcion = $request->description;
            $sucursales->direccion = ($request->address == '') ? '' : $request->address;
            $sucursales->contacto = ($request->name == '') ? '' : $request->name;
            $sucursales->cargo = ($request->charge == '') ? '' : $request->charge;
            $sucursales->telefono = ($request->phones == '') ? '' : $request->phones;
            $sucursales->usuario = Session::get('usuario');
            $id = null;
            $id = DB::transaction(function () use ($sucursales) {
                $sucursales->save();
                return $sucursales->id;
            });
            $r["ruta"] = route('edit.subsidiaries', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

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
        $data['route'] = route('subsidiaries');
        $data['proceso'] = 'edita';
        $subdiaries = Subsidiaries::findOrFail($id);
        $data['sucursal'] = $subdiaries;
        $data['view'] = link_view('Maestros', 'Otros', 'Sucursales', '');
        $data['header'] = headeroptions($this->var, 'edita', '', ($subdiaries->estado == 1) ? 'ACTIVO' : 'ANULADO', $this->privilegios(), $this->user);
        $data['navbar_menu'] = navbar_menu($this->privilegios_navbar_menu(), $this->user);

        return view('subsidiaries.edit', $data);
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
            $sucursales = Subsidiaries::findOrFail($id);
            $sucursales->codigo = $request->code;
            $sucursales->descripcion = $request->description;
            $sucursales->direccion = ($request->address == '') ? '' : $request->address;
            $sucursales->contacto = ($request->name == '') ? '' : $request->name;
            $sucursales->cargo = ($request->charge == '') ? '' : $request->charge;
            $sucursales->telefono = ($request->phones == '') ? '' : $request->phones;
            $sucursales->usuario = Session::get('usuario');
            $id = null;
            $id = DB::transaction(function () use ($sucursales) {
                $sucursales->save();
                return $sucursales->id;
            });
            $r["ruta"] = route('edit.subsidiaries', $id);
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
                Subsidiaries::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
