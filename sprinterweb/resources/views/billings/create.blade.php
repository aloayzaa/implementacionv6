@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form id="frm_generales" name="frm_generales"  method="POST">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="id" name="id" value="{{0}}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.billing') }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Registro</div>
                                <div class="panel-body">                        
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12">
                                            <label for="periodo">Periodo:</label>
                                            <input type="text" name="periodo" id="periodo" class="form-control" value="{{$period->descripcion}}" disabled>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="unidad_negocio">Unidad Negocio:</label>
                                            <select name="unidad_negocio" id="unidad_negocio" class="select2">
                                                @foreach($unegocios as $unegocio)
                                                    <option value="{{$unegocio->id}}">{{$unegocio->codigo}} | {{$unegocio->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <label for="numero">Número:</label>
                                            <input type="text" id="numero" name="numero" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="punto_venta">Punto de Venta:</label>
                                            <select name="punto_venta" id="punto_venta" class="select2">
                                                @foreach($puntosventa as $puntoventa)
                                                    <option value="{{$puntoventa->id}}" data-descripcion="{{$puntoventa->descripcion}}">{{$puntoventa->codigo}} | {{$puntoventa->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="tipo_venta">Tipo de Venta:</label>
                                            <select name="tipo_venta" id="tipo_venta" class="select2">
                                                @foreach($tiposventa as $tipoventa)
                                                    <option value="{{$tipoventa->id}}" data-esgratuito="{{$tipoventa->esgratuito}}">{{$tipoventa->codigo}} | {{$tipoventa->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="referencia_op">Referencia OP</label>
                                            <select name="referencia_op" id="referencia_op" class="select2">

                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <a onclick="documentos_referencia()"><i class="flaticon-search" title="Buscar referencia" style="font-size: 15px; color: blue"></i></a>
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <div class="panel panel-info"> 
                                <div class="panel-heading">Documento</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-12">
                                            <label for="tercero">Cliente:</label>
                                            <select name="tercero" id="tercero" class="select2"></select>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <label for="doc_identidad">Doc.Identidad:</label>
                                            <input type="text" id="doc_identidad" name="doc_identidad" class="form-control">
                                        </div>
                                        <div class="col-md-5 col-xs-12">
                                            <label for="direccion">Dirección</label>
                                            <select name="direccion" id="direccion" class="select2"></select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12">
                                            <label for="tipo_doc">Tipo Doc:</label>
                                            <select name="tipo_doc" id="tipo_doc" class="select2">
                                                <option value="">-Selecccionar-</option>
                                                @foreach($documentoscom as $documentocom)
                                                    <option value="{{$documentocom->id}}">{{$documentocom->codigo}} | {{$documentocom->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="serie_doc">Serie:</label>
                                            <input type="text" id="serie_doc" name="serie_doc" class="form-control">
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="numero_doc">Número:</label>
                                            <input type="text" id="numero_doc" name="numero_doc" class="form-control">
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="fecha_proceso">Fecha:</label>
                                            <input type="date" id="fecha_proceso" name="fecha_proceso" class="form-control tipocambio" value="{{$today}}">
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="changerate">T.Cambio:</label>
                                            <input type="text" class="form-control typechange" name="tcambio" id="tcambio" readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="moneda">Moneda:</label>
                                            <select name="moneda" id="moneda" class="select2">
                                                @foreach($monedas as $moneda)
                                                <option value="{{$moneda->id}}">{{$moneda->codigo}} | {{$moneda->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12">
                                            <label for="tcmoneda">T.C.</label>
                                            <input type="text" id="tcmoneda" name="tcmoneda" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-3 col-xs-12">
                                            <label for="condicion_pago">Condición de Pago:</label>
                                            <select name="condicion_pago" id="condicion_pago" class="select2">
                                                <option value="">-Seleccionar-</option>
                                                @foreach($condicionespago as $condicionpago)
                                                    <option value="{{$condicionpago->id}}" data-dias="{{$condicionpago->dias}}">{{$condicionpago->codigo}} | {{$condicionpago->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="vencimiento">Vencimiento:</label>
                                            <input type="date" name="vencimiento" id="vencimiento" class="form-control" value="dd/mm/YYYY" readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="occliente">OC/Cliente:</label>
                                            <input type="text" id="occliente" name="occliente" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Referencia Nota Crédito / Débito</div>   
                                    <div class="panel-body">     
                                        <div class="row">
                                            <div class="col-md-4 col-xs-12">
                                                <label for="tipo_nota" id="label_tipo_nota">Tipo Nota de Crédito:</label>
                                                <select name="tipo_nota" id="tipo_nota" class="select2" disabled></select>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <label for="documento_credito_debito">Documento:</label>
                                                <input type="text" id="documento_credito_debito" name="documento_credito_debito" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-4 col-xs-12" style="margin-top:-6px;">
                                                <label for="importe">Importe: &nbsp;&nbsp;&nbsp;<input type="checkbox" value="1" id="aplicar_credito_debito" name="aplicar_credito_debito"> Aplicar</label>
                                                <input type="text" id="importe" name="importe" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-1 col-xs-12" style="margin-top:14px;margin-left:-24px;">
                                                <button class="btn btn-default" type="button" style="height:27px;" id="buscar_nota" name="buscar_nota"><i class="flaticon-search" title="Buscar" style="font-size 15px; color:blue"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>                
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Tributos</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-12">
                                                <label for="tipo_afectacion_igv">Tipo Afectación IGV</label>
                                                <select name="tipo_afectacion_igv" id="tipo_afectacion_igv" class="select2">
                                                    @foreach($tiposafectaigv as $tipoafectaigv)
                                                        <option value="{{$tipoafectaigv->id}}" data-codigo="{{$tipoafectaigv->codigo}}">{{$tipoafectaigv->codigo}} | {{$tipoafectaigv->descripcion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <label for="igv">IGV:</label>
                                                <select name="igv" id="igv" class="select2">
                                                    @foreach($impuestos as $impuesto)
                                                        <option value="{{$impuesto->id}}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}" @if($impuesto->codigo == '03') selected @endif>{{$impuesto->nombrecorto}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-xs-12">
                                                <label for="percepcion">Percepción:</label>
                                                <select name="percepcion" id="percepcion" class="select2">
                                                    <option value="">-Seleccionar-</option>
                                                    @foreach($impuestos2 as $impuesto)
                                                        <option value="{{$impuesto->id}}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}">{{$impuesto->nombrecorto}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>                
                            </div>                                
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <label for="glosa">Glosa:</label>
                                    <input type="text" id="glosa" name="glosa" class="form-control">
                                </div>
                            </div>
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Detalle</a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#tab_content2" role="tab" id="aplica-tab" data-toggle="tab" aria-expanded="false">Datos Adicionales</a>
                                </li>

                                <li role="presentation" class="">
                                    <a href="#tab_content3" role="tab" id="aplica-tab" data-toggle="tab" aria-expanded="false">Historial</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                    @include('billings.tabs.detalle')
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="aplica-tab">
                                    @include('billings.tabs.datos_adicionales')
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="aplica-tab">
                                    @include('billings.tabs.historial')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label><input type="checkbox" value="1" name="crear_kardex" id="crear_kardex"> Crear Kardex</label>
                                    <input type="text" class="form-control" id="ocompra" name="ocompra" onkeydown="ver_ingresoalmacen_referencia(event)" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="serierem">Guía de Remisión</label>
                                    <input type="text" class="form-control" id="serierem" name="serierem" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12" style="margin-top: 15px;">
                                    <input type="text" class="form-control" id="nrorem" name="nrorem" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="base">Op.Gravada</label>
                                    <input type="number" class="form-control" id="base" name="base">
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="inafecto">No Grav/Exon</label>
                                    <input type="number" class="form-control" id="inafecto" name="inafecto">
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="op_gratuita">Op.Gratuita</label>
                                    <input type="number" class="form-control" id="op_gratuita" name="op_gratuita" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="descuento">(-)Descuento</label>
                                    <input type="number" class="form-control" id="descuento" name="descuento" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="isc_icbper">ISC/ICBPER</label>
                                    <input type="number" class="form-control" id="isc_icbper" name="isc_icbper" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="impuesto">I.G.V</label>
                                    <input type="number" class="form-control" id="impuesto" name="impuesto" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="impuesto2">Percepción</label>
                                    <input type="number" class="form-control" id="impuesto2" name="impuesto2" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="total">Total</label>
                                    <input type="number" class="form-control" id="total" name="total" readonly>
                                </div>
                            </div>
                            <input type="hidden" value="" id="lote" name="lote"> {{--Provisional hasta que se trabaje con lotes--}}
                        </div>
                    </div>
                </div>
            </form>
            @include('billings.modals.documentos_referencia')
            @include('billings.modals.detalles')
            @include('billings.modals.nota_credito_debito')
            @include('billings.modals.ingresoalmacen_referencia')
            @include('billings.modals.historial_aplicar')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/billing.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        function producto(id) {
            let url = '{{ route('edit.products', ["id" => ":id"]) }}';
            window.open(url.replace(':id', id), "_blank");
        }
     
    </script>
@endsection
