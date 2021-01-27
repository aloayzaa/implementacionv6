<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PermanentInventoryController extends Controller
{
    private $var = 'permanentinventory';
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
        $data['view'] = link_view('Gestión Tributaria', 'Reportes', 'Inventario Permanente', '');
        $data['period'] = Period::findOrFail(Session::get('period_id'));

        return view('permanentinventory.list', $data);
    }

    public function list(Request $request)
    {
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

        $resultado = $this->procedurereports->rptLibroKardex(1, 1, $fecha_inicio, $fecha_fin);

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

    public function reporte_inventario_permante_ple(Request $request)
    {
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

        if ($request->type == null) {
            $tipo = 0;
        } else {
            $tipo = $request->type;
        }

        $resultado = $this->procedurereports->rptLibroKardex(1, 1, $fecha_inicio, $fecha_fin);
        $resultados = collect($resultado);

        $codigo_periodo = $this->obtener_cod_periodo($fecha_inicio, $fecha_fin);
        if ($tipo == 0) {
            $lctipo = '00120100';
        } else {
            $lctipo = '00130100';
        }

        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . $lctipo . "001111.txt";
        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {

            if (trim($res->tipodoc) == null) {
                $tipodoc = '00';
                $seriedoc = '0';
                $numerodoc = '0';
            } elseif (trim($res->tipodoc) == '05' || trim($res->tipodoc) == '55') {
                $tipodoc = trim($res->tipodoc);
                $seriedoc = right(trim($res->seriedoc), 1);
                $numerodoc = right(trim($res->numerodoc), 11);
            } elseif (trim($res->tipodoc) == '50' || trim($res->tipodoc) == '51' || trim($res->tipodoc) == '52' || trim($res->tipodoc) == '53' || trim($res->tipodoc) == '54') {
                $tipodoc = trim($res->tipodoc);
                $seriedoc = right(trim($res->seriedoc), 3);
                $numerodoc = right(trim($res->numerodoc), 6);
            } else {
                $tipodoc = trim($res->tipodoc);
                $seriedoc = right(trim($res->seriedoc), 4);
                $numerodoc = right(trim($res->numerodoc), 8);
            }

            if ($res->fecha == null) {
                $fecha = '';
            } else {
                $fecha = spanishdate($res->fecha);
            }

            if ($res->ingresos == 0) {
                $precioingmn = 0;
            } else {
                $precioingmn = $res->preciomn;
            }

            if ($res->salidas == 0) {
                $preciosalmn = 0;
            } else {
                $preciosalmn = $res->preciomn;
            }

            if ($tipo == 1) {
                if (left($res->operacion, 2) == '16') {
                    $csv .= $codigo_periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->alm_cod) . $csv_sep . '9' . $csv_sep . trim($res->tipo) . $csv_sep . trim($res->codigo) . $csv_sep . '0000000000000000' . $csv_sep . $fecha . $csv_sep . $tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . left($res->operacion, 2) . $csv_sep . left(trim($res->descripcion), 80) . $csv_sep . $res->ume_sunat . $csv_sep . '1' . $csv_sep . $res->saldo . $csv_sep . $precioingmn . $csv_sep . $res->saldomn . $csv_sep . $res->salidas . $csv_sep . $preciosalmn . $csv_sep . $res->salidamn . $csv_sep . $res->saldo . $csv_sep . $preciosalmn . $csv_sep . $res->saldomn . $csv_sep . '1' . $csv_sep . $csv_end;
                } else {
                    $csv .= $codigo_periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->alm_cod) . $csv_sep . '9' . $csv_sep . trim($res->tipo) . $csv_sep . trim($res->codigo) . $csv_sep . '0000000000000000' . $csv_sep . $fecha . $csv_sep . $tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . left($res->operacion, 2) . $csv_sep . left(trim($res->descripcion), 80) . $csv_sep . $res->ume_sunat . $csv_sep . '1' . $csv_sep . $res->ingresos . $csv_sep . $precioingmn . $csv_sep . $res->ingresomn . $csv_sep . $res->salidas . $csv_sep . $preciosalmn . $csv_sep . $res->salidamn . $csv_sep . $res->saldo . $csv_sep . $preciosalmn . $csv_sep . $res->saldomn . $csv_sep . '1' . $csv_sep . $csv_end;
                }
            } else {
                if (left($res->operacion, 2) == '16') {
                    $csv .= $codigo_periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->alm_cod) . $csv_sep . '9' . $csv_sep . trim($res->tipo) . $csv_sep . trim($res->codigo) . $csv_sep . '0000000000000000' . $csv_sep . $fecha . $csv_sep . $tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . left($res->operacion, 2) . $csv_sep . left(trim($res->descripcion), 80) . $csv_sep . $res->ume_sunat . $csv_sep . $res->saldo . $csv_sep . ($res->salidas * -1) . $csv_sep . '1' . $csv_sep . $csv_end;
                } else {
                    $csv .= $codigo_periodo . '00' . $csv_sep . trim($res->voucher_id) . $csv_sep . trim($res->voucher_item) . $csv_sep . trim($res->alm_cod) . $csv_sep . '9' . $csv_sep . trim($res->tipo) . $csv_sep . trim($res->codigo) . $csv_sep . '0000000000000000' . $csv_sep . $fecha . $csv_sep . $tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . left($res->operacion, 2) . $csv_sep . left(trim($res->descripcion), 80) . $csv_sep . $res->ume_sunat . $csv_sep . $res->ingresos . $csv_sep . ($res->salidas * -1) . $csv_sep . '1' . $csv_sep . $csv_end;
                }
            }
        }

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
