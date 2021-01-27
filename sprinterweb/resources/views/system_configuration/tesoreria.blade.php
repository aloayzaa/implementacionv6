@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Tesorería<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_tesoreria" name="frm_conf_tesoreria" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Parámetros de los Movimientos</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_aprueba" id="chk_aprueba" @if($banaprueba==1) checked @endif> Los pagos de documentos requieren aprobación
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_bcoprgpago" id="chk_bcoprgpago" @if($bco_prgpago==1) checked @endif> La programación de pagos no es consolidada
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_bcocheque" id="chk_bcocheque" @if($bco_cheque==1) checked @endif> Controlar numeración de cheques(bloquear correlativo)
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        Tipo Cambio para Mov. de Caja
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <select class="form-control" id="cbo_caja" name="cbo_caja">
                                                            <option value="COMPRA" @if($tcaja=='COMPRA') selected @endif>COMPRA</option>
                                                            <option value="VENTA" @if($tcaja=='VENTA') selected @endif>VENTA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        Tipo Cambio para Mov. de Bancos
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <select class="form-control" id="cbo_banco" name="cbo_banco">
                                                            <option value="COMPRA" @if($tbanco=='COMPRA') selected @endif>COMPRA</option>
                                                            <option value="VENTA" @if($tbanco=='VENTA') selected @endif>VENTA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Parámetros para Transferencias(Tránsito)</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta M.N.</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentamn" id="txt_cuentamn" class="form-control select2">
                                                            <option value="0" @if(0 == $subing) selected @endif>Seleccionar -</option>
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $ctaTransmn) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-3 col-sm-3 col-xs-12">Cuenta M.E.</label>
                                                <div class="col-md-9 col-sm-9 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuentame" id="txt_cuentame" class="form-control select2">
                                                            <option value="0" @if(0 == $ctaTransme) selected @endif>Seleccionar -</option>
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $ctaTransme) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Subdiarios y Tipos de Operacion</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Ingresos</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_subingreso" id="txt_subingreso" class="form-control select2">
                                                            <option value="0" @if(0 == $subing) selected @endif>Seleccionar -</option>
                                                            @foreach($subdiaries as $sub)
                                                                <option value="{{ $sub->id }}" @if($sub->id == $subing) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Salidas</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_subegreso" id="txt_subegreso" class="form-control select2">
                                                            <option value="0" @if(0 == $subeg) selected @endif>Seleccionar -</option>
                                                            @foreach($subdiaries as $sub)
                                                                <option value="{{ $sub->id }}" @if($sub->id == $subeg) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Letras por Cobrar</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_subxcobrar" id="txt_subxcobrar" class="form-control select2">
                                                            <option value="0" @if(0 == $sublcobrar) selected @endif>Seleccionar -</option>
                                                            @foreach($subdiaries as $sub)
                                                                <option value="{{ $sub->id }}" @if($sub->id == $sublcobrar) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Letras por Pagar</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_subxpagar" id="txt_subxpagar" class="form-control select2">
                                                            <option value="0" @if(0 == $sublpagar) selected @endif>Seleccionar -</option>
                                                            @foreach($subdiaries as $sub)
                                                                <option value="{{ $sub->id }}" @if($sub->id == $sublpagar) selected @endif>{{ $sub->codigo }} | {{ $sub->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Operación Bancaria para Anticipo de Clientes</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_anticipo_cliente_tes" id="txt_anticipo_cliente_tes" class="form-control select2">
                                                            <option value="0" @if(0 == $opecli) selected @endif>Seleccionar -</option>
                                                            @foreach($tipo_operacion_I as $to)
                                                                <option value="{{ $to->id }}" @if($to->id == $opecli) selected @endif>{{ $to->codigo }} | {{ $to->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Operación para Cobranza</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="cbo_opecobro" id="cbo_opecobro" class="form-control select2">
                                                            <option value="0" @if(0 == $opecobro) selected @endif>Seleccionar -</option>
                                                            @foreach($tipo_operacion_I as $to)
                                                                <option value="{{ $to->id }}" @if($to->id == $opecobro) selected @endif>{{ $to->codigo }} | {{ $to->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Operación Bancaria para Anticipo a Proveedores</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_anticipo_proveedor_tes" id="txt_anticipo_proveedor_tes" class="form-control select2">
                                                            <option value="0" @if(0 == $opeprov) selected @endif>Seleccionar -</option>
                                                            @foreach($tipo_operacion_E as $to)
                                                                <option value="{{ $to->id }}" @if($to->id == $opeprov) selected @endif>{{ $to->codigo }} | {{ $to->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control col-md-12 col-sm-12 col-xs-12">Operación Bancaria para Pago a Proveedores</label>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="cbo_opepagos" id="cbo_opepagos" class="form-control select2">
                                                            <option value="0" @if(0 == $opepagos) selected @endif>Seleccionar -</option>
                                                            @foreach($tipo_operacion_E as $to)
                                                                <option value="{{ $to->id }}" @if($to->id == $opepagos) selected @endif>{{ $to->codigo }} | {{ $to->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
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
