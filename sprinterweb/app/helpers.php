<?php

use App\Companies\Entities\Empresa;
use App\Panel\Companies\Entities\Pempresa;
use App\Message\Entities\Message;
use App\Version\Entities\Version;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

function obtener_cliente()
{
    $ruc = session('company_ruc');
    DB::setDefaultConnection('DB_CONNECTION_' . $ruc);
    DB::purge('DB_CONNECTION_' . $ruc);
}

function sesion_empresa_administrador()
{
    session(['permission' => 1]);
}

function sesion_empresa_cliente($empresa)  //se usaba en el login pero parece duplicado
{
    session(['companyruc' => $empresa->ruc]);
    session(['companydesc' => $empresa->name]);
    session(['companyvia' => $empresa->direction]);
    session(['companyid' => $empresa->id]);
    session(['ver_id' => $empresa->user_id]);
}


function daysdiff($fecha_fin)
{
    $fecha_hoy = date("Y-m-d");
    $hoy = new DateTime($fecha_hoy);
    $fechaf = new DateTime($fecha_fin);
    $dif = $fechaf->diff($hoy)->format("%r%a");

    return $dif;
}

function restar_1_dia($fecha, $dia)
{
    $fecha = date('Y-m-d', strtotime('-' . $dia . ' day', strtotime($fecha)));
    return $fecha;
}

function sumar_dias($fecha, $dia)
{
    $date = date('Y-m-d', strtotime($fecha . ' + ' . $dia . ' days'));
    return $date;
}

function todaydate()
{
    return date("Y-m-d H:i:s");
}

function valida_version($nombre_menu)
{
    $version = Version::findOrFail(Session::get('ver_id'));

    if ($version) {
        $codigo = $version->codigo;

        switch ($nombre_menu) {
            case 'almacen':
                if ($codigo == '01') {
                    $retorno = 0;
                } elseif ($codigo == '02') {
                    $retorno = 0;
                } elseif ($codigo == '03') {
                    $retorno = 1;
                } elseif ($codigo == '04') {
                    $retorno = 1;
                }
                break;
            case 'activos':
                if ($codigo == '01') {
                    $retorno = 0;
                } elseif ($codigo == '02') {
                    $retorno = 1;
                } elseif ($codigo == '03') {
                    $retorno = 1;
                } elseif ($codigo == '04') {
                    $retorno = 1;
                }
                break;
            case 'planillas':
                if ($codigo == '01') {
                    $retorno = 0;
                } elseif ($codigo == '02') {
                    $retorno = 0;
                } elseif ($codigo == '03') {
                    $retorno = 0;
                } elseif ($codigo == '04') {
                    $retorno = 1;
                }
                break;
        }
    } else {
        $retorno = 0;
    }

    return $retorno;
}

function validaCheck($campo)
{
    if ($campo == true) {
        return 1;
    } else {
        return 0;
    }
}

function validapempresa($nombreParametro, $tipo, $descripcion, $valor)
{
    $pempresa = Pempresa::where('parametro', '=', $nombreParametro)->first();

    if (!$pempresa) {
        $pempresa = new Pempresa();
        $pempresa->parametro = $nombreParametro;
        $pempresa->valor = $valor;
        $pempresa->descripcion = $descripcion;
        $pempresa->tipo = $tipo;
        $pempresa->usuario = '';
        $pempresa->save();
    }

    return $pempresa;
}

function link_view_inicio()
{
    $data['view'] = "Inicio";
    $data['viewlink'] = '';
    $data['link'] = '';
    return $data;
}

function link_view($nivel_one, $nivel_two, $nivel_three, $video)
{

    $data['view'] = "$nivel_one | $nivel_two | ";
    $data['viewlink'] = $nivel_three;
    //$data['link']= $video;
    $data['link'] = 'https://www.youtube.com/watch?v=7X9IVKiNwow';
    return $data;
}

function right($str, $length)
{
    return substr($str, -$length);
}

function left($str, $length)
{
    return substr($str, 0, $length);
}

function spanishdate($date)
{
    $datec = date_create($date);
    $newdate = date_format($datec, "d/m/Y");
    return $newdate;
}

function ceros_izquierda($texto, $max)
{
    return str_pad($texto, $max, "0", STR_PAD_LEFT);
}

function formatear_numero($numero, $decimales)
{
    return number_format($numero, $decimales, '.', '');  //2 decimales obligatoriamente
}

function fecha_sin_guion($fecha)
{
    $cadena = trim($fecha);
    $tamano = strlen($cadena);
    $anno = substr($cadena, 6, $tamano);
    $dia = substr($cadena, 0, 2);
    $mes = substr($cadena, 3, 2);
    $valor = $anno . $mes . $dia;
    return $valor;
}

function fecha_hoy_sin_guion()
{
    $cadena = trim(fecha_hoy());
    $tamano = strlen($cadena);
    $anno = substr($cadena, 6, $tamano);
    $dia = substr($cadena, 0, 2);
    $mes = substr($cadena, 3, 2);
    $valor = $anno . $mes . $dia;
    return $valor;
}

function fecha_hoy()
{
    date_default_timezone_set('America/Lima');
    $fecha = date('d/m/Y');
    return $fecha;
}

function getMesaage($id)
{
    $mensaje = Message::findOrFail($id)->mensaje_es;
    return $mensaje;
}

function headeroptions($variable, $proceso, $array, $estado, $privilegios, $admin)
{
    $data = null;

    $routeCreate = Route::getRoutes()->hasNamedRoute('create.' . $variable);
    
    $routeEdit = Route::getRoutes()->hasNamedRoute('edit.' . $variable);

    $routeList = Route::getRoutes()->hasNamedRoute('list.' . $variable);

    $routeShow = Route::getRoutes()->hasNamedRoute('show.' . $variable);

    //dd($routeCreate, $routeEdit, $routeList);

    $disable_consulta = 'btn-menu';
    $href_aprueba = 'aprueba';
    $disable_crea = 'btn-menu nuevo';

    //validamos si existe la ruta create. en caso no exista, no cargara el icono del nuevo en la vista del list :v 
    if ($routeCreate) {
        $href_crea = 'href="' . route('create.' . $variable, $array) . '"';
    } else {
        $href_crea = '';
    }
    $disable_edita = 'btn-menu habilitar';
    $href_edita = 'id="btn_habilitar"';
    $disable_anula = 'btn-menu';
    $href_anula = 'id="btn_anular"';
    $disable_borra = 'btn-menu';
    $href_borra = 'id="btn_eliminar"';
    $disable_imprime = 'btn-menu';
    $href_imprime = '';
    $disable_aprueba = 'btn-menu';
    $href_aprueba = 'id="btn_aprobar"';
    $disable_precio = 'btn-menu';
    $href_precio = '';
    if ($privilegios === "") {

    } else {
        if ($admin == 'ADMINISTRADOR' || $variable == 'sprinter') {

        } else {
            if ($privilegios->consulta == 0) {
                $disable_consulta = 'btn-menu2';
                $href_consulta = '';
            }
            if ($privilegios->crea == 0) {
                $disable_crea = 'btn-menu2';
                $href_crea = '';
            }
            if ($privilegios->edita == 0) {
                $disable_edita = 'btn-menu2';
                $href_edita = '';
            }
            if ($privilegios->anula == 0) {
                $disable_anula = 'btn-menu2';
                $href_anula = 'id="btn_anular"';
            }
            if ($privilegios->borra == 0) {
                $disable_borra = 'btn-menu2';
                $href_borra = 'id="btn_eliminar"';
            }
            if ($privilegios->imprime == 0) {
                $disable_imprime = 'btn-menu2';
                $href_imprime = '';
            }
            if ($privilegios->aprueba == 0) {
                $disable_aprueba = 'btn-menu2';
                $href_aprueba = '';
            }
            if ($privilegios->precio == 0) {
                $disable_precio = 'btn-menu2';
                $href_precio = '';
            }
        }
    }


    if ($routeShow === true) {
        if ($proceso == 'show') {
            $data .= '<div class="menu-item" style="width: 35px;margin-top: -48px;">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Cancelar Cambios" id="btn_cancelar" href="' . route($variable) . '">
                            <span class="flaticon-menu-cancel"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Asiento" id="btn_asientito">
                            <span class="flaticon-menu-star"></span>
                        </a>
                    </div>';
        }
    }

    if ($routeCreate === true) {
        if ($proceso == 'crea') {

            $data = '<div class="menu-item" style="width: 35px;margin-top: -50px;">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Guardar" id="btn_grabar">
                            <span class="flaticon-submenu-upload"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <a class="btnwithoutborder btn-menu btn-sm" title="Nueva Linea" id="btn_nueva_linea">
                            <span class="flaticon-menu-add"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                      <a href="#" id="titlePeriod" data-toggle="modal" data-target="#modalPeriod"
                       class="col-md-1 col-xs-1">
                        <label class="label label-primary" id="SelectedPeriod" style="cursor: pointer!important;">' . Session::get("period") . '</label>
                      </a>
                    </div>';

            if($variable == 'saleorder' ){
                if (Session::get('point') == '') {
                    $puntoventa = 'NINGUNO';
                } else {
                    $puntoventa = Session::get("point");
                }
                $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px; margin-left: 100px;">
                      <a href="#" id="titlePoint" data-toggle="modal" data-target="#modalPoint"
                       class="col-md-1 col-xs-1">
                        <label class="label label-primary" id="SelectedPoint" style="cursor: pointer!important;">' . $puntoventa . '</label>
                      </a>
                    </div>';
            }
        }
    }

    if ($routeEdit == true) {
        if ($proceso == 'edita') {
            if ($variable == 'accounts' || $variable == 'exchangerate' || $variable == 'accountingctnwarehouse' || $variable == 'accountsTypesSales' || $variable == 'accountsDocumentsSales') {
                $data = '<div class="menu-item nuevo" style="width: 35px; margin-top: -50px;">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Nuevo Registro" id="btn_nuevo">
                            <span class="flaticon-menu-file"></span>
                        </a>
                    </div>';
                $data .= '<div class="menu-item nuevo2" style="width: 35px; margin-top: -50px; margin-left: -35px; display: none">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Nuevo Registro" >
                            <span class="flaticon-menu-file"></span>
                        </a>
                    </div>';
            } else {
                if ($routeCreate == true) {
                    $data = '<div class="menu-item nuevo" style="width: 35px; margin-top: -50px;">
                        <a type="button" class="btnwithoutborder ' . $disable_crea . ' btn-sm" title="Nuevo Registro" ' . $href_crea . ' id="btn_nuevo">
                            <span class="flaticon-menu-file"></span>
                        </a>
                    </div>';
                    $data .= '<div class="menu-item nuevo2" style="width: 35px; margin-top: -50px; margin-left: -35px; display: none">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Nuevo Registro" >
                            <span class="flaticon-menu-file"></span>
                        </a>
                    </div>';
                }
            }

            $data .= '<div class="menu-item habilitar" style="width: 35px;margin-top: -35px; margin-left: -35px;">
                        <a type="button" class="btnwithoutborder ' . $disable_edita . ' btn-sm" title="Editar" ' . $href_edita . '>
                            <span class="flaticon-menu-edit"></span>
                        </a>
                    </div>';
            $data .= '<div class="menu-item habilitar2" style="width: 35px;margin-top: -35px; margin-left: -75px; display: none">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Editar">
                            <span class="flaticon-menu-edit"></span>
                        </a>
                    </div>';
            if ($variable == 'accounts' || $variable == 'exchangerate' || $variable == 'accountsDocumentsSales' || $variable == 'accountsTypesSales') {
                //anular
                $data .= '<div class="menu-item anular" style="width: 35px;margin-top: -35px; margin-left: -75px;">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Anular">
                                <span class="flaticon-menu-delete-file"></span>
                            </a>
                        </div>';
                $data .= '<div class="menu-item anular2" style="width: 35px;margin-top: -35px; margin-left: -115px; display: none">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Anular">
                                <span class="flaticon-menu-delete-file"></span>
                            </a>
                        </div>';

                //eliminar
                $data .= '<div class="menu-item eliminar" style="width: 35px;margin-top: -35px; margin-left: -115px;">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Eliminar">
                                <span class="flaticon-menu-garbage-1"></span>
                            </a>
                        </div>';
                $data .= '<div class="menu-item eliminar2" style="width: 35px;margin-top: -35px; margin-left: -155px; display: none">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Eliminar">
                                <span class="flaticon-menu-garbage-1"></span>
                            </a>
                        </div>';
            } else {
                //anular
                $data .= '<div class="menu-item anular" style="width: 35px;margin-top: -35px; margin-left: -75px;" ' . $href_anula . '>
                            <a type="button" class="btnwithoutborder ' . $disable_anula . ' btn-sm" title="Anular">
                                <span class="flaticon-menu-delete-file"></span>
                            </a>
                        </div>';
                $data .= '<div class="menu-item anular2" style="width: 35px;margin-top: -35px; margin-left: -115px; display: none">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Anular">
                                <span class="flaticon-menu-delete-file"></span>
                            </a>
                        </div>';

                //eliminar
                $data .= '<div class="menu-item eliminar" style="width: 35px;margin-top: -35px; margin-left: -115px;" ' . $href_borra . '>
                            <a type="button" class="btnwithoutborder ' . $disable_borra . ' btn-sm" title="Eliminar">
                                <span class="flaticon-menu-garbage-1"></span>
                            </a>
                        </div>';
                $data .= '<div class="menu-item eliminar2" style="width: 35px;margin-top: -35px; margin-left: -155px; display: none">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Eliminar">
                                <span class="flaticon-menu-garbage-1"></span>
                            </a>
                        </div>';
            }

            //

            $data .= '<div class="menu-item editar" style="width: 35px;margin-top: -35px; margin-left: -155px;">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Guardar" >
                            <span class="flaticon-submenu-upload"></span>
                        </a>
                    </div>';
            $data .= '<div class="menu-item editar2" style="width: 35px;margin-top: -35px;margin-left: -195px; display: none">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Guardar" id="btn_editar">
                            <span class="flaticon-submenu-upload"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item cancelar" style="width: 35px;margin-top: -35px;margin-left: -195px;">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Cancelar" >
                            <span class="flaticon-submenu-ban"></span>
                        </a>
                    </div>';
            $data .= '<div class="menu-item cancelar2" style="width: 35px;margin-top: -35px;margin-left: -235px; display: none">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Cancelar" id="btn_cancelar">
                            <span class="flaticon-submenu-ban"></span>
                        </a>
                    </div>';

            /*if ($estado == 1 || $estado == 'Activo') {
                $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <a class="btnwithoutborder btn-menu btn-sm" title="Anular" id="btn_actualizar">
                            <span class="flaticon-menu-cancel-form"></span>
                        </a><input type="hidden" value="0" id="estado" name="estado">
                    </div>';
            } elseif ($estado == 0 || $estado == 'Anulado') {
                $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <a class="btnwithoutborder btn-menu btn-sm" title="Activar" id="btn_actualizar">
                            <span class="flaticon-menu-check-form"></span>
                        </a><input type="hidden" value="1" id="estado" name="estado">
                    </div>';
            }*/

            $data .= '<div class="menu-item nueva_linea" style="width: 35px;margin-top: -35px;margin-left: -240px">
                        <a class="btnwithoutborder btn-menu2 btn-sm" title="Nueva Linea">
                            <span class="flaticon-menu-add"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item nueva_linea2" style="width: 35px;margin-top: -35px;margin-left: -280px; display: none">
                        <a class="btnwithoutborder btn-menu btn-sm" title="Nueva Linea" id="btn_nueva_linea">
                            <span class="flaticon-menu-add"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px; margin-left: -280px">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Asiento" id="btn_asiento">
                            <span class="flaticon-menu-star"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item aprobar" style="width: 35px; margin-top: -35px; margin-left: 360px;">
                        <a type="button" class="btnwithoutborder ' . $disable_aprueba . ' btn-sm aprobar" title="Aprobar" ' . $href_aprueba . '>
                            <span class="flaticon-menu-like"></span>
                        </a>
                    </div>';
            $data .= '<div class="menu-item aprobar2" style="width: 35px; margin-top: -35px; margin-left: 360px; display: none">
                        <a type="button" class="btnwithoutborder btn-menu2 btn-sm">
                            <span class="flaticon-menu-like"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item" style="width: 100px; margin-top: -35px;margin-left: 380px;">
                       <label type="text" id="estadito">' . $estado . '</label>
                    </div>';

            $data .= '<div class="menu-item" style="margin-left: 80px;margin-top: -35px;margin-left: 500px;">
                        <label class="label label-primary" id="SelectedPeriod" style="cursor: pointer!important;">' . Session::get("period") . '</label>
                    </div>';
        }

        if ($proceso == 'no-edit') {
            if ($routeCreate == true) {
                $data = '<div class="menu-item" style="width: 35px; margin-top: -50px;">
                        <a type="button" class="btnwithoutborder btn-menu btn-sm" title="Nuevo Registro" href="' . route('create.' . $variable, $array) . '" id="btn_nuevo">
                            <span class="flaticon-menu-file"></span>
                        </a>
                    </div>';
            }

            $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <a class="btnwithoutborder btn-menu btn-sm" title="Nueva Linea" id="btn_nueva_linea">
                            <span class="flaticon-menu-add"></span>
                        </a>
                    </div>';

            $data .= '<div class="menu-item" style="width: 100px; margin-top: -35px;">
                       <label type="text" id="estadito">' . $estado . '</label>
                    </div>';

            $data .= '<div class="menu-item" style="margin-left: 80px;margin-top: -35px;">
                        <label class="label label-primary" id="SelectedPeriod" style="cursor: pointer!important;">' . Session::get("period") . '</label>
                    </div>';
        }
    }

    if ($routeList == true) {
        if ($proceso == 'list') {
            if ($variable == 'pointofsale') {
                $data = '<div class="menu-item nuevo_venta" style="width: 35px; margin-top: -50px;">
                        <a type="button" class="btnwithoutborder ' . $disable_crea . ' btn-sm" title="Nuevo Registro"  id="btn_nuevo_venta">
                            <span class="flaticon-menu-file"></span>
                        </a>
                        </div>';
                $data .= '<div class="menu-item nuevo_venta2" style="width: 35px; margin-top: -50px; margin-left: -35px; display: none">
                            <a type="button" class="btnwithoutborder btn-menu2 btn-sm" title="Nuevo Registro" >
                                <span class="flaticon-menu-file"></span>
                            </a>
                        </div>';

                $data .= '<div class="menu-item nueva_linea" style="width: 35px;margin-top: -35px;margin-left: -30px">
                        <a class="btnwithoutborder btn-menu2 btn-sm" title="Nueva Linea">
                            <span class="flaticon-menu-add"></span>
                        </a>
                    </div>';

                $data .= '<div class="menu-item nueva_linea2" style="width: 35px;margin-top: -35px;margin-left: -70px; display: none">
                        <a class="btnwithoutborder btn-menu btn-sm" title="Nueva Linea" id="btn_nueva_linea">
                            <span class="flaticon-menu-add"></span>
                        </a>
                    </div>';

                $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;">
                      <a href="#" id="titlePeriod" data-toggle="modal" data-target="#modalPeriod"
                       class="col-md-1 col-xs-1">
                        <label class="label label-primary" id="SelectedPeriod" style="cursor: pointer!important;">' . Session::get("period") . '</label>
                      </a>
                    </div>';


                if (Session::get('point') == '') {
                    $puntoventa = 'NINGUNO';
                } else {
                    $puntoventa = Session::get("point");
                }
                $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px; margin-left: 100px;">
                      <a href="#" id="titlePoint" data-toggle="modal" data-target="#modalPoint"
                       class="col-md-1 col-xs-1">
                        <label class="label label-primary" id="SelectedPoint" style="cursor: pointer!important;">' . $puntoventa . '</label>
                      </a>
                    </div>';
            } else {
                if ($routeCreate == true) {
                    $data = '<div class="menu-item" style="width: 35px; margin-top: -50px;">
                        <a type="button" class="btnwithoutborder ' . $disable_crea . ' btn-sm" title="Nuevo Registro" ' . $href_crea . ' id="btn_nuevo">
                            <span class="flaticon-menu-file"></span>
                        </a>
                    </div>';

                    $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;" >
                        <a class="btnwithoutborder btn-menu btn-sm" title="Configurar Ventana" id="btn_configurar">
                            <span class="flaticon-menu-wrench-1"></span>
                        </a>
                    </div>';
                    /*
                    $data .= '<div class="menu-item" style="width: 35px;margin-top: -35px;" >
                            <a class="btnwithoutborder btn-menu btn-sm" title="Eliminas registro(s)" id="btn_eliminar">
                                <span class="flaticon-menu-garbage-1"></span>
                            </a>
                        </div>';*/
                }
            }
        }
    }

    return $data;
}

function eliminar_acentos($cadena)
{
    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
    );
    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena);

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena);

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena);

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena);

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $cadena
    );

    return $cadena;
}

function navbar_menu($datas, $admin)
{
    $data = null;
    $variable2 = 'href=""';
    $variable1 = 'href="' . route("generales.configuration") . '"';
    $variable3 = 'href="' . route("user_management") . '"';
    $variable4 = 'href=""';
    $variable5 = 'href=""';
    $variable6 = '';
    //if (count($datas) > 0) {
        if ($admin == 'ADMINISTRADOR') {
        } else {
            if ($datas[0]['consulta'] == 0) {
                $variable1 = 'style="color: #5a6268"';
            }
            if ($datas[1]['consulta'] == 0) {
                $variable2 = 'style="color: #5a6268"';
            }
            if ($datas[2]['consulta'] == 0) {
                $variable3 = 'style="color: #5a6268"';
            }
            if ($datas[3]['consulta'] == 0) {
                $variable4 = 'style="color: #5a6268"';
            }
            if ($datas[4]['consulta'] == 0) {
                $variable5 = 'style="color: #5a6268"';
            }
            if ($datas[5]['consulta'] == 0) {
                $variable6 = 'style="color: #5a6268"';
            }
        }

    /*} else {

        $variable1 = 'style="color: #5a6268"';
        $variable2 = 'style="color: #5a6268"';
        $variable3 = 'style="color: #5a6268"';
        $variable4 = 'style="color: #5a6268"';
        $variable5 = 'style="color: #5a6268"';
    }*/

    $data .= '<li>
                <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Configuración">
                    <i class="flaticon-menu-settings"></i>
                </a>
                <ul id="uno">
                    <li>
                        <a ' . $variable1 . '><span class="fa fa-cogs"></span><br>Configurar Sistema</a>
                    </li>
                    <li>
                        <a ' . $variable2 . '><span class="fa fa-list-alt"></span><br>Editor de Menús</a>
                    </li>
                    <li>
                        <a ' . $variable4 . '><span class="fa fa-thumbs-o-up"></span><br>Aporbar Documentos</a>
                    </li>
                    <li>
                        <a href=""><span class="fa fa-print"></span><br>Config. Impresora</a>
                    </li>
                    <li>
                        <a href=""><span class="fa fa-file-pdf-o"></span><br>Firmar PDF</a>
                    </li>
                </ul>
            </li>';

    $data .= '<li>
                <a ' . $variable3 . ' data-toggle="tooltip" data-placement="top" title="Gestión de Usuario"><i class="flaticon-menu-user-1"></i></a>
            </li>';
    $data .= '<li>
                <a ' . $variable5 . ' data-toggle="tooltip" data-placement="top"title="Buscar">
                    <i class="flaticon-menu-magnifier"></i>
                </a>
            </li>';
    $data .= '<li>
                <a data-toggle="tooltip" data-placement="top" href="' . route("logout") . '" title="Salir del Sistema">
                    <i class="flaticon-menu-power-button"></i>
                </a>
            </li>';

    return $data;

}

function botones($proceso, $datas)
{
    $data = null;
    $variable = 'id="configuracion"';
    if ($data == '') {
        if ($proceso == 'crea') {
            $data .= '<div class="col-md-12">
                    <a class="form-control btn-dark text-center" id="configuracion"><i class="fa fa-user-secret" aria-hidden="true"></i> Configurar Privilegios</a>
                </div>
                <div class="col-md-12">
                    <br>
                </div>
                <div class="col-md-12">
                    <a class="form-control btn-dark text-center"><i class="fa fa-key"aria-hidden="true"></i> ResetearContraseña</a>
                </div>';
        }
        if ($proceso == 'edita') {
            $data .= '<div class="col-md-12">
                    <a class="form-control btn-primary text-center confi" ' . $variable . '><i class="fa fa-user-secret" aria-hidden="true"></i> Configurar Privilegios</a>
                    <a class="form-control btn-dark text-center confi2" style="display: none"><i class="fa fa-user-secret" aria-hidden="true"></i> Configurar Privilegios</a>
                 </div>
                 <div class="col-md-12">
                    <br>
                 </div>
                 <div class="col-md-12">
                    <a class="form-control btn-primary text-center reset"><i class="fa fa-key" aria-hidden="true"></i> Resetear Contraseña</a>
                    <a class="form-control btn-dark text-center reset2" style="display: none"><i class="fa fa-key" aria-hidden="true"></i> Resetear Contraseña</a>
                 </div>';
        }
    } else {
        if ($proceso == 'crea') {
            $data .= '<div class="col-md-12">
                    <a class="form-control btn-dark text-center" id="configuracion"><i class="fa fa-user-secret" aria-hidden="true"></i> Configurar Privilegios</a>
                </div>
                <div class="col-md-12">
                    <br>
                </div>
                <div class="col-md-12">
                    <a class="form-control btn-dark text-center"><i class="fa fa-key"aria-hidden="true"></i> ResetearContraseña</a>
                </div>';
        }
        if ($datas[2]['crea'] == 0) {
            $variable = '';
        }
        if ($proceso == 'edita') {
            $data .= '<div class="col-md-12">
                    <a class="form-control btn-primary text-center confi" ' . $variable . '><i class="fa fa-user-secret" aria-hidden="true"></i> Configurar Privilegios</a>
                    <a class="form-control btn-dark text-center confi2" style="display: none"><i class="fa fa-user-secret" aria-hidden="true"></i> Configurar Privilegios</a>
                 </div>
                 <div class="col-md-12">
                    <br>
                 </div>
                 <div class="col-md-12">
                    <a class="form-control btn-primary text-center reset"><i class="fa fa-key" aria-hidden="true"></i> Resetear Contraseña</a>
                    <a class="form-control btn-dark text-center reset2" style="display: none"><i class="fa fa-key" aria-hidden="true"></i> Resetear Contraseña</a>
                 </div>';
        }
    }

    return $data;
}
function entre_intervalo($valor, $minimo, $maximo){
    if( $valor < $minimo ) return false;
    if( $valor > $maximo ) return false;
    return true;
}

function string_between_two_string($str, $starting_word, $ending_word){ // OBTENER UN SUBSTRING DE UN STRING ENTRE UN INICIO Y FINAL
    
    $arr = explode($starting_word, $str); 
    
    if (isset($arr[1])){ 

        $arr = explode($ending_word, $arr[1]); 
        return $arr[0]; 

    }

    return ''; 
}


function guardar_xml_firma2($ruc, $xml_nombre, $xml){

    Storage::disk('FIRMAXML')->put('FIRMAXML/' . $ruc . '/' . $xml_nombre, $xml);

}


function obtener_xml_firma2($ruc, $xml_nombre){

    return Storage::disk('FIRMAXML')->get('FIRMAXML/' . $ruc . '/' . $xml_nombre);
    
}

function existe_xml_firma($ruc, $nombre_xml){

    return Storage::disk('FIRMAXML')->exists('FIRMAXML/' . $ruc . '/' . $nombre_xml);
}

function guardar_xml_sunat($ruc, $xml_nombre, $xml){

    Storage::disk('SUNATXML')->put('SUNATXML/' . $ruc . '/R-' . $xml_nombre, $xml);

}

function obtener_xml_sunat($ruc, $xml_nombre){

    return Storage::disk('SUNATXML')->get('SUNATXML/' . $ruc . '/R-' . $xml_nombre);

}


function obtener_contenido($content){

    // EL XML EN ALGUNOS CASOS ESTA
    // EN UN INDEX MAYOR A CERO DEL ARRAY DEL ZIP DE RESPUESTA

    $cont = count($content);

    $xml = "";

    for($i = 0; $i < $cont ; ++$i){

        if(empty($content[$i]['content']) == false){

            $xml = $content[$i]['content'];

        }
       
    
    }

    return $xml;

}
