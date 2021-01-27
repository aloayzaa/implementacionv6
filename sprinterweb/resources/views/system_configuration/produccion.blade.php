@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Producción<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_produccion" name="frm_conf_produccion" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="chk_pro_numeop" id="chk_pro_numeop" @if($pro_numeop==1) checked @endif> La numeración de las órdenes es manual
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="chk_pro_genped" id="chk_pro_genped" @if($pro_genped==1) checked @endif> La formulación en las órdenes es manual
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="chk_pro_genfor" id="chk_pro_genfor" @if($pro_genfor==1) checked @endif> Los Pedidos de las Ordenes de Producción se generan manualmente
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="rb_es_consignacion" {{--@if($act_dpdias==1) checked @endif--}}> Generar Pedidos por Actividad
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="chk_pro_factop" id="chk_pro_factop" @if($pro_factop==1) checked @endif> Se puede facturar Ordenes sin cerrar
                                        </div>
                                        <div class="form-group">
                                            <input type="checkbox" name="chk_trs_ordfact" id="chk_trs_ordfact" @if($trs_ordfact==1) checked @endif> Se puede asignar gastos a los órdenes luego de cerradas o facturadas
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group">
                                                    <label class="margen_top_label">Tipo de Movimiento para generar Pedido de Producción</label>
                                                    <select  class="form-control select2" name="cbo_pro_motivo" id="cbo_pro_motivo">
                                                        <option value="">Seleccionar -</option>
                                                        @foreach($tipo_movimiento as $tm)
                                                            <option value="{{ $tm->id }}" @if($tm->id == $pro_motivo) selected @endif>{{ $tm->codigo }} | {{ $tm->descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="margen_top_label">Tipo de Movimiento para generar Pedido de Venta</label>
                                                    <select  class="form-control select2" name="cbo_vta_motivo" id="cbo_vta_motivo">
                                                        @foreach($tipo_movimiento as $tm)
                                                            <option value="{{ $tm->id }}" @if($tm->id == $vta_motivo) selected @endif>{{ $tm->codigo }} | {{ $tm->descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div>
                                    <h4>Agrícola</h4>
                                </div>
                                <div class="col-sm-6 col-xs-12 col-md-offset-1">
                                    <div class="form-group" id="scrollable-dropdown-menu">
                                        <div class="row">
                                        <div class="row">
                                            <div class="form-group">
                                                <input type="checkbox" name="chk_arg_pideoa" id="chk_arg_pideoa" @if($arg_pideoa==1) checked @endif> Los Registros de Aplicación requieren Orden de Aplicación obligatoriamente
                                            </div>
                                            <div class="form-group">
                                                <label class="margen_top_label">Producto que idenfica el agua</label>
                                                <select name="cbo_arg_pdtoag" id="cbo_arg_pdtoag" class="form-control select2">
                                                    {{--
                                                    @foreach($subdiaries as $sub)
                                                        <option value="{{ $sub->id }}" @if($sub->id == $sub_bajact) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                    @endforeach
                                                    --}}
                                                </select>
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
