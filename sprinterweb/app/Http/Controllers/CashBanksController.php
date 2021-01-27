<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CashBanksController extends Controller
{
    private $var = 'cashbanks';
    private $procedurereports;

    public function __construct(ProcedureReports $procedurereports)
    {
        $this->obtener_cliente();
        $this->procedurereports = $procedurereports;
    }

    public function list(Request $request)
    {
        if ($request->period == null) {
            $period = Session::get('period_id');
        } else {
            $period = $request->period;
        }

        $resultado = $this->procedurereports->rptLibroId10($period, '10');

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
        $data['periods'] = Period::where('descripcion', Session::get('period'))->get();
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Saldos cuenta 10 Caja y Bancos', '');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('cashbanks.list', $data);
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

    public function reporte_balance_caja_banco_ple(Request $request)
    {
        if ($request->period == null) {
            $periodo_id = Session::get('period_id');
        } else {
            $periodo_id = $request->period;
        }

        $resultado = $this->procedurereports->rptLibroId10($periodo_id, '10');
        $resultados = collect($resultado);

        $periodo = Period::findOrFail($periodo_id);
        $codigo_periodo = $periodo->codigo;

        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00030200011111.txt";

        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {

            if ($res->moneda == 'PEN SOLES') {
                $cargo = $res->cargomn;
                $abono = $res->abonomn;
            } else {
                $cargo = $res->cargome;
                $abono = $res->abonome;
            }
            $csv .= $codigo_periodo . '31' . $csv_sep . trim($res->cuenta) . $csv_sep . substr($res->banco, 0, 2) . $csv_sep . trim($res->nrocuenta) . $csv_sep . $res->moneda . $csv_sep . $cargo . $csv_sep . $abono . $csv_sep . '1' . $csv_sep . $csv_end;
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
