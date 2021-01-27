<?php

namespace App\Http\Controllers;

use App\Currency\Entities\Currency;
use App\ExchangeRate\Entities\ExchangeRate;
use App\NoHabido\Entities\Nohabido;
use App\Period\Entities\Period;
use App\Procedures\ProcedureReports;
use App\TaxExcluded\Entities\TaxExcluded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SalesController extends Controller
{
    private $var = 'sales';
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
        $data['view'] = link_view('Gestión Tributaria', 'Reportes', 'Ventas', '');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['currencies'] = Currency::all();

        return view('sales.list', $data);
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

        $chk_agrupa = validaCheck($request->checkdocuments);

        $resultado = $this->procedurereports->rptVentasRegistro($fecha_inicio, $fecha_fin, $chk_agrupa);

        $resultados = array();

        foreach ($resultado as $res) {
            $nohab = Nohabido::buscar_codigo($res->nrodocide);
            if (!empty($nohab)) {
                $nohabido = Nohabido::findOrFail($nohab->id);
                $fechadoc = date('Y-m-d', strtotime($res->fechadoc));
                $desde = date('Y-m-d', strtotime($nohabido->desde));
                $hasta = date('Y-m-d', strtotime("2999-12-31"));
                if (($fechadoc > $desde) && ($fechadoc < $hasta)) {
                    $res->error2 = 1;
                }
            }

            $renexo = TaxExcluded::buscar_codigo($res->nrodocide);
            if (!empty($renexo)) {
                $RenunciaExo = TaxExcluded::findOrFail($renexo->id);
                $fechadoc = date('Y-m-d', strtotime($res->fechadoc));
                $desde = date('Y-m-d', strtotime($RenunciaExo->desde));
                if ($fechadoc > $desde) {
                    $res->error3 = 1;
                }
            }

            $tcbio = ExchangeRate::buscar_cambio(str_replace("-", "", $res->fechadoc));
            if (!empty($tcbio)) {
                $tcambio = ExchangeRate::findOrFail($tcbio->id);
                if ($res->tipodoc != '14' || $res->tipodoc != '07') {
                    if ($res->tcambio != $tcambio->t_venta) {
                        $res->error1 = 1;
                    }
                }
            }

            array_push($resultados, $res);
        }

        $resultados = collect($resultados);

        return Datatables::of($resultados)->make(true);
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

    public function reporte_ventas_ple(Request $request)
    {
        $tipo = $request->tipo;

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

        $chk_agrupa = validaCheck($request->checkdocuments);

        if ($request->currency == null) {
            $moneda = 'N';
        } else {
            $moneda = $request->moneda;
        }

        $resultado = $this->procedurereports->rptVentasRegistro($fecha_inicio, $fecha_fin, '0');
        $resultados = collect($resultado);
        $codigo_periodo = $this->obtener_cod_periodo($fecha_inicio, $fecha_fin);
        $csv_end = "\r\n";
        $csv_sep = "|";
        $csv = "";

        if ($moneda == "N") {
            $lcMon = 1;
        } else {
            $lcMon = 2;
        }

        $i = 0;

        foreach ($resultados as $res) {

            if ($res->fechadoc == '') {
                $fechadoc = '';
            } else {
                $fechadoc = $res->fechadoc;
            }

            if ($res->vencimiento == '') {
                $vencimiento = $fechadoc;
            } else {
                $vencimiento = $res->vencimiento;
            }

            if ($res->fecharef == '') {
                $fecharef = '01/01/0001';
            } else {
                $fecharef = spanishdate($res->fecharef);
            }

            $array1 = array('01', '03', '04', '06', '07', '08', '36', '56');
            $array2 = array('05', '55');
            if (in_array(trim($res->tipodoc), $array1)) {
                $seriedoc = ceros_izquierda(right(trim($res->seriedoc), 4), 4);
                if ($chk_agrupa == 1 && trim($res->tipodoc) == '03') {
                    $numerodoc = str_replace('-', '|', substr(trim($res->numerodoc), 2, 20)) . '|';
                } else {
                    $numerodoc = right(trim($res->numerodoc), 7) . '|';
                }
            } elseif (trim($res->tipodoc) == '12') {
                $seriedoc = trim($res->seriedoc);
                if ($chk_agrupa == 1) {
                    $numerodoc = str_replace('-', '|', trim($res->numerodoc));
                } else {
                    $numerodoc = right(trim($res->numerodoc), 7) . '|';
                }
            } elseif (in_array(trim($res->tipodoc), $array2)) {
                $seriedoc = right(trim($res->seriedoc), 4);
                $numerodoc = right(trim($res->numerodoc), 11) . '|';
            } elseif (trim($res->tipodoc) == '00') {
                $seriedoc = '-';
                $numerodoc = trim($res->numerodoc) . '|';
            } else {
                $seriedoc = trim($res->seriedoc);
                $numerodoc = trim($res->numerodoc) . '|';
            }
            $tipodocide = trim($res->tipodocide);
            $nrodocide = trim($res->nrodocide);
            $nombre = trim($res->nombre);

            $posnro = strpos($nrodocide, '/', 1);
            if ($posnro != '') {
                $nrodocide = left($nrodocide, $posnro - 1);
            }

            $posnom = strpos($nombre, '/', 1);
            if ($posnom != '') {
                $nombre = left($nombre, $posnom - 1);
            }

            if (trim($nrodocide) == '0' || trim($nrodocide) == '-') {
                $nrodocide = '';
            }

            if (trim($tipodocide) == '0' || trim($nrodocide) == '') {
                $tipodocide = '';
            }

            $exporta = $res->exportamn;
            $base = $res->basemn;
            $dsctobase = $res->dsctobasemn;
            $igv = $res->igvmn;
            $dsctoigv = $res->dsctoigvmn;
            $exonera = $res->exoneramn;
            $inafecto = $res->inafectomn;
            $isc = $res->iscmn;
            $baseivap = $res->baseivapmn;
            $ivap = $res->ivapmn;
            $otros = $res->otrosmn;
            $total = $res->totalmn;

            //INICION CADENA REFERENCIA
            $array3 = array('07', '08', '87', '88', '97', '98');
            if (in_array(trim($res->tipodoc), $array3)) {
                if (strtoupper($res->estado) == 'ANULADO') {
                    $cadena = '01/01/0001|00|-|-';
                } else {
                    $tipodocref = trim($res->tipodocref);

                    $array4 = array('01', '03', '04', '07', '08');
                    $array5 = array('50', '52');
                    if (in_array(trim($res->tipodocref), $array4)) {
                        $serieref = right(trim($res->serieref), 4);
                    } elseif (in_array(trim($res->tipodocref), $array5)) {
                        $serieref = left(trim($res->serieref), 3);
                    } else {
                        $serieref = trim($res->serieref);
                    }

                    $cadena = $fecharef . $csv_sep . $tipodocref . $csv_sep . $serieref . $csv_sep . trim($res->numeroref);
                }
            } else {
                $cadena = '01/01/0001|00|-|-';
            }
            //FIN CADENA REFERENCIA

            //Inicicio valida error 1
            $tcbio = ExchangeRate::buscar_cambio(fecha_sin_guion(spanishdate($res->fechadoc)));
            $error1 = $res->error1;
            if (!empty($tcbio)) {
                $tcambio = ExchangeRate::findOrFail($tcbio->id);
                if ($res->tipodoc != '14' || $res->tipodoc != '07') {
                    if ($res->tcambio != $tcambio->t_venta) {
                        $error1 = 1;
                    }
                }
            }
            //Fin valida error 1

            if (strtoupper($res->estado) == 'ANULADO') {
                $estado = 2;
            } else {
                if (intval(substr(fecha_sin_guion(spanishdate($fechadoc)), 0, 6)) < intval($codigo_periodo)) {
                    $estado = 8;
                } else {
                    $estado = 1;
                }
            }

            if ($tipo == 1) {
                if (left(fecha_sin_guion(spanishdate($vencimiento)), 6) > left(fecha_hoy_sin_guion(), 6)) {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . spanishdate($fechadoc) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . $tipodocide . $csv_sep . $nrodocide . $csv_sep . $nombre . $csv_sep . $exporta . $csv_sep . $base . $csv_sep . $dsctobase . $csv_sep . $igv . $csv_sep . $dsctoigv . $csv_sep . $exonera . $csv_sep . $inafecto . $csv_sep . $isc . $csv_sep . $baseivap . $csv_sep . $ivap . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena . $csv_sep . $res->contrato . $csv_sep . $error1 . $csv_sep . $res->mpago . $csv_sep . $estado . $csv_sep . $csv_end;
                } else {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . spanishdate($vencimiento) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . $tipodocide . $csv_sep . $nrodocide . $csv_sep . $nombre . $csv_sep . $exporta . $csv_sep . $base . $csv_sep . $dsctobase . $csv_sep . $igv . $csv_sep . $dsctoigv . $csv_sep . $exonera . $csv_sep . $inafecto . $csv_sep . $isc . $csv_sep . $baseivap . $csv_sep . $ivap . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena . $csv_sep . $res->contrato . $csv_sep . $error1 . $csv_sep . $res->mpago . $csv_sep . $estado . $csv_sep . $csv_end;
                }
            } else {
                if (left(fecha_sin_guion(spanishdate($vencimiento)), 6) > left(fecha_hoy_sin_guion(), 6)) {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . fecha_a_espanol($fechadoc) . $csv_sep . spanishdate($fechadoc) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . $tipodocide . $csv_sep . $nrodocide . $csv_sep . $nombre . $csv_sep . $base . $csv_sep . $igv . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena . $csv_sep . $error1 . $csv_sep . $res->mpago . $csv_sep . $estado . $csv_sep . $csv_end;
                } else {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . fecha_a_espanol($fechadoc) . $csv_sep . spanishdate($vencimiento) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . $tipodocide . $csv_sep . $nrodocide . $csv_sep . $nombre . $csv_sep . $base . $csv_sep . $igv . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena . $csv_sep . $error1 . $csv_sep . $res->mpago . $csv_sep . $estado . $csv_sep . $csv_end;
                }
            }

            $i++;
        }

        if ($i > 0) {
            $lcCont = 1;
        } else {
            $lcCont = 0;
        }

        if ($tipo == 1) {
            $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00140100001" . $lcCont . $lcMon . "1.txt";
        } else {
            $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00140200001" . $lcCont . $lcMon . "1.txt";
        }

        $csv_file = public_path() . '/ples/' . $nombre;

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
