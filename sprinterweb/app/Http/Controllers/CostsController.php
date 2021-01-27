<?php

namespace App\Http\Controllers;

use App\Costs\Entities\Costs;
use App\Http\Requests\MaestroRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;
use Yajra\DataTables\DataTables;

class CostsController extends Controller
{
    protected $var = 'costs';

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
        $data['view'] = link_view('Mestros', 'Costos', 'Centro de Costos', '');
        $data['header'] = headeroptions($this->var, 'list', '', '');

        return view('costs.list', $data);
    }

    public function list()
    {
        $costs = Costs::all();
        return DataTables::of($costs)->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['proceso'] = 'crea';
        $codigos = Costs::ultimo_codigo();
        $data['codigo'] = $codigos->codigo;
        $data['var'] = $this->var;
        $data['route'] = route('costs');
        $data['view'] = link_view('Maestros', 'Costos', 'Centro Costos', '');
        $data['header'] = headeroptions($this->var, 'crea', '', '');

        return view('costs.create', $data);
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
            $centroCostos = new Costs();
            $centroCostos->codigo = $request->code_cost;
            $centroCostos->descripcion = $request->description_costs;
            $centroCostos->tipo = ($request->type=='')? '':$request->type ;
            $centroCostos->codalterno = ($request->acode=='')?'':$request->acode;
            $centroCostos->piso = ($request->floor=='')?'':$request->floor;
            $centroCostos->techo = ($request->top=='')?'':$request->top;
            $centroCostos->largo = ($request->height=='')?'':$request->height;
            $centroCostos->ancho = ($request->width =='')?'':$request->width;
            $centroCostos->capacidad = $request->area;
            $centroCostos->usuario = Auth::id();
            $id=null;
            $id=DB::transaction(function () use ($centroCostos) {
                $centroCostos->save();
                $id= $centroCostos->id;
                return $id;
            });
            $r["ruta"] = route('edit.costs', $id);
            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

//    /**
//     * Display the specified resource.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */

    public function edit($id)
    {
        $data['centroCostos'] = Costs::findOrFail($id);
        $data['var'] = $this->var;
        $data['route'] = route('costs');
        $data['proceso'] = 'edita';
        $data['view'] = link_view('Maestros', 'Costos', 'Centro Costos', '');
        $data['header'] = headeroptions($this->var, 'edita', '', $data['centroCostos']->estado);

        return view('costs.edit', $data);
    }

//    /**
//     * Update the specified resource in storage.
//     *
//     * @param \Illuminate\Http\Request $request
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
    public function update(MaestroRequest $request, $id)
    {
        try {
            $centroCostos = Costs::findOrFail($id);
            $centroCostos->codigo = $request->code_cost;
            $centroCostos->descripcion = $request->description_costs;
            $centroCostos->tipo = ($request->type=='')? '':$request->type ;
            $centroCostos->codalterno = ($request->acode=='')?'':$request->acode;
            $centroCostos->piso = ($request->floor=='')?'':$request->floor;
            $centroCostos->techo = ($request->top=='')?'':$request->top;
            $centroCostos->largo = ($request->height=='')?'':$request->height;
            $centroCostos->ancho = ($request->width =='')?'':$request->width;
            $centroCostos->capacidad = $request->area;
            $centroCostos->usuario = Auth::id();
            $id=null;
            $id=DB::transaction(function () use ($centroCostos) {
                $centroCostos->save();
                $id=$centroCostos->id;
                return $id;
            });

            $r["estado"] = "ok";
            $r["success"] = "Grabado Correctamente";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["error"] = "Error al Grabar!";
            $r["bd"] = $e->getMessage();
        }

        $r["proceso"] = $request->proceso;

        return $r;
    }

    public function activar(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                Costs::anular($id, $request->estado);
            });

            $r["estado"] = "ok";

        } catch (PDOException $e) {
            $r["estado"] = "error";
            $r["bd"] = $e->getMessage();
        }

        return $r;
    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param int $id
//     * @return \Illuminate\Http\Response
//     */
    public function destroy($id)
    {
        //
    }
}
