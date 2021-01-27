<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class MonthlyIncomeTaxController extends Controller
{
    private $var = 'monthlyincometax';
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
        $data['view'] = link_view('Gestión Tributaria', 'Procesos', 'PDT 621 - IGV Renta Mensual', '');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['periods'] = Period::all();

        return view('monthlyincometax.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->period == null) {
            $id_periodo = Session::get('period_id');
        } else {
            $id_periodo = $request->period;
        }

        if ($request->type == null) {
            $tipo = "H";
        } else {
            $tipo = $request->type;
        }

        $ctipo = '621' . $tipo;

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

    public function reporte_igv_renta_mensual_pdt621(Request $request)
    {
        if ($request->period == null) {
            $id_periodo = Session::get('period_id');
        } else {
            $id_periodo = $request->period;
        }

        if ($request->type == null) {
            $tipo = "H";
        } else {
            $tipo = $request->type;
        }

        $ctipo = '621' . $tipo;

        $resultado = $this->procedurereports->rptPdtOperacionesTerceros($id_periodo, $ctipo);
        $resultados = collect($resultado);

        $csv_end = "\r\n";
        $csv_sep = "|";
        $periodo = Period::findOrFail($id_periodo);
        $codigo_periodo = $periodo->codigo;

        if ($tipo == 'L') {
            $nombre = Session::get('company_ruc') . $codigo_periodo . ".txt";
        } else {
            $nombre = Session::get('company_ruc') . $codigo_periodo . $tipo . ".txt";
        }
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {
            if ($res->fecha == '') {
                $fecha = '';
            } else {
                $fecha = $res->fecha;
            }

            if ($tipo == 'L') {
                $csv .= trim($res->ruc) . $csv_sep . ceros_izquierda($res->serie, 3) . $csv_sep . trim($res->numero) . $csv_sep . $fecha . $csv_sep . $res->retencion . $csv_sep . $csv_end;
            } elseif ($tipo == 'H') {
                $csv .= trim($res->ruc) . $csv_sep . trim($res->apaterno) . $csv_sep . trim($res->amaterno) . $csv_sep . trim($res->nombre) . $csv_sep . $res->serie . $csv_sep . $res->numero . $csv_sep . $fecha . $csv_sep . $res->monto . $csv_sep . $res->retencion . $csv_sep . $res->tasa . $csv_sep . $res->ies . $csv_sep . $res->nroform . $csv_sep . $csv_end;
            } elseif ($tipo == 'R') {
                $csv .= trim($res->nrodocide) . $csv_sep . substr($res->ret_numero, 2, 4) . $csv_sep . right(trim($res->ret_numero), 8) . $csv_sep . $fecha . $csv_sep . $res->totalret . $csv_sep . trim($res->tipodoc) . $csv_sep . substr($res->numerodoc, 2, 4) . $csv_sep . $res->totalmn . $csv_sep . $csv_end;
            } else {

                if ($res->fecha_doc == '') {
                    $fecha_doc = '';
                } else {
                    $fecha_doc = $res->fecha_doc;
                }

                if ($res->tipo = 0) {
                    $csv .= trim($res->ruc) . $csv_sep . trim($res->serie) . $csv_sep . trim($res->ret_numero) . $csv_sep . trim($res->numero) . $csv_sep . $fecha . $csv_sep . $res->percepcion . $csv_sep . trim($res->tipodoc) . $csv_sep . trim($res->seriedoc) . $csv_sep . trim($res->numerodoc) . $csv_sep . $fecha_doc . $csv_sep . $res->totalmn . $csv_sep . $csv_end;
                } else {
                    $csv .= trim($res->ruc) . $csv_sep . trim($res->tipodoc) . $csv_sep . trim($res->seriedoc) . $csv_sep . trim($res->numerodoc) . $csv_sep . $fecha_doc . $csv_sep . $res->totalmn . $csv_sep . $csv_end;
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
