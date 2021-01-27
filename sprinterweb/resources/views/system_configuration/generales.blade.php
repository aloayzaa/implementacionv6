@extends('templates.home')
@section('content')
    <div class="row-body">
        @include('system_configuration.menu.menu')
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2> General</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask" id="frm_conf_generales"
                          name="frm_conf_generales" method="post">
                        <input type="hidden" id="var" name="var" value="{{ $var }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="panel panel-info">
                                {{--<div class="panel-heading">Productos</div>--}}
                                <div class="panel-body">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 alinear_izquierda margen_top_label">&nbsp;
                                                Periódo de apertura: </label>
                                            <div class="col-sm-6 col-xs-12" id="scrollable-dropdown-menu">
                                                <select name="txt_periodo" id="txt_periodo"
                                                        class="form-control select2">
                                                    @foreach($periodo as $per)
                                                        <option value="{{ $per->id  }}"
                                                                @if($per->id == $id_periodo) selected @endif>{{ $per->codigo }}
                                                            | {{ $per->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                                {{--<input type="text" class="form-control has-feedback-left typeahead" id="txt_periodo" name="txt_periodo" placeholder="Buscar por código o descripción..." autocomplete="off" data-provide="typeahead" value="@if($id_periodo!='' || $id_periodo==0) {{$periodo_des->name}} @endif">
                                                {{--<span class="glyphicon glyphicon-search form-control-feedback left" aria-hidden="true"></span>--}}
                                                {{--<input type="hidden" id="txt_id_periodo_apertura" name="txt_id_periodo_apertura" value="{{ $id_periodo }}">--}}
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label
                                                class="col-md-12 col-sm-12 col-xs-12 alinear_izquierda margen_top_label">Los
                                                siguientes módulos tienen centralización contable en línea: </label>
                                            <br><br>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="flat" id="chk_logistica_almacenes"
                                                               name="chk_logistica_almacenes"
                                                               @if($chkVentas==1) checked @endif> Logística y almacenes
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="flat" id="chk_tesoreria"
                                                               name="chk_tesoreria"
                                                               @if($chkTesoreria==1) checked @endif> Tesorería
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="flat" id="chk_ventas_factura"
                                                               name="chk_ventas_factura"
                                                               @if($chkVentas==1) checked @endif> Ventas y facturación
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">
                                                        <input type="checkbox" class="flat" id="chk_activos"
                                                               name="chk_activos" @if($chkActivos==1) checked @endif>
                                                        Activos
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-xs-12">
                                                    <div class="form-group">

                                                        <input type="checkbox" class="flat" id="chk_cta_pagar"
                                                               name="chk_cta_pagar" @if($chkCpagar==1) checked @endif>
                                                        Cuentas por pagar
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <input type="checkbox" class="flat" id="chk_ag_retencion"
                                                       name="chk_ag_retencion" @if($chkAgente==1) checked @endif> Es
                                                agente de retención
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-md-6 col-xs-12">Vigente desde:</label>
                                                <div class="col-sm-6 col-xs-12">
                                                    <input type="date" id="txt_vig_desde" name="txt_vig_desde"
                                                           class="form-control" value="{{ $agenteDesde }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <input type="checkbox" class="flat" id="chk_activar_pto_rete"
                                                       name="chk_activar_pto_rete" @if($chkPunto==1) checked @endif>
                                                Activar control de puntos de emisión
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="col-md-8 col-xs-12">
                                                    <label class="margen_top_label7">Número máx. de módulos que se ven
                                                        en el menú principal:</label>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    <input type="text" id="txt_maximo_mod" name="txt_maximo_mod"
                                                           class="form-control alinear_centro" style="margin-top: 3px;"
                                                           value="{{ $nroMaxModulo }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="col-md-5 col-xs-12">
                                                    <label class="margen_top_label7">La contraseña de usuario expira
                                                        cada:</label>
                                                </div>
                                                <div class="col-md-2 col-xs-12">
                                                    <input type="text" id="txt_expira_contra" name="txt_expira_contra"
                                                           class="form-control alinear_centro" style="margin-top: 3px;"
                                                           value="{{ $nroUserExpira }}">
                                                </div>
                                                <div class="col-sm-5 col-xs-12">
                                                    <label class="margen_top_label7"> días (valor 0 indica que no
                                                        expira)</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Programación de tareas</div>
                                <div class="panel-body">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <input type="checkbox" class="flat" id="chkCPEs" name="chkCPEs"
                                                   @if($chkCPEs==1) checked @endif> Avisar CPE's próximos a cumplir
                                            límite de tiempo
                                        </div>
                                        <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-md-4 col-xs-12">Ejecutar cada </label>
                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <input type="text" id="nminutos" name="nminutos"
                                                           class="form-control" value="{{ $nminutos }}">
                                                </div>
                                                <label for="" class="col-md-4 col-xs-12">minutos</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/system_configuration.js') }}"></script>
@endsection
