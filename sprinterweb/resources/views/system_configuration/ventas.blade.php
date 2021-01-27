@extends('templates.home')
@section('content')
    <div class="row-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
            @include('system_configuration.menu.menu')
            <div class="col-sm-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2> Ventas - Cuentas por cobrar<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal form-label-left input_mask" id="frm_conf_ventas" name="frm_conf_ventas" method="post">
                            <input type="hidden" id="var" name="var" value="{{ $var }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <div class="col-sm-7 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Documentos de Venta</div>
                                        <div class="panel-body">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_negativos" id="chk_negativos" @if($valneg==1) checked @endif> Se permite valores negativos
                                                    </div>
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_excluir_productos" id="chk_excluir_productos" @if($unicopdto==1) checked @endif> No se permite repetir productos
                                                    </div>
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_validad_stock" id="chk_validad_stock" @if($constock==1) checked @endif> Validar stock dosponible
                                                    </div>
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_llenado_item" id="chk_llenado_item" @if($ventaux==1) checked @endif> LLenado de Items en ventana auxiliar
                                                    </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_tipo_precio" id="chk_tipo_precio" @if($tipoprecio==1) checked @endif> Mostrar tipo de venta (Tarifa)
                                                    </div>
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_bloquea_print" id="chk_bloquea_print" @if($bloqprint==1) checked @endif> Bloquear impresión de documentos después de impresos
                                                    </div>
                                                    <div class="col-md-10 col-sm-10 col-xs-12">
                                                        <input type="checkbox" class="flat" name="chk_control_cred" id="chk_control_cred" @if($ctrlcred==1) checked @endif> Activar Control de Créditos
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Precio de Venta</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_incluye_igv" id="chk_incluye_igv" @if($precioimp==1) checked @endif> El precio de venta incluye impuestos
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <label for="">Tipo de tarifario</label>
                                                    <select class="form-control" id="cbo_valtarifa" name="cbo_valtarifa">
                                                        <option value="0" @if($valtarifa==0)selected @endif>SIMPLE</option>
                                                        <option value="1" @if($valtarifa==1)selected @endif>CLIENTE</option>
                                                        <option value="2" @if($valtarifa==2)selected @endif>SUCURSAL</option>
                                                        <option value="3" @if($valtarifa==3)selected @endif>LABORATORIO</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <label for="">% Margen de Utilidad</label>
                                                    <input type="text" id="txt_utilidad" name="txt_utilidad" class="form-control" value="{{ $marutilidad }}">
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <label for="">% Gasto Administrativo</label>
                                                    <input type="text" id="txt_gasto_administrativo" name="txt_gasto_administrativo" class="form-control" value="{{ $gastoadm }}">
                                                </div>
                                                <div class="col-md-3 col-sm-3 col-xs-12">
                                                    <label for="">Tipo de cambio</label>
                                                    <input type="text" id="txt_tipo_gasto" name="txt_tipo_gasto" class="form-control" value="{{ $tcambio }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        Nemónico para ver precio en etiquetas
                                                    </div>
                                                    <div class="col-sm-4 col-xs-12">
                                                        <input type="text" id="txt_nemonico" name="txt_nemonico" class="form-control" value="{{ $nemprecio }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        El usuario puede cambiar el precio de venta
                                                    </div>
                                                    <div class="col-sm-4 col-xs-12">
                                                        <select class="form-control" id="cbo_cambia_precio" name="cbo_cambia_precio">
                                                            <option value="0" @if($preventa==0)selected @endif>SI</option>
                                                            <option value="1" @if($preventa==1)selected @endif>SOLO MAYOR</option>
                                                            <option value="2" @if($preventa==2)selected @endif>NO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Puntos de Venta</div>
                                        <div class="panel-body">
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_printftped" id="chk_printftped" @if($valprintftped==1) checked @endif> Activar impresión en Facturar Pedido
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_productos_xpunto" id="chk_productos_xpunto" @if($pdtoxpunto==1) checked @endif> Filtrar productos por puntos de venta
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_fecha_productos" id="chk_fecha_productos" @if($fechaxpunto==1) checked @endif> No se permite modificar la fecha de facturación
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_facturar_pedidos" id="chk_facturar_pedidos" @if($fxpunto==1) checked @endif> Solo Facturar los Pedidos del Día
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_valida_documento" id="chk_valida_documento" @if($fdocxpunto==1) checked @endif> Validar Tipo Documento por Punto de Venta
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_genera_kardex" id="chk_genera_kardex" @if($fpedkardex==1) checked @endif> Generar Kardex al Aprobar el Pedido de Venta
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_ftdigcant" id="chk_ftdigcant" @if($valftdigcant==1) checked @endif> Se debe digitar la cantidad para pasar al siguiente item
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5 col-xs-12">
                                    <div class="panel panel-info">
                                        <div class="panel-heading">Otros</div>
                                        <div class="panel-body">
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-6 col-sm-6 col-xs-12">T. Movimiento pora devoluciones por venta</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_tmovimiento" id="txt_tmovimiento" class="form-control select2">
                                                            <option value="0" @if($tmoventa == 0) selected @endif> Seleccionar -</option>
                                                            @foreach($tipomovimiento as $tm)
                                                                <option value="{{ $tm->id }}" @if($tm->id == $tmoventa) selected @endif>{{ $tm->codigo }} | {{ $tm->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-6 col-sm-6 col-xs-12">Impuesto usado para el IGV</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_impuesto_igv" id="txt_impuesto_igv" class="form-control select2">
                                                            <option value="0" @if($impigv == 0) selected @endif> Seleccionar -</option>
                                                            @foreach($taxes as $tax)
                                                                <option value="{{ $tax->id }}" @if($tax->id == $impigv) selected @endif>{{ $tax->codigo }} | {{ $tax->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-6 col-sm-6 col-xs-12">Impuesto usado para el Consumo</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_impuesto_consumo" id="txt_impuesto_consumo" class="form-control select2">
                                                            <option value="0" @if($impcon == 0) selected @endif> Seleccionar -</option>
                                                            @foreach($taxes as $tax)
                                                                <option value="{{ $tax->id }}" @if($tax->id == $impcon) selected @endif>{{ $tax->codigo }} | {{ $tax->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-6 col-sm-6 col-xs-12">Tipo de Documento - Comanda</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_tdocumento_comanda" id="txt_tdocumento_comanda" class="form-control select2">
                                                            <option value="0" @if($docomanda == 0) selected @endif> Seleccionar -</option>
                                                            @foreach($documento as $doc)
                                                                <option value="{{ $doc->id }}" @if($doc->id == $docomanda) selected @endif>{{ $doc->codigo }} | {{ $doc->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-6 col-sm-6 col-xs-12">Cuenta Descuentos a Terceros</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuenta_terceros" id="txt_cuenta_terceros" class="form-control select2">
                                                            <option value="0" @if($vendescter == 0) selected @endif> Seleccionar -</option>
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $vendescter) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="scrollable-dropdown-menu">
                                                <label class="control-label col-md-6 col-sm-6 col-xs-12">Cuenta Descuentos a Relacionadas</label>
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <div id="the-basics">
                                                        <select name="txt_cuenta_relacionadas" id="txt_cuenta_relacionadas" class="form-control select2">
                                                            <option value="0" @if($vendescrel == 0) selected @endif> Seleccionar -</option>
                                                            @foreach($pcg as $p)
                                                                <option value="{{ $p->id }}" @if($p->id == $vendescrel) selected @endif>{{ $p->codigo }} | {{ $p->descripcion }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_val_correlativo" id="chk_val_correlativo" @if($valcorr==1) checked @endif> Validad correlatividad de Registro de Ventas
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_vta_cendto" id="chk_vta_cendto" @if($val_vta_cendto==1) checked @endif> Discriminar los descuentos concedidos en la centralización
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-10 col-sm-10 col-xs-12">
                                                    <input type="checkbox" class="flat" name="chk_vta_anubco" id="chk_vta_anubco" @if($val_vta_anubco==1) checked @endif> Anular Mov. Caja al anular Venta(Sólo contados)
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-sm-6 col-xs-12 ">
                                                        Rango días para reporte de saldos
                                                    </div>
                                                    <div class="col-sm-4 col-xs-12">
                                                        <input type="text" id="text_sld_rangoc" name="text_sld_rangoc" class="form-control" value="{{ $val_sld_rangoc }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-sm-6 col-xs-12 ">
                                                        Cálculo de Comisión de Venta por
                                                    </div>
                                                    <div class="col-sm-4 col-xs-12">
                                                        <select name="cbo_vta_calcom" id="cbo_vta_calcom" class="form-control">
                                                            <option value="M" @if($val_vta_calcom == 'M') selected @endif>MarcaPdto</option>
                                                            <option value="U" @if($val_vta_calcom == 'U') selected @endif>Utilidad</option>
                                                            <option value="T" @if($val_vta_calcom == 'T') selected @endif>MetaxMarca</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="col-sm-8 col-xs-12 ">
                                                        Redondear cálculo detracción a (n) decimales
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <input type="text" id="txt_numero_decimales" name="txt_numero_decimales" class="form-control" value="{{ $reddetrac }}">
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
