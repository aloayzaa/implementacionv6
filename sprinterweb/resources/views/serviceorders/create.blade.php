@extends('templates.home')

@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.serviceorders') }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="panel panel-info">
                                {{--<div class="panel-heading">Registros</div>--}}
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="period">Periodo:</label>
                                            <input class="form-control" type="text" name="period" id="period" value="{{Session::get('period')}}" readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="buy">Unidad Negocio:</label>
                                            <select class="form-control select2" name="cbo_unegocio" id="cbo_unegocio">
                                                @foreach($unegocio as $un)
                                                    <option value="{{$un->id}}">{{$un->codigo}} | {{$un->descripcion}}</option>
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
                                            <input class="form-control" type="text" name="numberseries" id="numberseries" placeholder="00000" required readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="date">Fecha:</label>
                                            <input class="form-control tipocambio" type="date" name="processdate" id="txt_fecha" min="{{ $period->inicio }}" max="{{ $period->final }}" value="{{ $fecha }}" required>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="changerate">T.Cambio:</label>
                                            <input class="form-control typechange" type="text" name="changerate" id="changerate" placeholder="0.000" required readonly>
                                        </div>
                                        <div class="col-md-3 col-xs-12 label-input">
                                            <label for="buy">Punto de Emisión:</label>
                                            <select class="form-control select2" name="pointsale" id="pointsale">
                                                <option value="">-Seleccione-</option>
                                                @foreach($puntoventa as $pv)
                                                    <option value="{{$pv->id}}" data-direccion="{{ $pv->direccion }}">{{$pv->codigo}} | {{$pv->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="txt_subtotal" id="txt_subtotal" value="0">
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Datos de la orden</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12 label-input">
                                            <label for="customer">Codigo y Razón Social:</label>
                                            <select class="form-control select2" name="tercero_id" id="tercero_id">
                                                <option value="">-Seleccionar-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="customerruc">RUC:</label>
                                            <input class="form-control" name="txt_customerruc" id="txt_customerruc" readonly>
                                        </div>
                                        <div class="col-md-5 col-xs-12 label-input">
                                            <label for="">Condición de Pago:</label>
                                            <select class="form-control select2" name="condpayment" id="condpayment">
                                                <option value="">-Seleccione-</option>
                                                @foreach($condicionpagos as $condicionpago)
                                                    <option value="{{$condicionpago->id}}"> {{$condicionpago->codigo}} | {{$condicionpago->descripcion}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5 col-xs-12 label-input">
                                            <label for="">Moneda:</label>
                                            <select class="form-control select2" name="currency" id="currency">
                                                <option value="">-Seleccione-</option>
                                                @foreach($monedas as $moneda)
                                                    <option value="{{$moneda->id}}"> {{$moneda->descripcion}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="">TC:</label>
                                            <input class="form-control" type="text" name="txt_tc" id="txt_tc" readonly>
                                        </div>
                                        <div class="col-md-12 col-xs-12 label-input">
                                            <label for="comment">Observaciones:</label>
                                            <input class="form-control" type="text" name="comment" id="comment">
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
                                                    <option value="{{$impuesto->id}}" @if($impuesto->codigo == "03") selected @endif>{{$impuesto->nombrecorto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 col-xs-12 label-input">
                                            <label for="ruc">Renta:</label>
                                            <select class="form-control select2" name="rent" id="rent">
                                                <option value="">-Seleccione-</option>
                                                @foreach($impuestos3 as $impuesto3)
                                                    <option value="{{$impuesto3->id}}">{{$impuesto3->nombrecorto}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="afecto" id="afecto">
                            <div class="col-md-12 col-xs-12">
                                <input type="checkbox" id="chktax" name="chktax"> Los precios incluyen impuestos
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
                                            <input class="form-control" type="text" name="txt_base" id="txt_base" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Inafecto:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_inactive" id="txt_inactive" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">IGV:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_igvtotal" id="txt_igvtotal" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Renta:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_rentimport" id="txt_rentimport" readonly>
                                        </div>
                                        <div class="col-md-4 col-xs-12 label-input">
                                            <label for="ruc">Total:</label>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <input class="form-control" type="text" name="txt_total" id="txt_total" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            $("#afecto").val(1);
        })
        $(function () {
            ServiceOrdersDetailDocuments.init('{{ route('list_detalle_documento.serviceorders') }}');
        });
    </script>
@endsection
