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

class CheckingBalanceController extends Controller
{
    private $var = 'checkingbalance';
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

        if ($request->currency == null) {
            $moneda = 'N';
        } else {
            $moneda = $request->currency;
        }

        if ($request->digits == null) {
            $tamanio = 2;
        } else {
            $tamanio = $request->digits;
        }

        if ($request->checkclosing == null) {
            $chk_cierre = 0;
        } else {
            $chk_cierre = $request->checkclosing;
        }

        $resultado = $this->procedurereports->rptBalanceComprobacion($fecha_inicial, $fecha_final, $moneda, $tamanio, $chk_cierre);
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
        $data['currencies'] = Currency::all();
        $data['view'] = link_view('Contabilidad', 'Reportes', 'Balance de Comproboción', '');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('checkingbalance.list', $data);
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

    public function reporte_balance_comprobacion_ple(Request $request)
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

        if ($request->currency == null) {
            $moneda = 'N';
        } else {
            $moneda = $request->currency;
        }

        if ($request->digits == null) {
            $tamanio = 2;
        } else {
            $tamanio = $request->digits;
        }

        $chk_cierre = validaCheck($request->checkclosing);

        $resultado = $this->procedurereports->rptBalanceComprobacion($fecha_inicio, $fecha_fin, $moneda, $tamanio, $chk_cierre);
        $resultados = collect($resultado);

        $codigo_periodo = $this->obtener_cod_periodo($fecha_inicio, $fecha_fin);
        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "31010200001111.txt";
        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        foreach ($resultados as $res) {

            if ($res->iniciald > 0) {
                $cad1 = $res->iniciald . $csv_sep . '0.00';
            } else {
                $cad1 = '0.00' . $csv_sep . $res->inicialh;
            }

            if ($res->finald > 0) {
                $cad2 = $res->finald . $csv_sep . '0.00';
            } else {
                $cad2 = '0.00' . $csv_sep . $res->finalh;
            }

            if ($res->balanced > 0) {
                $cad3 = $res->balanced . $csv_sep . '0.00';
            } else {
                $cad3 = '0.00' . $csv_sep . $res->balanceh;
            }

            if ($res->naturald > 0) {
                $cad4 = $res->naturald . $csv_sep . '0.00';
            } else {
                $cad4 = '0.00' . $csv_sep . $res->naturalh;
            }

            $csv .= $codigo_periodo . '00' . $csv_sep . $res->codigo . $csv_sep . $cad1 . $csv_sep . $res->movimd . $csv_sep . $res->movimh . $csv_sep . ($res->iniciald + $res->movimd) . $csv_sep . ($res->inicialh + $res->movimh) . $csv_sep . $cad2 . $csv_sep . '0.00|0.00' . $csv_sep . $cad3 . $csv_sep . $cad4 . $csv_sep . '0.00|0.00' . $csv_sep . '1' . $csv_sep . $csv_end;
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

    public function reporte_balance_comprobacion_pdt(Request $request)
    {
        ini_set('max_execution_time', 360);

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

        if ($request->currency == null) {
            $moneda = 'N';
        } else {
            $moneda = $request->currency;
        }

        if ($request->digits == null) {
            $tamanio = 2;
        } else {
            $tamanio = $request->digits;
        }
        $chk_cierre = validaCheck($request->checkclosing);

        $resultado = $this->procedurereports->rptBalanceComprobacion($fecha_inicio, $fecha_fin, $moneda, $tamanio, $chk_cierre);
        $resultados = collect($resultado);

        $codigo_periodo = $this->obtener_cod_periodo($fecha_inicio, $fecha_fin);
        $nombre = "0684" . Session::get('company_ruc') . substr($codigo_periodo, 0, 4) . ".txt";
        $csv_file = public_path() . '/ples/' . $nombre;
        $csv = "";

        //dd($resultados);

        /*foreach ($resultados as $res) {
            $codigo = $res->codigo;
            $iniciald = 0;
            $inicialh = 0;
            $movimd = 0;
            $movimh = 0;

            do {
                $iniciald = $iniciald + $res->iniciald;
                $inicialh = $inicialh + $res->inicialh;
                $movimd = $movimd + $res->movimd;
                $movimh = $movimh + $res->movimh;
            } while ($codigo = $res->codigo);
        }*/

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
