<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DetailMovementAccountController extends Controller
{
    private $var = 'detailmovementaccount';
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

        $resultado = $this->procedurereports->rptLibroBanboDetalle(0, $period);

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
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Detalle de Movement de la Cuenta Corriente', '');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('detailmovementaccount.list', $data);
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

    public function reporte_movimiento_ctacte_ple(Request $request)
    {
        $a = array('|');
        $b = array(' ');

        if ($request->period == null) {
            $periodo_id = Session::get('period_id');
        } else {
            $periodo_id = $request->period;
        }

        $resultado = $this->procedurereports->rptLibroBanboDetalle(0, $periodo_id);
        $resultados = collect($resultado);

        $periodo = Period::findOrFail($periodo_id);
        $codigo_periodo = $periodo->codigo;
        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00010200001111.txt";

        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {

            if ($res->fechaproceso == '') {
                $fechaproceso = '';
            } else {
                $fechaproceso = spanishdate($res->fechaproceso);
            }

            if (trim($res->transaccion) != '') {
                $transaccion = trim($res->transaccion);
            } else {
                $transaccion = ceros_izquierda($res->numero, 7);
            }

            if ($res->voucher != '' and strtoupper($res->estado) != 'ANULADO') {
                $csv .= $res->periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->banco) . $csv_sep . left(trim($res->ctacte), 30) . $csv_sep . $fechaproceso . $csv_sep . trim($res->mediopago) . $csv_sep . str_replace($a, $b, trim($res->glosa)) . $csv_sep . trim($res->tipodoc) . $csv_sep . trim($res->nrodoc) . $csv_sep . trim($res->nombre) . $csv_sep . $transaccion . $csv_sep . $res->ingresomn . $csv_sep . $res->egresomn . $csv_sep . '1' . $csv_sep . $csv_end;
            }
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
