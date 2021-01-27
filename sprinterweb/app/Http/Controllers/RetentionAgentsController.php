<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class RetentionAgentsController extends Controller
{
    private $var = 'retentionagents';
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

        return view('retentionagents.list', $data);
    }

    public function list(Request $request)
    {
        if ($request->period == null) {
            $id_periodo = Session::get('period_id');
        } else {
            $id_periodo = $request->id_periodo;
        }

        $period = Period::findOrFail(Session::get('period_id'));

        if ($request->initialdate == null) {
            $fecha_inicio = $period->inicio;
        } else {
            $fecha_inicio = $request->fecha_inicio;
        }

        if ($request->finishdate == null) {
            $fecha_fin = $period->final;
        } else {
            $fecha_fin = $request->finishdate;
        }

        if ($request->type == null) {
            $tipo = "P";
        } else {
            $tipo = $request->type;
        }

        if ($tipo == 'P') {
            $resultado = $this->procedurereports->rptPdtOperacionesTerceros($id_periodo, '626');
        } else {
            $resultado = $this->procedurereports->rptDiarioRetencion($fecha_inicio, $fecha_fin, 'R');
        }

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

    public function reporte_agentes_de_retencion_pdt(Request $request)
    {
        if ($request->period == null) {
            $id_periodo = Session::get('period_id');
        } else {
            $id_periodo = $request->period;
        }

        if ($request->type == null) {
            $tipo = "P";
        } else {
            $tipo = $request->type;
        }

        $period = Period::findOrFail(Session::get('period_id'));
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

        if ($tipo == 'P') {
            $ctipo = '626';
            $resultado = $this->procedurereports->rptPdtOperacionesTerceros($id_periodo, $ctipo);

            $periodo = Period::findOrFail($id_periodo);
            $codigo_periodo = $periodo->codigo;

            $nombre = '0626' . Session::get('company_ruc') . $codigo_periodo . ".txt";
        } else {
            $ctipo = 'R';
            $resultado = $this->procedurereports->rptDiarioRetencion($fecha_inicio, $fecha_fin, $ctipo);
            $this->obtener_cod_periodo($fecha_inicio, $fecha_fin);

            $nombre = Session::get('company_ruc') . '-20-' . $fecha_inicio . "-1.txt";
        }

        $resultados = collect($resultado);
        $csv_end = "\r\n";
        $csv_sep = "|";

        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {
            if ($tipo == 'P') {
                if ($res->ret_fecha == '') {
                    $ret_fecha = '';
                } else {
                    $ret_fecha = $res->ret_fecha;
                }

                if ($res->fechadoc == '') {
                    $fechadoc = '';
                } else {
                    $fechadoc = $res->fechadoc;
                }

                $csv .= trim($res->nrodocide) . $csv_sep . trim($res->rsocial) . $csv_sep . trim($res->apaterno) . $csv_sep . trim($res->amaterno) . $csv_sep . trim($res->nombres) . $csv_sep . substr($res->ret_numero, 2, 4) . $csv_sep . right(trim($res->ret_numero), 8) . $csv_sep . $ret_fecha . $csv_sep . trim($res->tipodoc) . $csv_sep . substr($res->numerodoc, 2, 4) . $csv_sep . right(trim($res->numerodoc), 8) . $csv_sep . $fechadoc . $csv_sep . $res->totalmn . $csv_sep . $csv_end;
            } else {
                if ($res->ret_fecha == '') {
                    $ret_fecha = '          ';
                } else {
                    $ret_fecha = $res->ret_fecha;
                }

                if ($res->mon_fecha == '') {
                    $mon_fecha = '          ';
                } else {
                    $mon_fecha = $res->mon_fecha;
                }

                if ($res->ref_fechadoc == '') {
                    $ref_fechadoc = '';
                } else {
                    $ref_fechadoc = $res->ref_fechadoc;
                }

                if ($res->pag_fecha == '') {
                    $pag_fecha = '';
                } else {
                    $pag_fecha = $res->pag_fecha;
                }

                $array1 = array('01', '03', '07', '08');
                if (in_array(trim($res->ref_tipodoc), $array1)) {
                    $ref_seriedoc = right(trim($res->ref_seriedoc), 4);
                } else {
                    $ref_seriedoc = trim($res->ref_seriedoc);
                }

                $csv .= trim($res->motivo) . $csv_sep . trim($res->serieret) . $csv_sep . trim($res->numeroret) . $csv_sep . $res->fecharet . $csv_sep . trim($res->nrodocide) . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->rsocial) . $csv_sep . trim($res->tre_regimen) . $csv_sep . trim($res->tre_tasa) . $csv_sep . trim($res->tre_retenido) . $csv_sep . trim($res->tre_pagado) . $csv_sep . trim($res->ref_tipodoc) . $csv_sep . $ref_seriedoc . $csv_sep . trim($res->ref_numerodoc) . $csv_sep . $ref_fechadoc . $csv_sep . $res->ref_total . $csv_sep . trim($res->ref_moneda) . $csv_sep . $pag_fecha . $csv_sep . trim($res->pag_numero) . $csv_sep . $res->ref_total . $csv_sep . trim($res->pag_moneda) . $csv_sep . $res->ret_importe . $csv_sep . $ret_fecha . $csv_sep . trim($res->pag_total) . $csv_sep . trim($res->mon_ext) . $csv_sep . round($res->mon_tcambio, 3) . $csv_sep . $mon_fecha . $csv_sep . $csv_end;
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
