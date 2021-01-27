@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Activo Fijo</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_activos" name="frm_conf_activos" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-sm-12 col-xs-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Parametro de Codificación de Activos</div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-sm-5 col-xs-12 col-md-offset-1">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                                            <div class="row">
                                                                <label class="margen_top_label">El Código se Genera</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                                            <select  class="form-control" name="cbo_genera_codigo" id="cbo_genera_codigo" placeholder="Seleccione tipo">
                                                                <option value="AUTOMATICO" @if ($act_codigo=='AUTOMATICO') selected @endif>AUTOMATICO</option>
                                                                <option value="MANUAL" @if ($act_codigo=='MANUAL') selected @endif>MANUAL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-5 col-xs-12 col-md-offset-1">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                                            <div class="row">
                                                                <label class="margen_top_label">Catálogo Existencias</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12">
                                                            <select  class="form-control" name="cbo_act_catexi" id="cbo_act_catexi" placeholder="Seleccione tipo">
                                                                <option value="1" @if($act_catexi == 1) selected @endif>Naciones Unidas</option>
                                                                <option value="3" @if($act_catexi == 3) selected @endif>GS1 (EAN-UCC)</option>
                                                                <option value="9" @if($act_catexi == 9) selected @endif>Otros</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5 col-xs-12 col-md-offset-1">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                                                            <div class="row">
                                                                <div class="row">
                                                                    <label class="col-sm-12 col-xs-12">Longitud del Código</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-sm-7 col-xs-12">
                                                            <input type="text" name="txt_longitud_codigo" id="txt_longitud_codigo" class="form-control input_cart alinear_derecha" autocomplete="off" placeholder="0" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="{{$act_longit}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Subdiarios para los movimientos de Activos</div>
                                    <div class="panel-body">
                                    <div class="col-sm-7 col-xs-12 col-md-offset-1">
                                        <div class="form-group" id="scrollable-dropdown-menu">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                    <div class="row">
                                                        <label class="margen_top_label">Depreciación</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                                    <div id="scrollable-dropdown-menu">
                                                        <select name="txt_depreciacion" id="txt_depreciacion" class="form-control select2">
                                                            @foreach($subdiaries as $sub)
                                                                <option value="{{ $sub->id }}" @if($sub->id == $sub_depact) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-xs-12 col-md-offset-1">
                                        <div class="form-group" id="scrollable-dropdown-menu">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                    <div class="row">
                                                        <label class="margen_top_label">Bajas</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                                    <div id="scrollable-dropdown-menu">
                                                        <select name="txt_baja" id="txt_baja" class="form-control select2">
                                                            @foreach($subdiaries as $sub)
                                                                <option value="{{ $sub->id }}" @if($sub->id == $sub_bajact) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-6">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Parametros para Depreciación</div>
                                    <div class="panel-body">
                                        <div class="col-sm-7 col-xs-12 col-md-offset-1">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                                        <div class="row">
                                                            <label class="margen_top_label">Periodo Anterior</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                                        <div id="scrollable-dropdown-menu">
                                                            <select name="txt_periodo_anterior" id="txt_periodo_anterior" class="form-control select2">
                                                                @foreach($periodo as $per)
                                                                    <option value="{{ $per->id }}" @if($per->id == $act_ejeant) selected @endif>{{ $per->codigo }} | {{ $per->descripcion }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-1">
                                                <input type="checkbox" class="" name="rb_es_consignacion" @if($act_dpdias==1) checked @endif> Depreciar el Equivalente a Días en el periodo de inicial
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
