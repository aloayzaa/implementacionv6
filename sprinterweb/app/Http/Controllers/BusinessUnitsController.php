<?php

namespace App\Http\Controllers;

use App\BusinessUnit\Entities\BusinessUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class BusinessUnitsController extends Controller
{
    protected $var = 'businessunits';

    public function __construct()
    {
        $this->obtener_cliente();
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
        $data['view'] = link_view('Mestros', 'Costos', 'Uniades de Negocio', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('businessunits.list', $data);
    }

    public function list()
    {
        $activities = BusinessUnit::all();
        return DataTables::of($activities)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $codigos = BusinessUnit::all()->last();
        $data['codigo'] = $codigos->codigo + 1;
        $data['var'] = $this->var;
        $data['route'] = route($this->var);
        $data['view'] = link_view('Maestros', 'Costos', 'Actividades de Costo', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('businessunits.create', $data);
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
            $units = new BusinessUnit();
            $units->codigo = $request->code;
            $units->descripcion = $request->description;
            $units->estado = 1;
            $units->usuario = Auth::id();

            DB::transaction(function () use ($units) {
                $units->save();
            });

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
        $data['unit'] = BusinessUnit::findOrFail($id);
        $data['var'] = $this->var;
        $data['route'] = route($this->var);
        $data['view'] = link_view('Maestros', 'Costos', 'Actividades de Costo', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['unit']->estado);

        return view('businessunits.edit', $data);
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
            $units = BusinessUnit::findOrFail($id);
            $units->codigo = $request->code;
            $units->descripcion = $request->description;
            $units->estado = 1;
            $units->usuario = Auth::id();

            DB::transaction(function () use ($units) {
                $units->save();
            });

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
                BusinessUnit::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
