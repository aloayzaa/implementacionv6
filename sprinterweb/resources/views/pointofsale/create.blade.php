@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.pointofsale') }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="almacen_id" id="almacen_id" value="@if(isset($puntoventa)){{$puntoventa->almacen_id}}@endif">
                <input type="hidden" name="ncentrocosto" id="ncentrocosto" value="@if(isset($puntoventa)){{$puntoventa->centrocosto_id}}@endif">
                <input type="hidden" name="nctactebanco" id="nctactebanco" value="@if(isset($puntoventa)){{$puntoventa->ctactebanco_id}}@endif">
                <input type="hidden" name="sucursal_id" id="sucursal_id" value="@if(isset($puntoventa)){{$puntoventa->sucursal_id}}@endif">
                <input type="hidden" name="proyecto_id" id="proyecto_id" value="@if(isset($puntoventa)){{$puntoventa->proyecto_id}}@endif">
                <input type="hidden" name="banco_id" id="banco_id" value="@if(isset($puntoventa)){{$puntoventa->banco_id}}@endif">
                <input type="hidden" name="tipotransaccion_id" id="tipotransaccion_id" value="@if(isset($puntoventa)){{$puntoventa->tipotransaccion_id}}@endif">
                <input type="hidden" name="movimientotipo_id" id="movimientotipo_id" value="@if(isset($puntoventa)){{$puntoventa->movimientotipo_id}}@endif">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="period">Periodo:</label>
                                    <input class="form-control" type="text" name="period" id="period" value="{{Session::get('period')}}" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="numberOfSeries">Número:</label>
                                    <input class="form-control" type="text" name="numberseries" id="numberseries"
                                           value="00000" readonly>
                                </div>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="buy">Punto de Venta:</label>
                                    <select class="form-control ocultar" name="txt_descripcionpv" id="txt_descripcionpv" readonly>
                                    </select>
                                </div>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="buy">Tipo de Venta:</label>
                                    <select class="form-control ocultar" name="txt_descripciontv" id="txt_descripciontv" readonly>
                                        <option value="{{ $tipoventa->id }}">{{ $tipoventa->descripcion }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="buy">Moneda:</label>
                                    <select class="form-control ocultar" name="txt_descripcionmon" id="txt_descripcionmon"  readonly>
                                        <option value="{{ $monedas->id }}">{{ $monedas->descripcion }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-xs-12 label-input">
                                    <br>
                                    <button type="button" id="cobrar" class="btn btn-dark btn-sm">Cobrar</button>
                                    <button type="button" id="ticket" class="btn btn-dark btn-sm">Ticket</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="detallepva active">
                                    <a href="#tab_content1" id="detalle" role="tab" class="detallepv" data-toggle="tab" aria-expanded="true">Detalle</a>
                                </li>
                                <li role="presentation" class="cobrarpva ocultar">
                                    <a href="#tab_content2" role="tab" id="cobranza" class="cobrarpv" data-toggle="tab" aria-expanded="false">Cobranza</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in detallepva" id="tab_content1"
                                     aria-labelledby="detalle">
                                    @include('pointofsale.tabs.detalle')
                                </div>
                                <div role="tabpanel" class="tab-pane fade cobrarpva in" id="tab_content2" aria-labelledby="cobranza">
                                    @include('pointofsale.tabs.cobranza')
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="">Condición de Pago</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="txt_descripcioncp" id="txt_descripcioncp" class="form-control">
                                                        @foreach($condicionpagos as $condicionpagos)
                                                            <option value="{{ $condicionpagos->id }}">{{ $condicionpagos->descripcion }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="">Vencimiento</label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="date" name="txt_vence" id="txt_vence" class="form-control" value="{{ $fecha_pv }}" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="col-md-2">
                                                    <label for="">Observaciones</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" name="edtobservacion" id="edtobservacion" value="{{ $observaciones }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row" style='display:none'>
                                                <div class="col-md-12 col-xs-12 label-input">
                                                    <label for="buy">Tipo Afectac IGV</label>
                                                    <select class="form-control ocultar" name="cnttipoafectaigv" id="cnttipoafectaigv" readonly>
                                                        <option value="@if(isset($cnttipoafectaigv->id)){{ $cnttipoafectaigv->id }}@endif">@if(isset($cnttipoafectaigv->id)){{ $cnttipoafectaigv->descripcion }}@endif</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" style='display:none'>
                                                <div class="col-md-12 col-xs-12 label-input ocultar">
                                                    <label for="buy">Uidad Negocio</label>
                                                    <select class="form-control select2" name="cbo_unegocio" id="cbo_unegocio" readonly>
                                                        @foreach($unegocio as $un)
                                                            <option value="{{$un->id}}">{{$un->codigo}} | {{$un->descripcion}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="" style="float: right">Op.Gravada</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_base" id="txt_base" value="0.00" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="" style="float: right">No Grav/Exon</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_inafecto" id="txt_inafecto" value="0.00" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="" style="float: right">Op.Gratuita</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_gratuito" id="txt_gratuito" value="0.00" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="" style="float: right">(-)Descuento</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_descuento" id="txt_descuento" value="0.00" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="" style="float: right"> I.G.V.</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select name="cbo_impuesto" id="cbo_impuesto" class="form-control">
                                                                @foreach($impuesto as $impuesto)
                                                                    <option value="{{ $impuesto->id }}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}" @if($impuesto_select != null ) @if($impuesto->id == $impuesto_select) selected @endif @else @if($impuesto->id == 4) selected @endif @endif>{{ $impuesto->nombrecorto }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_impuesto" id="txt_impuesto" value="0.00" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="" style="float: right">Percepción</label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select name="cbo_impuesto2" id="cbo_impuesto2" class="form-control">
                                                                <option value="">-Seleccionar-</option>
                                                                @foreach($impuesto2 as $impuesto2)
                                                                    <option value="{{ $impuesto2->id }}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}">{{ $impuesto2->nombrecorto }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_impuesto2" id="txt_impuesto2" class="form-control" value="0.00" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="" style="float: right">ISC/ICBPER</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_isc" id="txt_isc" class="form-control" readonly value="0.00">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label for="" style="float: right"><b>Importe Total</b></label>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="txt_total" id="txt_total" value="0.00" class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @include('pointofsale.modals.add_item')
            @include('pointofsale.modals.forma_pago')
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/sales.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/pointofsale.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            PointOfSaleDetail.init('{{ route('list_detalle_venta.pointofsale') }}');
            PointOfSaleCobranza.init('{{ route('list_cobranza_venta.pointofsale') }}');
        });
    </script>
@endsection
