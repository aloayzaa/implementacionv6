@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Planillas<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_planillas" name="frm_conf_planillas" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="col-md-5 col-sm-5 col-xs-12 alinear_izquierda margen_top_label">Subdiario para Movimientos de Planillas: </label>
                                    <div class="col-md-7 col-sm-7 col-xs-12">
                                        <select name="txt_subdiario_movpla" id="txt_subdiario_movpla" class="form-control select2">
                                            <option value=""@if($sub_planilla== 0) selected @endif>Seleccionar -</option>
                                            @foreach($subdiaries as $sub)
                                                <option value="{{ $sub->id }}" @if($sub->id == $sub_planilla) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Asistencia</div>
                                    <div class="panel-body">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_confirmar_asistencia" name="chk_confirmar_asistencia" @if($pla_conasist == 1) checked @endif> Pedir Confirmar en la Asistencia
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_asistencia_tipopla" name="chk_asistencia_tipopla" @if($pla_asixtipo == 1) checked @endif> El parte de Asistencia es por tipo de planilla
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_registro_tareo" name="chk_registro_tareo" @if($pla_astareo == 1) checked @endif> El registro de tareo requiere asistencia de personal
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_horas_extras" name="chk_horas_extras" @if($pla_authext == 1) checked @endif> Las horas extras requieren autorización
                                                </label>
                                            </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Provisiones</div>
                                    <div class="panel-body">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_calculo_prov" name="chk_calculo_prov" @if($pla_gracts == 1) checked @endif> Asignar 1/6 de Gratificación al cálculo de la Provisión de CTS
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_conceptos_prov" name="chk_conceptos_prov" @if($pla_provfijo == 1) checked @endif> Calcular Provisión sólo sobre conceptos fijos
                                                </label>
                                            </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Gratificaciones</div>
                                    <div class="panel-body">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_meses_gratificacion" name="chk_meses_gratificacion" @if($pla_gracom == 1) checked @endif> Calcular meses completos para tiempo computable
                                                </label>
                                            </div>
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_gratificacion_mes" name="chk_gratificacion_mes" @if($pla_grames == 1) checked @endif> Calcular Gratificación con periodo laborado menor a un mes
                                                </label>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <input type="checkbox" class="flat" id="chk_planillas_proceso" name="chk_planillas_proceso" @if($pla_vermes == 1) checked @endif> Mostar solo las planillas del mes en proceso
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Envío Correo Electrónico</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-2 col-xs-12 margen_top_label">Servidor
                                                </label>
                                                <div class="col-sm-6 col-xs-12">
                                                    <input type="text" id="txt_servidor_pla" name="txt_servidor_pla" class="form-control" value="{{ $pla_servidor }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-2 col-xs-12 margen_top_label">Puerto
                                                </label>
                                                <div class="col-sm-6 col-xs-12">
                                                    <input type="text" id="txt_puerto_pla" name="txt_puerto_pla" class="form-control" value="{{ $pla_puerto }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-2 col-xs-12 margen_top_label">Usuario
                                                </label>
                                                <div class="col-sm-6 col-xs-12">
                                                    <input type="text" id="txt_usuario_pla" name="txt_usuario_pla" class="form-control" value="{{ $pla_usuario }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-2 col-xs-12 margen_top_label">Clave
                                                </label>
                                                <div class="col-sm-6 col-xs-12">
                                                    <input type="password" id="txt_clave_pla" name="txt_clave_pla" class="form-control" value={{ $pla_clave }}"">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-2 col-xs-12 margen_top_label">Copia A
                                                </label>
                                                <div class="col-sm-6 col-xs-12">
                                                    <input type="text" id="txt_copia_pla" name="txt_copia_pla" class="form-control" value="{{ $pla_copiaa }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-12 col-xs-12 margen_top_label">
                                                    <input type="checkbox" class="flat" id="chk_firma_digital" name="chk_firma_digital" @if($pla_verfirma == 1) checked @endif> Mostrar firma digital en el PDF
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">PLAME</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-sm-12 col-xs-12">
                                                <label class="col-sm-4 col-xs-12 margen_top_label">Usar para horas ordinarias
                                                </label>
                                                <div class="col-sm-3 col-xs-12">
                                                    <input type="text" id="txt_pla_plameho" name="txt_pla_plameho" class="form-control" value="{{ $pla_plameho }}">
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
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/system_configuration.js') }}"></script>
@endsection
