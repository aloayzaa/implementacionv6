<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DetailMovementCashController extends Controller
{
    private $var = 'detailmovementcash';
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

        $resultado = $this->procedurereports->rptLibroBanboDetalle(1, $period);

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
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Detalle de Movimientos del Efectivo', '');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('detailmovementcash.list', $data);
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

    public function reporte_movimiento_efectivo_ple(Request $request)
    {
        $a = array('|');
        $b = array(' ');

        if ($request->period == null) {
            $periodo_id = Session::get('period_id');
        } else {
            $periodo_id = $request->period;
        }
        $tipo = 1;

        $resultado = $this->procedurereports->rptLibroBanboDetalle($tipo, $periodo_id);
        $resultados = collect($resultado);

        $periodo = Period::findOrFail($periodo_id);
        $codigo_periodo = $periodo->codigo;

        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00010100001111.txt";

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

            if ($res->ref_fechacon == '') {
                $ref_fechacon = '';
            } else {
                $ref_fechacon = spanishdate($res->ref_fechacon);
            }

            if ($res->ref_fechaven == '') {
                $ref_fechaven = '';
            } else {
                $ref_fechaven = spanishdate($res->ref_fechaven);
            }

            if ($res->fechaproceso == '') {
                $fechaproceso = '';
            } else {
                $fechaproceso = spanishdate($res->fechaproceso);
            }

            if ($res->voucher != '' and strtoupper($res->estado) != 'ANULADO') {
                if ($res->ref_seriedoc != '') {
                    if ($ref_fechaven != '') {
                        $csv .= $res->periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->ctacon) . $csv_sep . trim($res->unegocio) . $csv_sep . $csv_sep . trim($res->moneda) . $csv_sep . $ref_tipodoc . $csv_sep . $ref_seriedoc . $csv_sep . $ref_numerodoc . $csv_sep . $ref_fechacon . $csv_sep . $ref_fechaven . $csv_sep . $fechaproceso . $csv_sep . str_replace($a, $b, trim($res->glosa)) . $csv_sep . str_replace($a, $b, trim($res->ref_glosa)) . $csv_sep . $res->ingresomn . $csv_sep . $res->egresomn . $csv_sep . $res->ref_libro . $csv_sep . '1' . $csv_sep . $csv_end;
                    } else {
                        $csv .= $res->periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->ctacon) . $csv_sep . trim($res->unegocio) . $csv_sep . $csv_sep . trim($res->moneda) . $csv_sep . $ref_tipodoc . $csv_sep . $ref_seriedoc . $csv_sep . $ref_numerodoc . $csv_sep . $ref_fechacon . $csv_sep . $ref_fechacon . $csv_sep . $fechaproceso . $csv_sep . str_replace($a, $b, trim($res->glosa)) . $csv_sep . str_replace($a, $b, trim($res->ref_glosa)) . $csv_sep . $res->ingresomn . $csv_sep . $res->egresomn . $csv_sep . $res->ref_libro . $csv_sep . '1' . $csv_sep . $csv_end;
                    }
                } else {
                    $csv .= $res->periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->ctacon) . $csv_sep . trim($res->unegocio) . $csv_sep . $csv_sep . trim($res->moneda) . $csv_sep . $ref_tipodoc . $csv_sep . $ref_seriedoc . $csv_sep . $ref_numerodoc . $csv_sep . $fechaproceso . $csv_sep . $csv_sep . $fechaproceso . $csv_sep . str_replace($a, $b, trim($res->glosa)) . $csv_sep . str_replace($a, $b, trim($res->ref_glosa)) . $csv_sep . $res->ingresomn . $csv_sep . $res->egresomn . $csv_sep . $res->ref_libro . $csv_sep . '1' . $csv_sep . $csv_end;
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
