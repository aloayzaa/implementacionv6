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

class BenefitDeclarationController extends Controller
{
    private $var = 'benefitdeclaration';
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
        $data['periods'] = Period::all();

        return view('benefitdeclaration.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->period == null) {
            $id_periodo = Session::get('period_id');
        } else {
            $id_periodo = $request->period;
        }

        if ($request->type == null) {
            $ctipo = 'BV';
        } else {
            $ctipo = 'B' . $request->type;
        }

        $resultado = $this->procedurereports->rptPdtOperacionesTerceros($id_periodo, $ctipo);
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

    public function reporte_programa_beneficios(Request $request)
    {
        if ($request->period == null) {
            $id_periodo = Session::get('period_id');
        } else {
            $id_periodo = $request->period;
        }

        if ($request->type == null) {
            $tipo = "V";
        } else {
            $tipo = $request->type;
        }

        $ctipo = 'B' . $tipo;

        $resultado = $this->procedurereports->rptPdtOperacionesTerceros($id_periodo, $ctipo);
        $resultados = collect($resultado);

        $csv_end = "\r\n";
        $csv_sep = "|";
        $periodo = Period::findOrFail($id_periodo);
        $codigo_periodo = $periodo->codigo;

        if ($tipo == 'T') {
            $nombre = Session::get('company_ruc') . ".tc";
        } else {
            $nombre = Session::get('company_ruc') . $codigo_periodo . ".txt";
        }
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {
            if ($tipo == 'V') {
                $csv .= mysql_field_type($cnsl, 1) . $csv_sep . $csv_end;
            } elseif ($tipo == 'C') {
                $csv .= $csv_sep . 'Aqui falta analizar' . $csv_sep . $csv_end;
            } elseif ($tipo == 'F') {
                $csv .= $csv_sep . 'Aqui falta analizar' . $csv_sep . $csv_end;
            } else {
                if ($res->fecha == '') {
                    $fecha = '';
                } else {
                    $fecha = $res->fecha;
                }
                $csv .= trim(spanishdate($fecha)) . $csv_sep . trim($res->t_compra) . $csv_sep . trim($res->t_venta) . $csv_sep . $csv_end;
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
