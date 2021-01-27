<?php

namespace App\Http\Controllers;

use App\Bank\Entities\Bank;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class IssuedChecksController extends Controller
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
        $data['view'] = link_view('Tesoreria', 'Reportes', 'Relación Cheques Emitidos','');
        $data['header'] = headeroptions($this->var, 'list');
        return view('issuedchecks.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->banco_id == null) {
            $banco_id = 0;
        } else {
            $banco_id = $request->bank;
        }

        $period = Period::where('descripcion', Session::get('period'))->first();

        if ($request->fecha_inicio == null) {
            $fecha_inicio = $period->inicio;
        } else {
            $fecha_inicio = $request->initialdate;
        }

        if ($request->finishdate == null) {
            $fecha_fin = $period->final;
        } else {
            $fecha_fin = $request->finishdate;
        }

        $resultado = $this->procedurereports->rptChequesEmitidos($banco_id, $fecha_inicio, $fecha_fin);
        $resultado = collect($resultado);

        return Datatables::of($resultado)->make();
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
