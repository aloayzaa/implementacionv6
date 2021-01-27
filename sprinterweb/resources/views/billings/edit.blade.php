@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form id="frm_generales" name="frm_generales"  method="PUT">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="id" name="id" value="{{ $docxpagar->id }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" name="ruta" id="ruta" value="{{ route('update.billing', $docxpagar->id) }}">
                <input type="hidden" name="sunat_estado" id="sunat_estado" value="{{$sunat_estado}}">
                <input type="hidden" name="sunat_codigo" id="sunat_codigo" value="{{$sunat_codigo}}">
                <input type="hidden" name="sunat_descripcion" id="sunat_descripcion" value="{{$sunat_descripcion}}">
                <input type="hidden" name="respuesta_contabiliza" id="respuesta_contabiliza" value="{{$respuesta_contabiliza}}">
                <input type="hidden" name="respuesta_centraliza" id="respuesta_centraliza" value="{{$respuesta_centraliza}}">
                
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
                                                    <option value="{{$unegocio->id}}" @if($docxpagar->unegocio_id == $unegocio->id) selected @endif>{{$unegocio->codigo}} | {{$unegocio->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <label for="numero">Número:</label>
                                            <input type="text" id="numero" name="numero" class="form-control" value="{{$docxpagar->numero}}" disabled>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="punto_venta">Punto de Venta:</label>
                                            <select name="punto_venta" id="punto_venta" class="select2" disabled>
                                                @foreach($puntosventa as $puntoventa)
                                                    <option value="{{$puntoventa->id}}" data-descripcion="{{$puntoventa->descripcion}}" @if($docxpagar->puntoventa_id == $puntoventa->id) selected @endif>{{$puntoventa->codigo}} | {{$puntoventa->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="tipo_venta">Tipo de Venta:</label>
                                            <select name="tipo_venta" id="tipo_venta" class="select2" disabled>
                                                @foreach($tiposventa as $tipoventa)
                                                    <option value="{{$tipoventa->id}}" data-esgratuito="{{$tipoventa->esgratuito}}" @if($docxpagar->tipoventa_id == $tipoventa->id) selected @endif>{{$tipoventa->codigo}} | {{$tipoventa->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="referencia_op">Referencia OP</label>
                                            <select name="referencia_op" id="referencia_op" class="select2">
                                                @if($docxpagar_otrabajo)
                                                    @foreach($docxpagar_otrabajo as $d)
                                                        @if($d->ordentrabajo_id)
                                                            <option value="{{$d->ordentrabajo_id}}" data-ventana="{{$d->ventana}}">{{$d->nromanual}}</option>
                                                        @else
                                                            <option value="{{$d->cotizacion_id}}" data-ventana="{{$d->ventana}}">{{$d->nromanual}}</option>
                                                        @endif                                                        
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12">
                                            <a><i class="flaticon-search" title="Buscar referencia" style="font-size: 15px; color: blue"></i></a>
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
                                            <select name="tercero" id="tercero" class="select2" disabled>
                                                <option value="{{$tercero_docxpgar->id}}">{{$tercero_docxpgar->codigo}} | {{$tercero_docxpgar->descripcion}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <label for="doc_identidad">Doc.Identidad:</label>
                                            <input type="text" id="doc_identidad" name="doc_identidad" class="form-control" value="{{$tercero_docxpgar->nrodocidentidad}}" disabled>
                                        </div>
                                        <div class="col-md-5 col-xs-12">
                                            <label for="direccion">Dirección</label>
                                            <select name="direccion" id="direccion" class="select2" disabled>
                                                <option value="{{$tercero_docxpgar->ubigeo_id}}">{{$tercero_docxpgar->direccion}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12">
                                            <label for="tipo_doc">Tipo Doc:</label>
                                            <select name="tipo_doc" id="tipo_doc" class="select2" disabled>
                                                <option value="">-Selecccionar-</option>
                                                @foreach($documentoscom as $documentocom)
                                                    <option value="{{$documentocom->id}}" @if($docxpagar->documento_id == $documentocom->id) selected @endif>{{$documentocom->codigo}} | {{$documentocom->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="serie_doc">Serie:</label>
                                            <input type="text" id="serie_doc" name="serie_doc" class="form-control" value="{{$docxpagar->seriedoc}}" disabled>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="numero_doc">Número:</label>
                                            <input type="text" id="numero_doc" name="numero_doc" class="form-control" value="{{$docxpagar->numerodoc}}" disabled>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="fecha_proceso">Fecha:</label>
                                            <input type="date" id="fecha_proceso" name="fecha_proceso" class="form-control tipocambio" value="{{$docxpagar->fechadoc}}" disabled>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="changerate">T.Cambio:</label>
                                            <input type="text" class="form-control typechange" name="tcambio" id="tcambio" value="{{$docxpagar->tcambio}}" readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="moneda">Moneda:</label>
                                            <select name="moneda" id="moneda" class="select2" disabled>
                                                @foreach($monedas as $moneda)
                                                <option value="{{$moneda->id}}" @if($docxpagar->moneda_id == $moneda->id) selected @endif>{{$moneda->codigo}} | {{$moneda->descripcion}}</option>
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
                                                    <option value="{{$condicionpago->id}}" data-dias="{{$condicionpago->dias}}" @if($docxpagar->condicionpago_id == $condicionpago->id) selected @endif>{{$condicionpago->codigo}} | {{$condicionpago->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <label for="vencimiento">Vencimiento:</label>
                                            <input type="date" name="vencimiento" id="vencimiento" class="form-control" value="{{$docxpagar->vencimiento}}" readonly>
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
                                                <select name="tipo_nota" id="tipo_nota" class="select2" disabled>
                                                    @if($docxpagar->tiponotacredito_id != null || $docxpagar->tiponotadebito_id != null)
                                                        <option value="{{$notacreditodebito->id}}">{{$notacreditodebito->codigo}} | {{$notacreditodebito->descripcion}}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <label for="documento_credito_debito">Documento:</label>
                                                @if($docxpagar->tiponotacredito_id != null || $docxpagar->tiponotadebito_id != null)
                                                    <input type="text" id="documento_credito_debito" name="documento_credito_debito" class="form-control" value="{{$referencia->docrefer}}" data-id="{{$referencia->referencia_id}}" readonly>
                                                @else
                                                <input type="text" id="documento_credito_debito" name="documento_credito_debito" class="form-control" value="" readonly>
                                                @endif                                                                                                
                                            </div>
                                            <div class="col-md-4 col-xs-12" style="margin-top:-6px;">
                                                @if($docxpagar->tiponotacredito_id != null || $docxpagar->tiponotadebito_id != null)
                                                    <label for="importe">Importe: &nbsp;&nbsp;&nbsp;<input type="checkbox" value="" id="aplicar_credito_debito" name="aplicar_credito_debito" @if($referencia->aplicar == 1) checked @endif> Aplicar</label>
                                                    <input type="text" id="importe" name="importe" class="form-control" value="{{$referencia->importe}}" readonly>
                                                @else
                                                    <label for="importe">Importe: &nbsp;&nbsp;&nbsp;<input type="checkbox" value="" id="aplicar_credito_debito" name="aplicar_credito_debito"> Aplicar</label>
                                                    <input type="text" id="importe" name="importe" class="form-control" readonly>
                                                @endif                                            
                                            </div>
                                            <div class="col-md-1 col-xs-12" style="margin-top:14px;margin-left:-24px;">
                                                <button class="btn btn-default" type="button" style="height:27px;"><i class="flaticon-search" title="Buscar" style="font-size 15px; color:blue"></i></button>
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
                                                <select name="tipo_afectacion_igv" id="tipo_afectacion_igv" class="select2" disabled>
                                                    @foreach($tiposafectaigv as $tipoafectaigv)
                                                        <option value="{{$tipoafectaigv->id}}" data-codigo="{{$tipoafectaigv->codigo}}" @if($docxpagar->tipoafectaigv_id == $tipoafectaigv->id) selected @endif>{{$tipoafectaigv->codigo}} | {{$tipoafectaigv->descripcion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-xs-12">
                                                <label for="igv">IGV:</label>
                                                <select name="igv" id="igv" class="select2" disabled>
                                                    @foreach($impuestos as $impuesto)
                                                        <option value="{{$impuesto->id}}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}" @if($docxpagar->impuesto_id == $impuesto->id) selected @endif>{{$impuesto->nombrecorto}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 col-xs-12">
                                                <label for="percepcion">Percepción:</label>
                                                <select name="percepcion" id="percepcion" class="select2" disabled>
                                                    <option value="">-Seleccionar-</option>
                                                    @foreach($impuestos2 as $impuesto)
                                                        <option value="{{$impuesto->id}}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}" @if($docxpagar->impuesto2_id == $impuesto->id) selected @endif>{{$impuesto->nombrecorto}}</option>
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
                                    <input type="text" id="glosa" name="glosa" class="form-control" value="{{$docxpagar->glosa}}">
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
                                    @include('billings.tabs.detalle_editar')
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="aplica-tab">
                                    @include('billings.tabs.datos_adicionales_editar')
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="aplica-tab">
                                    @include('billings.tabs.historial_editar')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label><input type="checkbox" value="" name="crear_kardex" id="crear_kardex" @if($docxpagar->noalmacen == 1) checked @endif disabled> Crear Kardex</label>
                                    <input type="text" class="form-control" id="ocompra" name="ocompra" value="{{$ocompra}}" data-id ="{{$referencia_salidaalmacen_id}}" data-ventana ="{{$ocompra_ventana}}" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="serierem">Guía de Remisión</label>
                                    <input type="text" class="form-control" id="serierem" name="serierem" value="{{$serierem}}" disabled>
                                </div>
                                <div class="col-md-2 col-xs-12" style="margin-top: 15px;">
                                    <input type="text" class="form-control" id="nrorem" name="nrorem" value="{{$numerorem}}" disabled>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="base">Op.Gravada</label>
                                    <input type="text" class="form-control" id="base" name="base" value="{{$docxpagar->base}}" disabled>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="inafecto">No Grav/Exon</label>
                                    <input type="text" class="form-control" id="inafecto" name="inafecto" value="{{$docxpagar->inafecto}}" disabled>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="op_gratuita">Op.Gratuita</label>
                                    <input type="text" class="form-control" id="op_gratuita" name="op_gratuita" value="" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="descuento">(-)Descuento</label>
                                    <input type="text" class="form-control" id="descuento" name="descuento" value="{{$docxpagar->descuento}}" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="isc_icbper">ISC/ICBPER</label>
                                    <input type="text" class="form-control" id="isc_icbper" name="isc_icbper" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="impuesto">I.G.V</label>
                                    <input type="text" class="form-control" id="impuesto" name="impuesto" value="{{$docxpagar->impuesto}}" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="impuesto2">Percepción</label>
                                    <input type="text" class="form-control" id="impuesto2" name="impuesto2" value="{{$docxpagar->impuesto2}}" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <label for="total">Total</label>
                                    <input type="text" class="form-control" id="total" name="total" value="{{$docxpagar->total}}" readonly>
                                </div>
                            </div>
                            <input type="hidden" value="" id="lote" name="lote"> {{--Provisional hasta que se trabaje con lotes--}}
                        </div>
                    </div>
                </div>
            </form>
            @include('billings.modals.documentos_referencia')
            @include('billings.modals.detalles')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/billing.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
        {{-- 
        $("#referencia_op").change(function(){
            let id = $(this).val();
            let url = '{{ route('edit.customerquote', ["id" => ":id"]) }}';
            if(id){window.open(url.replace(':id', id), "_blank");}            
        })
        --}}
        function producto(id) {
            let url = '{{ route('edit.products', ["id" => ":id"]) }}';
            window.open(url.replace(':id', id), "_blank");
        }
        $("#ocompra").dblclick(function(){       

            let id = $(this).data('id');      
            let ventana = $(this).data('ventana');

            let url = '';
            
            switch(ventana.toUpperCase()){

                case "MOV_SALIDAALMACEN":
                    url = '{{ route('edit.exittowarehouse', ["id" => ":id"]) }}';
                break;

                case "MOV_INGRESOALMACEN":
                    url = '{{ route('edit.incometowarehouse', ["id" => ":id"]) }}';
                break;

                case "MOV_GUIAREMISION":
                    url = ''; // AUN NO EXISTE
                break;

            }            

            if(id){if(url !== ''){window.open(url.replace(':id', id), "_blank");}}
            
        });
        $("#documento_credito_debito").dblclick(function(){
            let id = $(this).data('id');            
            let url = '{{ route('edit.billing', ["id" => ":id"]) }}';
            if(id){window.open(url.replace(':id', id), "_blank");}
        });
        function tmpguias(id, ventana){

            let url = '';
            
            switch(ventana.toUpperCase()){

                case "MOV_SALIDAALMACEN":
                    url = '{{ route('edit.exittowarehouse', ["id" => ":id"]) }}';
                break;

                case "MOV_INGRESOALMACEN":
                    url = '{{ route('edit.incometowarehouse', ["id" => ":id"]) }}';
                break;

                case "MOV_GUIAREMISION":
                    url = ''; // AUN NO EXISTE
                break;

            }    

            if(url !== ''){window.open(url.replace(':id', id), "_blank");}

        } 
        function historial_aplicaciones(origen_id, ventanaorigen){

            let url = '';

            switch(ventanaorigen.toUpperCase()){

                case "MOV_FACTURAVENTA":
                    url = '{{ route('edit.billing', ["id" => ":id"]) }}';
                break;

                case "MOV_BANCO":
                    url = '{{ route('edit.bankmovement', ["id" => ":id"]) }}';
                break;

                case "MOV_CAJA":
                    url = '{{ route('edit.cashmovement', ["id" => ":id"]) }}';
                break;

            }

            if(url !== ''){window.open(url.replace(':id', origen_id), "_blank");}

        }
    </script>    
@endsection
