<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class BalancesAccountController extends Controller
{
    private $var = 'balancesaccount';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
    }

    public function list(Request $request)
    {
        if ($request->year == null) {
            $data = Period::where('descripcion', Session::get('period'))->first();
            $fecha_inicio = substr($data->codigo, 0, 4) . '-01-01';
            $fecha_fin = substr($data->codigo, 0, 4) . '-12-31';
        } else {
            $fecha_inicio = $request->year . '-01-01';
            $fecha_fin = $request->year . '-12-31';
        }

        $id_cuenta = $request->account;

        $resultado = $this->procedurereports->rptSaldoxCuenta($fecha_inicio, $fecha_fin, $id_cuenta);
        $resultado = collect($resultado);

        return Datatables::of($resultado)->make(true);
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
        $data['currencies'] = Currency::all();
        $data['pcgs'] = AccountingPlan::where('es_titulo', 2)->get();
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Saldos por Cuenta','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('balancesaccount.list', $data);
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
