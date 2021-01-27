<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\AccountingPlan\Entities\AccountingPlan;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DailyBookController extends Controller
{
    private $var = 'dailybook';
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

        if ($request->type == null) {
            $tipo = 'A';
        } else {
            $tipo = $request->type;
        }

        $resultado = $this->procedurereports->rptLibroDiario($fecha_inicial, $fecha_final, $tipo);

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
        $data['periods'] = Period::where('descripcion', Session::get('period'))->get();
        $data['currencies'] = Currency::all();
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Ventas','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('dailybook.list', $data);
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

    public function ejecuta_ple_libro_diario(Request $request)
    {
        $a = array('á', 'é', 'í', 'ó', 'ú', '|', '%', '$', '¿', '?', 'Ç', 'ª', 'º', '~', '€', '¬', 'ç', '¡', '/', '•');
        $b = array('a', 'e', 'i', 'o', 'u', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ');

        $period = Period::findOrFail(Session::get('period_id'));

        if ($request->initialdate == null) {
            $fecha_inicio = spanishdate($period->initialdate);
        } else {
            $fecha_inicio = spanishdate($request->initialdate);
        }

        if ($request->finishdate == null) {
            $fecha_fin = spanishdate($period->final);
        } else {
            $fecha_fin = spanishdate($request->finishdate);
        }

        if ($request->type == null) {
            $tipo = 'A';
        } else {
            $tipo = $request->tipo;
        }
        //validación para q se ejecute cuando se ha precionado ejecutar
        $resultado = $this->procedurereports->rptLibroDiario($fecha_inicio, $fecha_fin, $tipo);
        $resultados = collect($resultado);

        $codigo_periodo = $this->obtener_cod_periodo($fecha_inicio, $fecha_fin);
        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00050100001111.txt";
        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {

            if (trim($res->ref_tipodoc) == '') {
                $ref_tipodoc = '00';
                $ref_seriedoc = '';
                $ref_numerodoc = '0';
            } elseif (trim($res->ref_tipodoc) == '05' || trim($res->ref_tipodoc) == '55') {
                $ref_tipodoc = trim($res->ref_tipodoc);
                $ref_seriedoc = right(trim($res->ref_seriedoc), 1);
                $ref_numerodoc = right(trim($res->ref_numerodoc), 11);
            } elseif (trim($res->ref_tipodoc) == '50' || trim($res->ref_tipodoc) == '51' || trim($res->ref_tipodoc) == '52' || trim($res->ref_tipodoc) == '53' || trim($res->ref_tipodoc) == '54') {
                $ref_tipodoc = trim($res->ref_tipodoc);
                $ref_seriedoc = right(trim($res->ref_seriedoc), 3);
                $ref_numerodoc = right(trim($res->ref_numerodoc), 6);
            } else {
                $ref_tipodoc = trim($res->ref_tipodoc);
                $ref_seriedoc = right(trim($res->ref_seriedoc), 4);
                $ref_numerodoc = right(trim($res->ref_numerodoc), 8);
            }

            if ($res->ref_fecha == '') {
                $ref_fecha = '';
            } else {
                $ref_fecha = spanishdate($res->ref_fecha);
            }

            if ($res->ref_vence == '') {
                $ref_vence = '';
            } else {
                $ref_vence = spanishdate($res->ref_vence);
            }

            if ($res->fecha == '') {
                $fecha = '';
            } else {
                $fecha = $res->fecha;
            }

            if (intval(substr(fecha_sin_guion(spanishdate($fecha)), 0, 6)) < intval($res->periodo)) {
                $final = 8;
            } else {
                $final = 1;
            }

            $csv .= trim($res->periodo) . '00' . $csv_sep . $res->cuo . $csv_sep . $res->cuo_item . $csv_sep . trim($res->cuenta) . $csv_sep . trim($res->actividad) . $csv_sep . trim($res->ccosto) . $csv_sep . trim($res->moneda) . $csv_sep . trim($res->ter_tipodoc) . $csv_sep . trim($res->ter_numerodoc) . $csv_sep . $ref_tipodoc . $csv_sep . $ref_seriedoc . $csv_sep . $ref_numerodoc . $csv_sep . $ref_fecha . $csv_sep . $ref_vence . $csv_sep . fecha_a_espanol($res->fecha) . $csv_sep . str_replace($a, $b, $res->glosa) . $csv_sep . $csv_sep . $res->cargomn . $csv_sep . $res->abonomn . $csv_sep . $res->ref_libro . $csv_sep . $final . $csv_sep . $csv_end;
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

    public function ejecuta_ple_libro_diario_cuentas(Request $request)
    {
        $a = array('|', '/');
        $b = array(' ', '-');

        $fecha = fecha_sin_guion($request->finishdate);
        $nombre = "LE" . Session::get('company_ruc') . substr(fecha_sin_guion($request->finishdate), 0, 6) . "00050300001111.txt";
        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        $pempresa = validapempresa('PCG_TIPO', 'CONTAB', 'Plan de Cuentas usado por la empresa', '');
        $plan_cont = $pempresa->valor;
        $cuentas = AccountingPlan::listado_cuentas_ple();

        foreach ($cuentas as $cuenta) {
            $csv .= $fecha . $csv_sep . trim($cuenta->codigo) . $csv_sep . right(str_replace($a, $b, trim($cuenta->descripcion)), 100) . $csv_sep . trim($plan_cont) . $csv_sep . $csv_sep . $csv_sep . '-' . $csv_sep . '1' . $csv_sep . $csv_end;
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

    protected function obtener_cod_periodo($fecha_inicio, $fecha_fin)
    {
        $periodo = Period::obtener_periodo($fecha_inicio, $fecha_fin);
        return $periodo->codigo;
    }
}
