<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\FinancialState\Entities\FinancialState;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class FinancialStateController extends Controller
{
    private $var = 'financialstate';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
    }

    public function list(Request $request)
    {
        $period = Period::where('descripcion', Session::get('period'))->first();

        $fecha_inicial = $period->inicio;

        if ($request->initaldate == null) {
            $fecha_final = $period->final;
        } else {
            $fecha_final = $request->finishdate;
        }

        if ($request->financialstate == null) {
            $estado_financiero = 1;
        } else {
            $estado_financiero = $request->financialstate;
        }

        if ($request->currency == null) {
            $moneda = 'N';
        } else {
            $moneda = $request->currency;
        }

        $chk_acumulada = validaCheck($request->view);

        $vista = $moneda . $chk_acumulada;

        $resultado = $this->procedurereports->rptEstadoFinancieroAnual($fecha_inicial, $fecha_final, $estado_financiero, $vista);

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
        $data['currencies'] = Currency::all();
        $data['estados'] = FinancialState::all();
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Estado Financiero - Anual','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('financialstate.list', $data);
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
