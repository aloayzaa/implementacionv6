<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SalesSummaryController extends Controller
{
    private $var = 'salessummary';
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
        $data['view'] = link_view('Ventas', 'Reportes', 'Resumen de Ventas','');

        return view('salessummary.list', $data);
    }

    public function list(Request $request)
    {
        $chk_ccosto = $request->chk_ccosto;
        $chk_mensual = $request->chk_mensual;

        $hoy = date('Y-m-d');

        $fecha_inicio = $request->fecha_inicio;
        if (!$fecha_inicio) {
            $fecha_inicio = $hoy;
        } else {
            $fecha_inicio = $request->fecha_inicio;
        }

        $fecha_fin = $request->fecha_fin;
        if (!$fecha_fin) {
            $fecha_fin = $hoy;
        } else {
            $fecha_fin = $request->fecha_fin;
        }

        $tipo = $request->cbo_tipo;
        if (!$tipo) {
            $tipo = "C";
        } else {
            $tipo = $request->cbo_tipo;
        }

        $pVista = '1' . $chk_mensual . $chk_ccosto;

        $resultado = $this->procedurereports->rptResumenVenta($fecha_inicio, $fecha_fin, $pVista, $tipo, 1);

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
