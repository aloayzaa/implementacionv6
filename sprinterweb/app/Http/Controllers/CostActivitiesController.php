<?php

namespace App\Http\Controllers;

use App\CostActivities\Entities\CostActivities;
use App\Http\Requests\MaestroRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class CostActivitiesController extends Controller
{
    protected $var = 'costactivities';

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
        $data['view'] = link_view('Mestros', 'Costos', 'Actividades de Costo', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('costactivities.list', $data);
    }

    public function list()
    {
        $activities = CostActivities::all();
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
        $data['var'] = $this->var;
        $data['route'] = route('costactivities');
        $data['view'] = link_view('Maestros', 'Costos', 'Actividades de Costo', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('costactivities.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaestroRequest $request)
    {
        try {
            $activities = new CostActivities();
            $activities->codigo = $request->code_activ;
            $activities->descripcion = $request->description_activ;
            $activities->costohoramn = ($request->hourlycost =='')?'':$request->hourlycost;
            $activities->estado = 1;
            $activities->usuario = Auth::id();

            $id=null;
            $id= DB::transaction(function () use ($activities) {
                $activities->save();
                $id=$activities->id;
                return $id;
            });
            $r["ruta"] = route('edit.costactivities', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
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
        $data['activities'] = CostActivities::findOrFail($id);
        $data['var'] = $this->var;
        $data['route'] = route('costactivities');
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Maestros', 'Costos', 'Actividades de Costo', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['activities']->estado);

        return view('costactivities.edit', $data);
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
            $activities = CostActivities::findOrFail($id);
            $activities->codigo = $request->code_activ;
            $activities->descripcion = $request->description_activ;
            $activities->costohoramn = ($request->hourlycost =='')?'':$request->hourlycost;
            $id=null;
            $id= DB::transaction(function () use ($activities) {
                $activities->save();
                $id=$activities->id;
                return $id;
            });

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["success"] = "Error al Grabar!";
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
                CostActivities::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
