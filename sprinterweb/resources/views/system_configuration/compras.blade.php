@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Compras - Cuentas por pagar<small></small></h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_compras" name="frm_conf_compras" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Tipos de Documento</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Anticipo Proveedores</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_anticipo_proveedor" id="txt_anticipo_proveedor" class="form-control select2">
                                                            @foreach($documentotipo as $document)
                                                                <option value="{{ $document->id }}" @if($document->id == $antxpagar) selected @endif>{{ $document->codigo }} || {{ $document->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Comprobantes de Detracción</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_documento_detraccion" id="txt_documento_detraccion" class="form-control select2">
                                                            @foreach($documentotipo as $document)
                                                                <option value="{{ $document->id }}" @if($document->id == $docdetrac) selected @endif>{{ $document->codigo }} || {{ $document->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-4 col-sm-4 col-xs-12">Comprobantes de Retención</label>
                                                <div class="col-md-8 col-sm-8 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_documento_retencion" id="txt_documento_retencion" class="form-control select2">
                                                            @foreach($documentotipo as $document)
                                                                <option value="{{ $document->id }}" @if($document->id == $docreten) selected @endif>{{ $document->codigo }} || {{ $document->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Ordenes de Compra / Servicio</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_fijar_fecha" id="chk_fijar_fecha" @if($fijfechaoc==1) checked @endif>Fijar el Campo fecha en las Ordenes de Compra
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_tipo_peso" id="chk_tipo_peso" @if($tipopeso==1) checked @endif>La Orden de Compra solicita Tipo Peso
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_aprueba" id="chk_aprueba" @if($printocom==1) checked @endif>La Orden de Compra requiere aprobación para ser impresa
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_itemrepecom" id="chk_itemrepecom" @if($itemrepecom==1) checked @endif>No se permiten items repetidos en la orden de compra
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_occpedcom" id="chk_occpedcom" @if($occpedcom==1) checked @endif>Bloquear Orden de Compra, cuando se jala Pedidos
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_actualizar_precios" id="chk_actualizar_precios" @if($actpventa==1) checked @endif>Actualizar precios de venta desde Ordenes de Compra
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_requiere_orden" id="chk_requiere_orden" @if($reqoserv==1) checked @endif>Se requiere Confirmar Ordenes de Servicio
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_confggcom" id="chk_confggcom" @if($confggcom==1) checked @endif>Las Confirmaciones generan provisión de gasto
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Otras Propiedades</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_excluir_productos" id="chk_excluir_productos" @if($excpdto==1) checked @endif>Excluir de las búsquedas de productos los productos terminados
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_excluir_anulados" id="chk_excluir_anulados" @if($excanul==1) checked @endif>Excluir los documentos anulados del Registro de Compras
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        Redondear cálculo detracción a (n) decimales
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <input type="text" id="txt_numero_decimales" name="txt_numero_decimales" class="form-control" value="{{$reddetrac}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        Rango días para reporte de Saldo
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <input type="text" id="txt_dias_reporte" name="txt_dias_reporte" class="form-control" value="{{$rangoxpag}}">
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
