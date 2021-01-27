<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OperationCustomerController extends Controller
{
    private $var = 'operationcustomer';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
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
        $data['view'] = link_view('Gestión Tributaria', 'Procesos', 'PDB-Programa de Declaración de Beneficios', '');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['currencies'] = Currency::all();

        return view('operationcustomer.list', $data);
    }

    public function list(Request $request)
    {
        $period = Period::findOrFail(Session::get('period_id'));

        if ($request->year == null) {
            $anio = substr($period->codigo, 0, 4);
        } else {
            $anio = $request->year;
        }

        if ($request->type == null) {
            $tipo = "P";
        } else {
            $tipo = $request->type;
        }

        $chk_detallado = validaCheck($request->detailed);

        $ctipo = 'D' . $tipo . $chk_detallado;

        $resultado = $this->procedurereports->rptPdtOperacionesTerceros($anio, $ctipo);

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

    public function reporte_operaciones_por_terceros(Request $request)
    {
        $period = Period::findOrFail(Session::get('period_id'));

        if ($request->year == null) {
            $anio = substr($period->codigo, 0, 4);
        } else {
            $anio = $request->anio;
        }

        if ($request->type == null) {
            $tipo = "P";
        } else {
            $tipo = $request->type;
        }

        $chk_detallado = validaCheck($request->detailed);

        $ctipo = 'D' . $tipo . $chk_detallado;

        $resultado = $this->procedurereports->rptPdtOperacionesTerceros($anio, $ctipo);

        $resultados = collect($resultado);

        $csv_end = "\r\n";
        $csv_sep = "|";

        if ($tipo == 'P') {
            $nombre = "Costos.txt";
        } else {
            $nombre = "Ingresos.txt";
        }

        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {
            $csv .= trim($res->contador) . $csv_sep . trim($res->d_tipodoc) . $csv_sep . trim($res->d_numdoc) . $csv_sep . trim($res->periodo) . $csv_sep . trim($res->tipo_per) . $csv_sep . trim($res->tipo_doc) . $csv_sep . trim($res->num_doc) . $csv_sep . $res->nimporte . $csv_sep . trim($res->ap_pater) . $csv_sep . trim($res->ap_mater) . $csv_sep . trim($res->nombre1) . $csv_sep . trim($res->nombre2) . $csv_sep . trim($res->razon_soc) . $csv_sep . $csv_end;
        }

        //Generamos el csv de todos los datos
        if (!file_exists(public_path() . '/ples/')) {
            mkdir(public_path() . '/ples/', 0777, true);
        }

        $exists = Storage::disk('ples')->exists($nombre);

        if ($exists == false) {
            File::put($csv_file, utf8_decode($csv));
        }

        $r["estado"] = "ok";
        $r["archivo"] = $nombre;

        return $r;
    }
}
