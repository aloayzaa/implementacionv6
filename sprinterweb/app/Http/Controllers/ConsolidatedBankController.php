<?php

namespace App\Http\Controllers;

use App\Bank\Entities\Bank;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class ConsolidatedBankController extends Controller
{
    private $var = 'consolidatedbank';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['period'] = Period::where('descripcion', Session::get('period'))->first();
        $data['var'] = $this->var;
        $data['bancos'] = Bank::all();
        $data['view'] = link_view('Tesoreria', 'Reportes', 'Consolidado de Bancos','');
        $data['header'] = headeroptions($this->var, 'list');
        return view('consolidatedbank.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->banco_id == null) {
            $banco_id = 0;
        } else {
            $banco_id = $request->banco_id;
        }

        $period = Period::where('descripcion', Session::get('period'))->first();

        if ($request->initialdate == null) {
            $fecha_inicio = $period->inicio;
        } else {
            $fecha_inicio = $request->initialdate;
        }

        if ($request->finishdate == null) {
            $fecha_fin = $period->final;
        } else {
            $fecha_fin = $request->finishdate;
        }

        if ($request->currency == null) {
            $moneda = "O";
        } else {
            $moneda = $request->currency;
        }

        $resultado = $this->procedurereports->rptConsolidadoBancos($banco_id, $fecha_inicio, $fecha_fin, $moneda);
        $resultado = collect($resultado);

        return Datatables::of($resultado)->make(true);
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
     *$data['view'] = link_view
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
