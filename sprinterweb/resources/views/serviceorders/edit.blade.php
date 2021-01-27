@extends('templates.home')

@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.serviceorders', $servicio->id) }}">
                <input type="hidden" id="ruta_estado" name="ruta_estado" value="{{ route('estado.serviceorders') }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="id" name="id" value="{{ $servicio->id }}">
                <input type="hidden" id="estado" name="estado" value="{{ $servicio->estado }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="panel panel-info">
                                {{--<div class="panel-heading">Registros</div>--}}
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="period">Periodo:</label>
                                            <input type="hidden" name="period" id="period" value="{{$period->id}}" readonly>
                                            <input class="form-control" type="text" name="" id="" value="{{$period->descripcion}}" readonly>
                                        </div>
                                        {{--                                        <button type="button" id="enviardatos">INSERTAR</button>--}}
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="buy">Unidad Negocio:</label>
                                            <select class="form-control select2" name="cbo_unegocio" id="cbo_unegocio">
                                                @foreach($unegocio as $un)
                                                    <option value="{{$un->id}}" @if($un->id == $servicio->unegocio_id) selected @endif>{{$un->codigo}} | {{$un->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="series">Serie:</label>
                                            <input class="form-control" type="text" name="txt_series" id="txt_series" value="{{ $serie }}" required>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="numberOfSeries">Número:</label>
                                            <input type="hidden" name="number_series" value="{{ $numero }}">
                                            <input class="form-control" type="text" name="numberseries" id="numberseries" value="{{ $numero }}" required readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="date">Fecha:</label>
                                            <input class="form-control tipocambio" type="date" name="processdate" id="txt_fecha" min="{{ $period->inicio }}" max="{{ $period->final }}" value="{{ $fecha }}" required>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="changerate">T.Cambio:</label>
                                            <input class="form-control typechange" type="text" name="changerate" id="changerate" value="{{ $servicio->tcambio }}" required readonly>
                                        </div>
                                        <div class="col-md-3 col-xs-12 label-input">
                                            <label for="buy">Punto de Emisión:</label>
                                            <select class="form-control select2" name="pointsale" id="pointsale">
                                                <option value="">-Seleccione-</option>
                                                @foreach($puntoventa as $pv)
                                                    <option value="{{$pv->id}}" data-direccion="{{ $pv->direccion }}" @if($pv->id == $servicio->puntoventa_id) selected @endif>{{$pv->codigo}} | {{$pv->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Datos de la orden</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12 label-input">
                                            <label for="customer">Codigo y Razón Social:</label>
                                            <select class="form-control select2" name="tercero_id" id="tercero_id">
                                                <option value="">-Seleccionar-</option>
                                                <option value="{{ $terceros->id }}" selected>{{$terceros->codigo}} | {{$terceros->descripcion}}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="customerruc">RUC:</label>
                                            <input class="form-control" name="txt_customerruc" id="txt_customerruc" value="{{ $terceros->ruc }}" readonly>
                                        </div>
                                        <div class="col-md-5 col-xs-12 label-input">
                                            <label for="">Condición de Pago:</label>
                                            <select class="form-control select2" name="condpayment" id="condpayment">
                                                <option value="">-Seleccione-</option>
                                                @foreach($condicionpagos as $condicionpago)
                                                    <option value="{{$condicionpago->id}}" @if($condicionpago->id == $servicio->condicionpago_id) selected @endif> {{$condicionpago->codigo}} | {{$condicionpago->descripcion}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5 col-xs-12 label-input">
                                            <label for="">Moneda:</label>
                                            <select class="form-control select2" name="currency" id="currency">
                                                <option value="">-Seleccione-</option>
                                                @foreach($monedas as $moneda)
                                                    <option value="{{$moneda->id}}" @if($moneda->id == $servicio->moneda_id) selected @endif> {{$moneda->descripcion}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="">TC:</label>
                                            <input class="form-control" type="text" name="txt_tc" id="txt_tc" value="0.000" readonly>
                                        </div>
                                        <div class="col-md-12 col-xs-12 label-input">
                                            <label for="comment">Observaciones:</label>
                                            <input class="form-control" type="text" name="comment" id="comment" value="{{ $servicio->glosa }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Tributos</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 label-input">
                                            <label for="ruc">IGV:</label>
                                            <select class="form-control select2" name="igv" id="igv">
                                                <option value="">-Seleccione-</option>
                                                @foreach($impuestos as $impuesto)
                                                    <option value="{{$impuesto->id}}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}" data-codigo="{{$impuesto->codigo}}" @if($impuesto->id == $servicio->impuesto_id) selected @endif>{{$impuesto->nombrecorto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-xs-12 label-input">
                                            <label for="ruc">Renta:</label>
                                            <select class="form-control select2" name="rent" id="rent">
                                                <option value="">-Seleccione-</option>
                                                @foreach($impuestos3 as $impuesto3)
                                                    <option value="{{$impuesto3->id}}" @if($impuesto3->id == $servicio->impuesto3_id) selected @endif>{{$impuesto3->nombrecorto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="afecto" id="afecto" value="@if(isset($detalle)) {{$detalle->esafecto}}@else 1 @endif">
                            <div class="col-md-12 col-xs-12">
                                <input type="checkbox" id="chktax" name="chktax" @if($servicio->incluyeimpto ==1) checked @endif> Los precios incluyen impuestos
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Importes</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Afecto:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_base" id="txt_base" value="{{ $servicio->base }}" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Inafecto:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_inactive" id="txt_inactive" value="{{ $servicio->inafecto }}" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">IGV:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_igvtotal" id="txt_igvtotal" value="{{ $servicio->impuesto }}" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Renta:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_rentimport" id="txt_rentimport" value="{{ $servicio->impuesto3 }}" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Total:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_total" id="txt_total" value="{{ $servicio->total }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="txt_subtotal" id="txt_subtotal" value="0">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#tab_content1" id="generales" role="tab" data-toggle="tab" aria-expanded="true">Detalle del Documento</a>
                                        </li>
                                        <li role="presentation" class="">
                                            <a href="#tab_content2" role="tab" id="cliente_provedor" data-toggle="tab" aria-expanded="false">Datos Adicionales</a>
                                        </li>
                                        <li role="presentation" class="">
                                            <a href="#tab_content3" role="tab" id="otros_datos" data-toggle="tab" aria-expanded="false">Referencias</a>
                                        </li>
                                    </ul>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="generales">
                                            @include('serviceorders.tabs.detalle_documento')
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="cliente_provedor">
                                            @include('serviceorders.tabs.datos_adicionales')
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="otros_datos">
                                            @include('serviceorders.tabs.referencias')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include ('serviceorders.modals.add_item')
            @include ('serviceorders.modals.edit_item')
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/serviceorders.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(document).ready(function () {
            var ordencompra_id = $("#id").val();
            var ruta_provisiones = $("#tabla_provisiones").val();
            ListProvisions.init( ruta_provisiones + '?ordencompra_id=' + ordencompra_id);
            sumar_footer(ordencompra_id);
        })
        $(function () {
            ServiceOrdersDetailDocuments.init('{{ route('list_detalle_documento.serviceorders') }}');
            if(performance.navigation.type == 0){
                if(document.referrer.includes('serviceorders/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('serviceorders/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>
@endsection
