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

class PurchasesController extends Controller
{
    private $var = 'purchases';
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
        $data['view'] = link_view('Gestión Tributaria', 'Reportes', 'Compras', '');
        $data['period'] = Period::findOrFail(Session::get('period_id'));
        $data['currencies'] = Currency::all();

        return view('purchases.list', $data);
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

        $chk_documentos = validaCheck($request->checkdocuments);

        $resultado = $this->procedurereports->rptComprasRegistro($fecha_inicio, $fecha_fin);

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

            if ($chk_documentos == 1) {
                if ($res->flag != 0) {
                    array_push($resultados, $res);
                }
            } else {
                array_push($resultados, $res);
            }
        }

        $resultados = collect($resultados);

        return Datatables::of($resultados)->make();
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

    public function reporte_compras_plenormal(Request $request)
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

        $moneda = $request->moneda;
        if ($moneda == '') {
            $moneda = 'N';
        } else {
            $moneda = $request->moneda;
        }

        $chk_documentos_registro_compras = validaCheck($request->checkdocuments);

        $resultado = $this->procedurereports->rptComprasRegistro($fecha_inicio, $fecha_fin);
        $resul = array();
        foreach ($resultado as $res) {
            if ($chk_documentos_registro_compras == 1) {
                if ($res->flag != 0) {
                    array_push($resul, $res);
                }
            } else {
                array_push($resul, $res);
            }
        }

        $resultados = collect($resul);

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

            $array1 = array('01', '02', '03', '04', '06', '07', '08', '23', '25', '34', '35', '36', '46');
            $array2 = array('05', '55');
            $array3 = array('50', '51', '52', '53', '54');
            if (in_array(trim($res->tipodoc), $array1)) {
                $seriedoc = right(trim($res->seriedoc), 4);
                if (trim($res->tipodoc) == '36') {
                    $extra = 0;
                    $numerodoc = right(trim($res->numerodoc), 8) . '|';
                } else {
                    $extra = 0;
                    $numerodoc = right(trim($res->numerodoc), 7) . '|';
                }
            } elseif (in_array(trim($res->tipodoc), $array2)) {
                $seriedoc = right(trim($res->seriedoc), 1);
                $extra = 0;
                $numerodoc = right(trim($res->numerodoc), 11) . '|';
            } elseif (in_array(trim($res->tipodoc), $array3)) {
                $seriedoc = right(trim($res->seriedoc), 3);
                if (trim($res->tipodoc) == '54') {
                    $extra = 0;
                    $numerodoc = trim($res->numerodoc) . '|';
                } else {
                    if (trim($res->tipodoc) == '50' || trim($res->tipodoc) == '52') {
                        $extra = left(trim($res->numerodoc), 4);
                    } else {
                        $extra = right(trim($res->numerodoc), 6);
                    }
                    $numerodoc = trim($res->numerodocexp) . '|';
                }
            } else {
                $seriedoc = trim($res->seriedoc);
                $extra = 0;
                $numerodoc = trim($res->numerodoc) . '|';
            }

            $baseexp = $res->baseexpmn;
            $igvexp = $res->igvexpmn;
            $basemix = $res->basemixmn;
            $igvmix = $res->igvmixmn;
            $baseexo = $res->baseexomn;
            $igvexo = $res->igvexomn;
            $basenog = $res->basenogmn;
            $isc = $res->iscmn;
            $otros = $res->otrosmn;
            $total = $res->totalmn;

            if ($res->fecharef == '') {
                $fecharef = '01/01/0001';
            } else {
                $fecharef = spanishdate($res->fecharef);
            }

            //INICION CADENA REFERENCIA
            $array4 = array('07', '08', '87', '88', '97', '98');
            if (in_array(trim($res->tipodoc), $array4)) {
                $tipodocref = trim($res->tipodocref);

                $array5 = array('50', '52');
                if (in_array(trim($res->tipodocref), $array5)) {
                    $serieref = right(trim($res->serieref), 3);
                    $auxiliar = left(trim($res->numeroref), 4);
                    $numeroref = trim(substr($res->numeroref, 5, 20)) . '|0';
                } else {
                    $serieref = right(trim($res->serieref), 4);
                    $auxiliar = '';
                    $numeroref = trim($res->numeroref);
                }

                $cadena_referencia = $fecharef . $csv_sep . $tipodocref . $csv_sep . $serieref . $csv_sep . $auxiliar . $csv_sep . $numeroref;
            } else {
                $cadena_referencia = '01/01/0001||||';
            }

            if ($res->nrodocdet == '') {
                $nrodocdet = '';
            } else {
                $nrodocdet = $res->nrodocdet;
            }

            if ($res->fechadocdet == '') {
                $fechadocdet = '01/01/0001';
            } else {
                $fechadocdet = spanishdate($res->fechadocdet);
            }

            if (empty($nrodocdet)) {
                $cadena_detrac = '01/01/0001|0';
            } else {
                $cadena_detrac = $fechadocdet . $csv_sep . $nrodocdet;
            }

            if ($res->retencion == '') {
                $retencion = 0;
            } else {
                $retencion = $res->retencion;
            }

            if ($retencion == 0) {
                $retencion = '';
            } else {
                $retencion = 1;
            }

            //Inicio valida error 1
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
            //Inicio valida error 2
            $error2 = $res->error2;
            $nohab = Nohabido::buscar_codigo($res->nrodocide);
            if (!empty($nohab)) {
                $nohabido = Nohabido::findOrFail($nohab->id);
                $fechadoc = date('Y-m-d', strtotime($res->fechadoc));
                $desde = date('Y-m-d', strtotime($nohabido->desde));
                $hasta = date('Y-m-d', strtotime("2999-12-31"));
                if (($fechadoc > $desde) && ($fechadoc < $hasta)) {
                    $error2 = 1;
                }
            }
            //Fin valida error 2
            //Inicio valida error 3
            $error3 = $res->error3;
            $renexo = TaxExcluded::buscar_codigo($res->nrodocide);
            if (!empty($renexo)) {
                $RenunciaExo = TaxExcluded::findOrFail($renexo->id);
                $fechadoc = date('Y-m-d', strtotime($res->fechadoc));
                $desde = date('Y-m-d', strtotime($RenunciaExo->desde));
                if ($fechadoc > $desde) {
                    $error3 = 1;
                }
            }
            //Fin valida error 3

            $array5 = array('02', '03', '10', '15', '16', '21', '22', '44', '45', '46', '49');
            if (in_array(trim($res->tipodoc), $array5)) {
                $final = 0;
            } else {
                if (intval(substr(fecha_sin_guion(spanishdate($fechadoc)), 0, 6)) < intval($res->periodo)) {
                    $final = 6;
                } elseif (fecha_sin_guion(spanishdate($fechadoc)) - fecha_sin_guion(spanishdate($fecha_inicio)) > 365) {
                    $final = 7;
                } else {
                    $final = 1;
                }
            }

            if ($res->nd_flag == 0) {
                if (left(fecha_sin_guion(spanishdate($vencimiento)), 6) > left(fecha_hoy_sin_guion(), 6)) {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . spanishdate($fechadoc) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $extra . $csv_sep . $numerodoc . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . left(trim($res->nombre), 100) . $csv_sep . $baseexp . $csv_sep . $igvexp . $csv_sep . $basemix . $csv_sep . $igvmix . $csv_sep . $baseexo . $csv_sep . $igvexo . $csv_sep . $basenog . $csv_sep . $isc . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena_referencia . $csv_sep . $cadena_detrac . $csv_sep . $retencion . $csv_sep . $res->tipobien . $csv_sep . $res->contrato . $csv_sep . $error1 . $csv_sep . $error2 . $csv_sep . $error3 . $csv_sep . $res->error4 . $csv_sep . $res->mpago . $csv_sep . $final . $csv_sep . $csv_end;
                } else {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . spanishdate($vencimiento) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $extra . $csv_sep . $numerodoc . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . left(trim($res->nombre), 100) . $csv_sep . $baseexp . $csv_sep . $igvexp . $csv_sep . $basemix . $csv_sep . $igvmix . $csv_sep . $baseexo . $csv_sep . $igvexo . $csv_sep . $basenog . $csv_sep . $isc . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena_referencia . $csv_sep . $cadena_detrac . $csv_sep . $retencion . $csv_sep . $res->tipobien . $csv_sep . $res->contrato . $csv_sep . $error1 . $csv_sep . $error2 . $csv_sep . $error3 . $csv_sep . $res->error4 . $csv_sep . $res->mpago . $csv_sep . $final . $csv_sep . $csv_end;
                }
                $i++;
            }
        }

        if ($i > 0) {
            $lcCont = 1;
        } else {
            $lcCont = 0;
        }

        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00080100001" . $lcCont . $lcMon . "1.txt";
        $csv_file = public_path() . '/ples/' . $nombre;

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

    public function reporte_compras_plesimplificado(Request $request)
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

        $moneda = $request->moneda;
        if ($moneda == '') {
            $moneda = 'N';
        } else {
            $moneda = $request->moneda;
        }

        $chk_documentos_registro_compras = validaCheck($request->checkdocuments);

        $resultado = $this->procedurereports->rptComprasRegistro($fecha_inicio, $fecha_fin);
        $resul = array();
        foreach ($resultado as $res) {
            if ($chk_documentos_registro_compras == 1) {
                if ($res->flag != 0) {
                    array_push($resul, $res);
                }
            } else {
                array_push($resul, $res);
            }
        }

        $resultados = collect($resul);

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

            $array1 = array('01', '02', '03', '04', '06', '07', '08', '23', '25', '34', '35', '36');
            $array2 = array('05', '55');
            $array3 = array('50', '51', '52', '53', '54');
            if (in_array(trim($res->tipodoc), $array1)) {
                $seriedoc = right(trim($res->seriedoc), 4);
                if (trim($res->tipodoc) == '36') {
                    $numerodoc = right(trim($res->numerodoc), 8) . '|';
                } else {
                    $numerodoc = right(trim($res->numerodoc), 7) . '|';
                }
            } elseif (in_array(trim($res->tipodoc), $array2)) {
                $seriedoc = right(trim($res->seriedoc), 1);
                $numerodoc = right(trim($res->numerodoc), 11) . '|';
            } elseif (in_array(trim($res->tipodoc), $array3)) {
                $seriedoc = right(trim($res->seriedoc), 3);
                $numerodoc = trim($res->numerodoc) . '|';
            } else {
                $seriedoc = trim($res->seriedoc);
                $numerodoc = trim($res->numerodoc) . '|';
            }

            $baseexp = $res->baseexpmn;
            $igvexp = $res->igvexpmn;
            $otros = $res->otrosmn;
            $total = $res->totalmn;

            if ($res->fecharef == '') {
                $fecharef = '01/01/0001';
            } else {
                $fecharef = spanishdate($res->fecharef);
            }

            //INICION CADENA REFERENCIA
            $array4 = array('07', '08', '87', '88', '97', '98');
            if (in_array(trim($res->tipodoc), $array4)) {
                $tipodocref = trim($res->tipodocref);
                $array5 = array('50', '52');
                if (in_array(trim($res->tipodocref), $array5)) {
                    $serieref = right(trim($res->serieref), 3);
                    $numeroref = trim(substr($res->numeroref, 5, 20)) . '|0';
                } else {
                    $serieref = right(trim($res->serieref), 4);
                    $numeroref = trim($res->numeroref);
                }
                $cadena_referencia = $fecharef . $csv_sep . $tipodocref . $csv_sep . $serieref . $csv_sep . $numeroref;
            } else {
                $cadena_referencia = '01/01/0001|||';
            }

            if ($res->nrodocdet == '') {
                $nrodocdet = '';
            } else {
                $nrodocdet = $res->nrodocdet;
            }

            if ($res->fechadocdet == '') {
                $fechadocdet = '01/01/0001';
            } else {
                $fechadocdet = spanishdate($res->fechadocdet);
            }

            if (empty($nrodocdet)) {
                $cadena_detrac = '01/01/0001|0';
            } else {
                $cadena_detrac = $fechadocdet . $csv_sep . $nrodocdet;
            }

            if ($res->retencion == '') {
                $retencion = 0;
            } else {
                $retencion = $res->retencion;
            }

            if ($retencion == 0) {
                $retencion = '';
            } else {
                $retencion = 1;
            }

            //Inicio valida error 1
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
            //Inicio valida error 2
            $error2 = $res->error2;
            $nohab = Nohabido::buscar_codigo($res->nrodocide);
            if (!empty($nohab)) {
                $nohabido = Nohabido::findOrFail($nohab->id);
                $fechadoc = date('Y-m-d', strtotime($res->fechadoc));
                $desde = date('Y-m-d', strtotime($nohabido->desde));
                $hasta = date('Y-m-d', strtotime("2999-12-31"));
                if (($fechadoc > $desde) && ($fechadoc < $hasta)) {
                    $error2 = 1;
                }
            }
            //Fin valida error 2
            //Inicio valida error 3
            $error3 = $res->error3;
            $renexo = TaxExcluded::buscar_codigo($res->nrodocide);
            if (!empty($renexo)) {
                $RenunciaExo = TaxExcluded::findOrFail($renexo->id);
                $fechadoc = date('Y-m-d', strtotime($res->fechadoc));
                $desde = date('Y-m-d', strtotime($RenunciaExo->desde));
                if ($fechadoc > $desde) {
                    $error3 = 1;
                }
            }
            //Fin valida error 3

            $array5 = array('02', '03', '10', '15', '16', '21', '22', '44', '45', '46', '49');
            if (in_array(trim($res->tipodoc), $array5)) {
                $final = 0;
            } else {
                if (intval(substr(fecha_sin_guion(spanishdate($fechadoc)), 0, 6)) < intval($res->periodo)) {
                    $final = 6;
                } elseif (fecha_sin_guion(spanishdate($fechadoc)) - fecha_sin_guion(spanishdate($fecha_inicio)) > 365) {
                    $final = 7;
                } else {
                    $final = 1;
                }
            }

            if ($res->nd_flag == 0) {

                if (left(fecha_sin_guion(spanishdate($vencimiento)), 6) > left(fecha_hoy_sin_guion(), 6)) {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . spanishdate($fechadoc) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . left(trim($res->nombre), 100) . $csv_sep . $baseexp . $csv_sep . $igvexp . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena_referencia . $csv_sep . $cadena_detrac . $csv_sep . $retencion . $csv_sep . $res->tipobien . $csv_sep . $error1 . $csv_sep . $error2 . $csv_sep . $error3 . $csv_sep . $res->mpago . $csv_sep . $final . $csv_sep . $csv_end;
                } else {
                    $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . spanishdate($vencimiento) . $csv_sep . $res->tipodoc . $csv_sep . $seriedoc . $csv_sep . $numerodoc . $csv_sep . trim($res->tipodocide) . $csv_sep . trim($res->nrodocide) . $csv_sep . left(trim($res->nombre), 100) . $csv_sep . $baseexp . $csv_sep . $igvexp . $csv_sep . $otros . $csv_sep . $total . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . $cadena_referencia . $csv_sep . $cadena_detrac . $csv_sep . $retencion . $csv_sep . $res->tipobien . $csv_sep . $error1 . $csv_sep . $error2 . $csv_sep . $error3 . $csv_sep . $res->mpago . $csv_sep . $final . $csv_sep . $csv_end;
                }
                $i++;
            }
        }

        if ($i > 0) {
            $lcCont = 1;
        } else {
            $lcCont = 0;
        }

        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00080300001" . $lcCont . $lcMon . "1.txt";
        $csv_file = public_path() . '/ples/' . $nombre;

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

    public function reporte_compras_plenodomiciliado(Request $request)
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

        $moneda = $request->moneda;
        if ($moneda == '') {
            $moneda = 'N';
        } else {
            $moneda = $request->moneda;
        }

        $chk_documentos_registro_compras = validaCheck($request->checkdocuments);

        $resultado = $this->procedurereports->rptComprasRegistro($fecha_inicio, $fecha_fin);
        $resul = array();
        foreach ($resultado as $res) {
            if ($chk_documentos_registro_compras == 1) {
                if ($res->flag != 0) {
                    array_push($resul, $res);
                }
            } else {
                array_push($resul, $res);
            }
        }

        $resultados = collect($resul);

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

            if ($res->nd_art76 == 1) {
                $aux = 1;
            } else {
                $aux = '';
            }

            if ($res->nd_flag == 1) {
                $csv .= $res->periodo . '00' . $csv_sep . $res->voucher_id . $csv_sep . $res->voucher_item . $csv_sep . spanishdate($fechadoc) . $csv_sep . trim($res->tipodoc) . $csv_sep . trim($res->seriedoc) . $csv_sep . trim($res->numerodoc) . $csv_sep . $res->baseexpmn . $csv_sep . $res->basenogmn . $csv_sep . $res->totalmn . $csv_sep . trim($res->nd_tipodoc) . $csv_sep . trim($res->nd_serie) . $csv_sep . trim($res->nd_anio) . $csv_sep . trim($res->nd_numero) . $csv_sep . trim($res->nd_retigv) . $csv_sep . trim($res->cmoneda) . $csv_sep . number_format(round($res->tcambio, 3), 3, '.', '') . $csv_sep . trim($res->nd_paisnd) . $csv_sep . left($res->nombre, 100) . $csv_sep . trim($res->nd_domicilio) . $csv_sep . trim($res->nrodocide) . $csv_sep . trim($res->nd_documentoben) . $csv_sep . trim(left($res->nd_nombreben, 100)) . $csv_sep . trim($res->nd_paisben) . $csv_sep . trim($res->nd_vinculo) . $csv_sep . $res->nd_rentab . $csv_sep . $res->nd_costo . $csv_sep . $res->nd_rentan . $csv_sep . $res->nd_tasaret . $csv_sep . $res->nd_impuesto . $csv_sep . trim($res->nd_convenio) . $csv_sep . trim($res->nd_exoneracion) . $csv_sep . trim($res->nd_tiporenta) . $csv_sep . trim($res->nd_tiposervicio) . $csv_sep . $aux . $csv_sep . '0' . $csv_sep . $csv_end;
                $i++;
            }
        }

        if ($i > 0) {
            $lcCont = 1;
        } else {
            $lcCont = 0;
        }

        $nombre = "LE" . Session::get('company_ruc') . $codigo_periodo . "00080200001" . $lcCont . $lcMon . "1.txt";
        $csv_file = public_path() . '/ples/' . $nombre;

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
