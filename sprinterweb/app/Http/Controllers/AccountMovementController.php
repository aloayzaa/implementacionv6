<?php

namespace App\Http\Controllers;

use App\AccountingPlan\Entities\AccountingPlan;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class AccountMovementController extends Controller
{
    private $var = 'accountmovement';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
    }

    public function list(Request $request)
    {
        if ($request->initaldate == null) {
            $period = Period::where('descripcion', Session::get('period'))->first();
            $fecha_inicial = $period->inicio;
            $fecha_final = $period->final;
        } else {
            $fecha_inicial = $request->initialdate;
            $fecha_final = $request->finishdate;
        }

        if ($request->account == null) {
            $id_cuenta = 0;
        } else {
            $id_cuenta = $request->account;
        }

        $resultado = $this->procedurereports->rptMovimientoxCuenta($fecha_inicial, $fecha_final, $id_cuenta);

        $resultado = collect($resultado);

        return Datatables::of($resultado)->make();
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
        $data['accounts'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Movimientos por Cuenta','');
        $data['header'] = headeroptions($this->var, 'list','','');

        return view('accountmovement.list', $data);
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
