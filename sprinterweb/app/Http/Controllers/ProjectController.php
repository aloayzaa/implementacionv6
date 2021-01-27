<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Project\Entities\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    protected $var = 'projects';

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
        $data['view'] = link_view('Mestros', 'Costos', 'Proyectos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('projects.list', $data);
    }

    public function list()
    {
        $projects = Project::all();
        return DataTables::of($projects)->make();
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
        $data['view'] = link_view('Maestros', 'Costos', 'Proyectos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        return view('projects.create', $data);
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
            $proyecto = new Project();
            $proyecto->codigo = $request->code;
            $proyecto->descripcion = $request->description;
            $proyecto->inicio = $request->initialdate;
            $proyecto->final = $request->finishdate;
            $proyecto->glosa = $request->observations;
            $proyecto->estado = 1;
            $proyecto->usuario = Auth::id();
            $proyecto->ctacargo_id = $request->chargeaccount;
            $proyecto->ctaabono_id = $request->paymentaccount;
            $proyecto->ctareferencia = $request->account;
            $proyecto->encurso = validaCheck($request->chkconfig);

            DB::transaction(function () use ($proyecto) {
                $proyecto->save();
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
        $data['var'] = $this->var;
        $data['proyecto'] = Project::findOrFail($id);
        $data['proceso'] = 'edita';
        $data['route'] = route($this->var);
        $data['view'] = link_view('Mestros', 'Costos', 'Proyectos', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['proyecto']->estado);
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();

        return view('projects.edit', $data);
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
            $proyecto = Project::findOrFail($id);
            $proyecto->codigo = $request->code;
            $proyecto->descripcion = $request->description;
            $proyecto->inicio = $request->initialdate;
            $proyecto->final = $request->finishdate;
            $proyecto->glosa = $request->observations;
            $proyecto->usuario = Auth::id();
            $proyecto->ctacargo_id = $request->chargeaccount;
            $proyecto->ctaabono_id = $request->paymentaccount;
            $proyecto->ctareferencia = $request->account;
            $proyecto->encurso = validaCheck($request->chkconfig);

            DB::transaction(function () use ($proyecto) {
                $proyecto->save();
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
                Project::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }
}
