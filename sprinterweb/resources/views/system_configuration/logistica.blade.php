@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Logística y Almacén<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_logistica" name="frm_conf_logistica" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Provisión de Compras</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_orden_obligatoria" id="rb_orden_obligatoria" @if($alm_pideoc==1) checked @endif> Los ingresos por compra requieren Orden de Compra obligatoriamente
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Tipo Transac</label>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_tipo_transaccion" id="txt_tipo_transaccion" class="form-control">
                                                            @foreach($transaccion as $transac)
                                                                <option value="{{ $transac->id }}" @if($transac->id == $alm_tiptra) selected @endif>{{ $transac->codigo }} | {{ $transac->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Doc. Ingreso</label>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_documento_ingreso" id="txt_documento_ingreso" class="form-control select2">
                                                            @foreach($documento as $doc)
                                                                <option value="{{ $doc->id }}" @if($doc->id == $alm_docume) selected @endif>{{ $doc->codigo }} | {{ $doc->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Doc. Salida</label>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_documento_salida" id="txt_documento_salida" class="form-control select2">
                                                            @foreach($documento as $doc)
                                                                <option value="{{ $doc->id }}" @if($doc->id == $alm_docsal) selected @endif>{{ $doc->codigo }} | {{ $doc->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Stocks</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3 col-xs-12">Se controlan por: </label>
                                                <div class="col-md-4 col-sm-4 col-xs-12">
                                                    <select  class="form-control" name="cbo_tipo_control" id="cbo_tipo_control" placeholder="Seleccione tipo">
                                                        <option value="CODIGO" @if($alm_cstock=='CODIGO') selected @endif>CODIGO</option>
                                                        <option value="LOTE" @if($alm_cstock=='LOTE') selected @endif>LOTE</option>
                                                        <option value="SERIE" @if($alm_cstock=='SERIE') selected @endif>SERIE</option>
                                                    </select>
                                                </div>
                                            </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_almacen_control" id="rb_almacen_control" @if($alm_verdet==1) checked @endif> El dato almacén se controla desde el detalle del documento
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_valorizacion_productos" id="rb_valorizacion_productos" @if($alm_valorop==1) checked @endif> La Valorización de Productos Terminados es por Ordén de Producción
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_kardex_anulados" id="rb_kardex_anulados" @if($alm_veranu==1) checked @endif> Ver en el Kardex los movimientos anulados
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_peso_kardex" id="rb_peso_kardex" @if($alm_verpeso==1) checked @endif> Mostrar Peso en el Kardex
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_alm_verguia" id="rb_alm_verguia" @if($alm_verguia==1) checked @endif> En el kardex predomina la guía del proveedor sobre la factura
                                                </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4 col-xs-12">Número de decimales en reportes </label>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <input type="number" class="form-control" id="text_alm_decimal" name="text_alm_decimal" value="{{ $alm_decimal }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Subdiarios y Cuentas</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <label>Subdiario Ingresos de Almacén</label>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div id="the-basics">
                                                                <select name="txt_subdiario_ingreso" id="txt_subdiario_ingreso" class="form-control select2">
                                                                    @foreach($subdiaries as $sud)
                                                                        <option value="{{ $sud->id }}" @if($sud->id == $sub_ingalm) selected @endif>{{ $sud->codigo }} | {{ $sud->descripcion }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <label>Subdiario Salidas de Almacén</label>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div id="the-basics">
                                                                <select name="txt_subdiario_salida" id="txt_subdiario_salida" class="form-control select2">
                                                                    @foreach($subdiaries as $sud)
                                                                        <option value="{{ $sud->id }}" @if($sud->id == $sub_salalm) selected @endif>{{ $sud->codigo }} | {{ $sud->descripcion }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-sm-12 col-xs-12">
                                                        <div class="col-sm-12 col-xs-12">
                                                            <label>Cuenta para los ingresos por Donación</label>
                                                        </div>
                                                        <div class="col-sm-12 col-xs-12">
                                                            <div id="the-basics">
                                                                <select name="txt_cuenta_ingreso_donacion" id="txt_cuenta_ingreso_donacion" class="form-control select2">
                                                                    @foreach($pcg as $pcgs)
                                                                        <option value="{{ $pcgs->id }}" @if($pcgs->id == $alm_ctadon) selected @endif>{{ $pcgs->codigo }} | {{ $pcgs->descripcion }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Otros</div>
                                        <div class="panel-body">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_transferencia_automatica" id="rb_transferencia_automatica" @if($alm_transf==1) checked @endif> Las Trasnferencias entre almacenes es automática (un sólo paso)
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_privilegios_especiales" id="rb_privilegios_especiales" @if($alm_privil==1) checked @endif> Se requieren privilegios especiales para ver documento de almacén)
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_umedida" id="rb_umedida" @if($alm_seleum==1) checked @endif> No se permite seleccionar unidad de medida en salida de Almacén
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_permite_modificar" id="rb_permite_modificar" @if($alm_edtioc==1) checked @endif> No se permite modificar los ingresos con Orden de Compra
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rb_permite_modGuia" id="rb_permite_modGuia" @if($alm_edtguia==1) checked @endif> No se permite modificar las guías/salidas ya facturadas
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rbo_alm_itemrepe" id="rbo_alm_itemrepe" @if($alm_itemrepe==1) checked @endif> No se permite items repetidos en las transferencias
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rbo_alm_salapro" id="rbo_alm_salapro" @if($alm_salapro==1) checked @endif> Las salidas de almacén requiren documento referencia aprobado
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rbo_alm_anuped" id="rbo_alm_anuped" @if($alm_anuped==1) checked @endif> Anular Pedido de Venta, al anular el documento de venta
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rbo_alm_vestado" id="rbo_alm_vestado" @if($alm_vestado==1) checked @endif> Activar control de estados en los ingresos a almacén
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="rbo_alm_partgcc" id="rbo_alm_partgcc" @if($alm_partgcc==1) checked @endif> Partes de Transformación por Fichas(Tipo GCC)
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
