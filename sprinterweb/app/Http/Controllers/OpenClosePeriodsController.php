<?php

namespace App\Http\Controllers;

use App\Period\Collections\PeriodCollection;
use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class OpenClosePeriodsController extends Controller
{
    private $var = 'opencloseperiods';
    private $periodcollecion;

    public function __construct( )
    {
        $this->obtener_cliente();
    }

    public function list(Request $request)
    {
        $codigo = $request->codigo;
        if ($codigo == null) {
            $periodo = Period::where('descripcion', Session::get('period'))->first();
            $periodos = Period::lista_search_x_anio(substr($periodo->codigo, 0, -2));
        } else {
            $periodos = Period::lista_search_x_anio($codigo);
        }

        $this->periodcollecion->actionsperiod($periodos);

        return DataTables::of($periodos)->rawColumns(['compras', 'ventas', 'almacen', 'tesoreria', 'contabilidad',
            'planillas', 'gestiontributaria', 'activos'])->make();
    }

    public function abrir_cerrar_periodo(Request $request)
    {
        if ($request->estado == 1) {
            $parametro = 0;
        } else {
            $parametro = 1;
        }

        Period::abrir_cerrar($request->id, $request->modulo, $parametro);

        $r["estado"] = "ok";
        $r["mensaje"] = "Grabado Correctamente";

        return $r;
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
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['view'] = link_view('Contabilidad', 'Procesos', 'Abrir/Cerrar Periodos','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('opencloseperiods.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
