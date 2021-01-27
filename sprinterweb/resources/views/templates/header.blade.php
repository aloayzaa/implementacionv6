<header class="header-container" id="header-container">
    <div class="nav_menu" style="border-bottom: 2px solid #0095CC">
        <div class="col-sm-1">
            <div class="visible-xs visible-sm">
                <a href="#" style="color: blue" id="sidebar-toggler" class="menu-link menu-link-slide"><img
                        src="{{ asset('img/sprinter3.png') }}" style="padding-top: 7px;"></a>
            </div>
            <div class="hidden-xs">
                <a href="#" id="offcanvas-toggler" class="menu-link menu-link-slide"><img
                        src="{{ asset('img/sprinter3.png') }}" style="padding-top: 7px;"></a>
            </div>
        </div>

        <div class="col-md-12 col-sm-8 tablet-top">
            <div class="col-md-7 col-sm-8" style="margin-top: 10px;">
                <div class="hidden-xs">
                    <input type="checkbox" href="#" class="menu-open" id="menu-open" checked
                           onclick="data_menu({{ "'". ucwords(strtolower(Session::get('company_desc')))  ."'" }}, {{ "'". Session::get('company_ruc') ."'" }}, {{ "'". Session::get('usuario') ."'" }})"/>

                    <label class="menu-open-button hamburgerr hamburger--arrow" for="menu-open">
                        <span class="hamburger-inner"></span>
                    </label>

                    {!!$header!!}
                    {{--{!! headeroptions($var, $proceso) !!}--}}

                    {{--<div class="menu-item" style="width: 35px; margin-top: -50px;">
                        <button type="button" class="btnwithoutborder btn-menu btn-sm" title="Nuevo Registro"
                                id="btn_nuevo">
                            <span class="flaticon-menu-file"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button type="button" class="btnwithoutborder btn-menu btn-sm" title="Habilitar"
                                id="btn_habilitar">
                            <span class="flaticon-menu-check-form"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button type="button" class="btnwithoutborder btn-menu btn-sm" title="Guardar" id="btn_grabar">
                            <span class="flaticon-submenu-upload"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button type="button" class="btnwithoutborder btn-menu btn-sm" title="Editar"
                                id="btn_editar">
                            <span class="flaticon-menu-new-file"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button type="button" class="btnwithoutborder btn-menu btn-sm" title="Cancelar Cambios"
                                id="btn_cancelar">
                            <span class="flaticon-menu-cancel"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Anular" id="btn_anular"
                                name="btn_anular">
                            <span class="flaticon-menu-cancel-form"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="ELiminar" id="btn_eliminar"
                                name="btn_eliminar">
                            <span class="flaticon-menu-garbage-1"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Duplicar Registro" id="btn_duplicar"
                                name="btn_duplicar">
                            <span class="flaticon-menu-copy"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Nueva Linea" id="btn_nueva_linea"
                                name="btn_nueva_linea">
                            <span class="flaticon-menu-add"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Borrar Linea" id="btn_borrar_linea"
                                name="btn_borrar_linea">
                            <span class="flaticon-menu-minus-symbol"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Confifurar Ventana" id="btn_configurar"
                                name="btn_configurar">
                            <span class="flaticon-menu-wrench-1"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Imprimir" id="btn_imprimir"
                                name="btn_imprimir">
                            <span class="flaticon-menu-printer"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Vincular" id="btn_vincular"
                                name="btn_vincular">
                            <span class="flaticon-menu-link"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Aprobar" id="btn_aprobar"
                                name="btn_aprobar">
                            <span class="flaticon-menu-like"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Ver Preferencia" id="btn_preferencia"
                                name="btn_preferencia">
                            <span class="flaticon-menu-star-1"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Historial" id="btn_historial"
                                name="btn_historial">
                            <span class="flaticon-menu-magnifying-glass"></span>
                        </button>
                    </div>
                    <div class="menu-item" style="width: 35px;margin-top: -35px;">
                        <button class="btnwithoutborder btn-menu btn-sm" title="Salir" id="btn_salir" name="btn_salir">
                            <span class="flaticon-menu-exit"></span>
                        </button>
                    </div>--}}
                    <div id="data-empresa"></div>
                </div>
            </div>
            <div class="col-md-5 col-sm-4" style="">
                <div class="top-laptop-hidden col-md-3 col-sm-3" style="">

              {{--      <a href="#" id="titlePeriod" data-toggle="modal" data-target="#modalPeriod"
                       class="col-md-1 col-xs-1">
                        <label class="label label-primary"
                               style="cursor: pointer!important;"> {{ Session::get('period') }} </label>
                    </a>--}}

                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="pagina-actual col-md-10 col-sm-10">
                        <h5>
                            {!! $view['view'] !!}
                            <a href="{!! $view['link'] !!}" target="_blank">{!! $view['viewlink'] !!}</a>
                        </h5>
                    </div>
                </div>
                <div class="top-laptop-visible">
                    <div class="pagina-actual-two col-xs-12">
                        {!! $view['view'] !!}
                        <a href="{!! $view['link'] !!}" target="_blank">{!! $view['viewlink'] !!}</a>
                    </div>
                    <div class="data-corporation col-xs-12">
                        <a href="#" id="titlePeriod" data-toggle="modal" data-target="#modalPeriod">
                            <label class="label label-primary"> {{ Session::get('period') }} </label>
                        </a> &nbsp;&nbsp;
                        <label class="label label-primary">Anikama Group</label> &nbsp;&nbsp;
                        <label class="label label-primary" style="">cllave@.com</label> &nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--PERIODOS-->
<div class="modal fade" id="modalPeriod" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header alert alert-info" role="alert">
                <h5 class="modal-title" id="exampleModalLabel">Periodo contable</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="">
                    <select class="form-control "
                            name="accountingPeriod"
                            id="accountingPeriod">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnUpdate" class="btn btn-primary"
                        data-route="{{ route('accountingPeriodUpdate') }}">
                    Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

@if(isset($fechita))
<!--Puntos de venta-->
<div class="modal fade" id="modalPoint" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header alert alert-info" role="alert">
                <h5 class="modal-title" id="exampleModalLabel">Parámetros de Ventas</h5>
                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Punto de venta</label>
                            <div class="">
                                <select class="form-control" name="salesPoint" id="salesPoint">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="">Fecha</label>
                            <div class="">
                                <input type="date" name="datePoint" id="datePoint" class="form-control" max="{{ $fecha }}" value="{{$fecha}}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="btnUpdatePoint" class="btn btn-primary" data-route="{{ route('salesPointUpdate') }}">
                    Actualizar
                </button>
            </div>
        </div>
    </div>
</div>
@endif
