<?php

namespace App\Http\Controllers;

use App\Period\Entities\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDO;

class AccountingCentralizationController extends Controller
{
    private $var = 'accountingcentralizationwarehouse';

    public function __construct()
    {
        $this->obtener_cliente();
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
        $data['view'] = link_view('Contabilidad', 'Procesos', 'Centralización Contable','');
        $data['header'] = headeroptions($this->var, 'list','','');
        return view('accountingcentralization.list', $data);
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

    public function centraliza_modulo(Request $request)
    {
        $modulo = $request->module;
        $f_inicio = $request->initialdate;
        $f_fin = $request->finaldate;

        $periodo = Period::obtener_periodo($f_inicio, $f_fin);

        if ($modulo == 0) {
            if ($periodo->modalm == 0) {
                $mensaje = 'El Módulo Almacén esta cerrado para este periodo';
                $estado = 'error';

                $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);

            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'ingresoalmacen';
                    $origen = '';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó Módulo Almacén sin problemas';
                    $estado = 'ok';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }

            if ($periodo->modcxc == 0) {
                $mensaje = 'El Módulo Ventas esta cerrado para este periodo';
                $estado = 'error';

                $r[1] = array('mensaje' => $mensaje, 'estado' => $estado);
            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[1] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'docxpagar';
                    $origen = 'C0';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó Módulo Ventas sin problemas';
                    $estado = 'ok';

                    $r[1] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }

            if ($periodo->modcxp == 0) {
                $mensaje = 'El Módulo Compras esta cerrado para este periodo';
                $estado = 'error';

                $r[2] = array('mensaje' => $mensaje, 'estado' => $estado);
            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[2] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'docxpagar';
                    $origen = 'P';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó Módulo Compras sin problemas';
                    $estado = 'ok';

                    $r[2] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }

            if ($periodo->modtes == 0) {
                $mensaje = 'El Módulo Tesoreria esta cerrado para este periodo';
                $estado = 'error';

                $r[3] = array('mensaje' => $mensaje, 'estado' => $estado);
            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[3] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'docbanco';
                    $origen = '';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó Módulo Tesoreria sin problemas';
                    $estado = 'ok';

                    $r[3] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }

        } elseif ($modulo == 1) {
            if ($periodo->modalm == 0) {
                $mensaje = 'El Módulo Almacén esta cerrado para este periodo';
                $estado = 'error';

                $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);

            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'ingresoalmacen';
                    $origen = '';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se proceso sin problemas';
                    $estado = 'ok';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }
        } elseif ($modulo == 2) {
            $checked = validaCheck($request->checkctacte);
            if ($periodo->modcxc == 0) {
                $mensaje = 'El Módulo Ventas esta cerrado para este periodo';
                $estado = 'error';

                $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'docxpagar';
                    $origen = 'C' . $checked;
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó sin problemas';
                    $estado = 'ok';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }

        } elseif ($modulo == 3) {
            if ($periodo->modcxp == 0) {
                $mensaje = 'El Módulo Compras esta cerrado para este periodo';
                $estado = 'error';

                $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'docxpagar';
                    $origen = 'P';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó sin problemas';
                    $estado = 'ok';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }
        } else {
            if ($periodo->modtes == 0) {
                $mensaje = 'El Módulo Tesoreria esta cerrado para este periodo';
                $estado = 'error';

                $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
            } else {
                if ($periodo->modcon == 0) {
                    $mensaje = 'El Módulo Contabilidad esta cerrado para este periodo';
                    $estado = 'error';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                } else {
                    $tabla = 'docbanco';
                    $origen = '';
                    $this->ejecuta_procedure_centraliza($f_inicio, $f_fin, $tabla, $origen);
                    $mensaje = 'Se procesó sin problemas';
                    $estado = 'ok';

                    $r[0] = array('mensaje' => $mensaje, 'estado' => $estado);
                }
            }
        }

        return $r;
    }

    private function ejecuta_procedure_centraliza($inicio, $final, $tabla, $origen)
    {
        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $stmt = $pdo->exec('CALL sprinter.prccentraliza("' . $inicio . '","' . $final . '","' . $tabla . '","' . $origen . '")');
        return $stmt;
    }
}
