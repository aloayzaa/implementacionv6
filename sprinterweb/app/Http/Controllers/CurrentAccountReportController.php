<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CurrentAccountReportController extends Controller
{
    private $var = 'currentaccountreport';
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

        if ($request->currency == null) {
            $lctipo = '12';
        } else {
            $lctipo = $request->currency;
        }

        if ($lctipo == 12) {
            $lctipo1 = '13';
        } elseif ($lctipo == 16) {
            $lctipo1 = '17';
        } elseif ($lctipo == 42) {
            $lctipo1 = '43';
        } elseif ($lctipo == 46) {
            $lctipo1 = '47';
        } elseif ($lctipo == 37) {
            $lctipo1 = '49';
        } else {
            $lctipo1 = '00';
        }

        $resultado = $this->procedurereports->rptLibroId12($period, $lctipo, $lctipo1);

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
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Saldos Cuenta Corriente', '');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('currentaccountreport.list', $data);
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

    public function reporte_balance_cuentas_corrientes_ple(Request $request)
    {
        if ($request->period == null) {
            $periodo_id = Session::get('period_id');
        } else {
            $periodo_id = $request->period;
        }

        if ($request->currency == null) {
            $moneda = '12';
        } else {
            $moneda = $request->currency;
        }

        if ($moneda == 12) {
            $lctipo1 = '13';
        } elseif ($moneda == 16) {
            $lctipo1 = '17';
        } elseif ($moneda == 42) {
            $lctipo1 = '43';
        } elseif ($moneda == 46) {
            $lctipo1 = '47';
        } elseif ($moneda == 37) {
            $lctipo1 = '49';
        } else {
            $lctipo1 = '00';
        }

        $resultado = $this->procedurereports->rptLibroId12($periodo_id, $moneda, $lctipo1);
        $resultados = collect($resultado);

        $periodo = Period::findOrFail($periodo_id);
        $codigo_periodo = $periodo->codigo;

        $csv_end = "\r\n";
        $csv_sep = "|";

        $nombre = "";

        switch ($moneda) {
            case 12:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31030300" . $moneda . ".txt";

                break;
            case 14:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31030400" . $moneda . ".txt";
                break;
            case 16:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31030500" . $moneda . ".txt";
                break;
            case 19:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31030600" . $moneda . ".txt";
                break;
            case 41:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31031100" . $moneda . ".txt";
                break;
            case 42:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31031200" . $moneda . ".txt";
                break;
            case 46:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31031300" . $moneda . ".txt";
                break;
            case 47:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31031400" . $moneda . ".txt";
                break;
            case 37:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31031500" . $moneda . ".txt";
                break;
            case 50:
                $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31031600" . $moneda . ".txt";
                break;
        }

        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {

            if ($res->fechadoc == '') {
                $fechadoc = '';
            } else {
                $fechadoc = spanishdate($res->fechadoc);
            }

            $posnom = strpos($res->documento, '-', 1);
            $seriedoc = left($res->documento, $posnom - 1);
            $numerodoc = substr($res->documento, $posnom + 1, 50);

            if ($res->nrodocide != '') {
                if ($moneda == 37) {
                    $csv .= $codigo_periodo . '31' . $csv_sep . trim($res->operacion) . $csv_sep . trim($res->ope_item) . $csv_sep . trim($res->tipodoc) . $csv_sep . trim($seriedoc) . $csv_sep . trim($numerodoc) . $csv_sep . trim($res->cuenta) . $csv_sep . trim($res->glosa) . $csv_sep . $res->saldomn . $csv_sep . $res->adicion . $csv_sep . $res->deduccion . $csv_sep . '1' . $csv_sep . $csv_end;
                } elseif ($moneda == 41) {
                    $csv .= $codigo_periodo . '31' . $csv_sep . trim($res->operacion) . $csv_sep . trim($res->ope_item) . $csv_sep . trim($res->cuenta) . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . left(trim($res->razonsocial), 100) . $csv_sep . $res->saldomn . $csv_sep . '1' . $csv_sep . $csv_end;
                } elseif ($moneda == 42) {
                    $csv .= $codigo_periodo . '31' . $csv_sep . trim($res->operacion) . $csv_sep . trim($res->ope_item) . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . $fechadoc . $csv_sep . left(trim($res->razonsocial), 100) . $csv_sep . $res->saldomn . $csv_sep . '1' . $csv_sep . $csv_end;
                } elseif ($moneda == 46) {
                    $csv .= $codigo_periodo . '31' . $csv_sep . trim($res->operacion) . $csv_sep . trim($res->ope_item) . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . $fechadoc . $csv_sep . left(trim($res->razonsocial), 100) . $csv_sep . trim($res->cuenta) . $csv_sep . $res->saldomn . $csv_sep . '1' . $csv_sep . $csv_end;
                } else {
                    $csv .= $codigo_periodo . '31' . $csv_sep . trim($res->operacion) . $csv_sep . trim($res->ope_item) . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . left(trim($res->razonsocial), 100) . $csv_sep . $fechadoc . $csv_sep . $res->saldomn . $csv_sep . '1' . $csv_sep . $csv_end;
                }
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
