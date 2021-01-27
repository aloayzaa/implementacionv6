@extends('templates.home')

@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.customerquote') }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Registros</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="period">Periodo:</label>
                                            <input class="form-control" type="text" name="periodc" id="periodc"
                                                   value="{{Session::get('period')}}" readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="buy">Unidad Negocio:</label>
                                            <select class="form-control select2" name="cbo_unegocio_coti" id="cbo_unegocio_coti">
                                                @foreach($unegocioct as $unegocic)
                                                    <option value="{{$unegocic->id}}">{{$unegocic->codigo}}
                                                        | {{$unegocic->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="series">Serie:</label>
                                            <input class="form-control" type="text" name="series_cotiza" id="series_cotiza"
                                                   class="form-control" value="00001">
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="numberOfSeries">Número:</label>
                                            <input type="hidden" name="number_series" value="{{ $numero }}">
                                            <input class="form-control" type="text" name="numberseries"
                                                   id="numberseries" placeholder="00000" required readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="date">Fecha:</label>
                                            <input class="form-control tipocambio" type="date" name="processdate"
                                                   id="txt_fecha" min="{{ $period->inicio }}" max="{{ $period->final }}"
                                                   value="{{ $fecha }}" required>
                                        </div>
                                        <div class="col-md-1 col-xs-12 label-input">
                                            <label for="changerate">T.Cambio:</label>
                                            <input class="form-control typechange" type="text" name="changerate"
                                                   id="changerate" placeholder="0.000" required readonly>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="buy">Punto de Emisión:</label>
                                            <select class="form-control select2" name="pointsale" id="pointsale">
                                                <option value="">-Seleccione-</option>
                                                @foreach($puntoventa as $pv)
                                                    <option value="{{$pv->id}}"
                                                            data-direccion="{{ $pv->direccion }}">{{$pv->codigo}}
                                                        | {{$pv->descripcion}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-sm-3 col-xs-12">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4 col-xs-12 label-input">
                                                        <label for="ruc">O.Trabajo:</label>
                                                    </div>
                                                    <div class="col-md-6 col-xs-12">
                                                        <input class="form-control" type="text" name="txt_base" id="txt_base" readonly>
                                                    </div>
                                                    <button type="button" id="otrabajo" class="btn btn-dark btn-sm">OT</button>

                                                    <div class="col-md-4 col-xs-12 label-input">
                                                        <label for="ruc">Pedi.Venta:</label>
                                                    </div>
                                                    <div class="col-md-6 col-xs-12">
                                                        <input class="form-control" type="text" name="txt_inactive" id="txt_inactive"
                                                               readonly>
                                                    </div>
                                                    <button type="button" id="pvalmacen" class="btn btn-dark btn-sm">PV</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">Cliente</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-12 label-input">
                                            <label for="customer">Codigo y Razón Social:</label>
                                            <select class="form-control select2" name="tercero_id" id="tercero_id">
                                                <option value="">-Seleccionar-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="customerruc">RUC:</label>
                                            <input class="form-control" name="txt_customerruc" id="txt_customerruc"
                                                   readonly>
                                       </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="contacto">Contacto</label>
                                            <select class="form-control select2" id="contacto_cli" name="contacto_cli">
                                                <option value="">--Seleccione-</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-xs-12 label-input">
                                            <label for="email">Email:</label>
                                            <input class="form-control" name="txt_email" id="txt_email" readonly>

                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="email">Telefono:</label>
                                            <input class="form-control" name="txt_email" id="txt_email" readonly>
                                        </div>

                                </div>
                            </div>
                        </div>
                        </div>
                            <div class="col-md-12 col-xs-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">Condiciones</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-2 col-xs-12 label-input">
                                                <label for="">Condición de Pago:</label>
                                                <select class="form-control select2" name="condpayment" id="condpayment">
                                                    <option value="">-Seleccione-</option>
                                                    @foreach($condicionpagos as $condicionpago)
                                                        <option value="{{$condicionpago->id}}"> {{$condicionpago->codigo}}
                                                            | {{$condicionpago->descripcion}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 col-xs-12 label-input">
                                                <label for="doferta">Dias Oferta:</label>
                                                <input class="form-control" name="txt_doferta" id="txt_doferta" >

                                            </div>
                                            <div class="col-md-1 col-xs-12 label-input">
                                                <label for="dentrega">Días Entrega:</label>
                                                <input class="form-control" name="txt_entrega" id="txt_entrega" >
                                            </div>
                                            <div class="col-md-2 col-xs-12 label-input">
                                                <label for="date">Fecha entrega:</label>
                                                <input class="form-control tipocambio" type="date" name="dateentrega"
                                                       id="dateentrega" value="{{ $fecha }}" >
                                            </div>
                                            <div class="col-md-6 col-xs-12 label-input">
                                                <label for="contacto">Lugar de Entrega</label>
                                                <select class="form-control select2" id="lugar_entrega" name="lugar_entrega">
                                                    <option value="">--Seleccione-</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-xs-12 label-input">
                                                <label for="txt_vendedor">Vendedor: </label>
                                                <select class="form-control select2" name="txt_vendedor"
                                                        id="txt_vendedor">
                                                    <option value="" selected>-- Seleccione una opción --</option>
                                                    @foreach($vendedores as $vendedor)
                                                        <option value="{{ $vendedor->id }}">
                                                            {{ $vendedor->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1 col-xs-12 label-input">
                                                <label for="contacto">Precio</label>
                                                <select class="form-control " id="precio" name="precio">
                                                    <option value="0">--Seleccione-</option>
                                                    <option value="1">Precio A</option>
                                                    <option value="2">Precio B</option>
                                                    <option value="3">Precio C</option>
                                                    <option value="4">Precio D</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-xs-12 label-input">
                                                <label for="">Moneda:</label>
                                                <select class="form-control select2" name="currency" id="currency">
                                                    <option value="">-Seleccione-</option>
                                                    @foreach($monedas as $moneda)
                                                        <option value="{{$moneda->id}}"> {{$moneda->descripcion}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-7 col-xs-12 label-input">
                                                <label for="comment">Observaciones:</label>
                                                <input class="form-control" type="text" name="comment" id="comment">
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
                                        <a href="#tab_content1" id="generales" role="tab" data-toggle="tab"
                                           aria-expanded="true">Detalle del Documento</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_content2" role="tab" id="cotiza_especifica" data-toggle="tab"
                                           aria-expanded="false">Especificaciones</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_content3" role="tab" id="caracteristica_producto"
                                           data-toggle="tab" aria-expanded="false">Caracteristicas</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_content3" role="tab" id="otros_datos" data-toggle="tab"
                                           aria-expanded="false">Otros Datos</a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="generales">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-2 col-xs-6 label-input">
                                                    <select class="select2 ag-modal-select" id="add_centrocosto_id" name="centrocosto_id">
                                                        <option value="" selected>Seleccione un Centro de Costo</option>
                                                        @foreach($centroscosto as $centrocosto)
                                                            <option value="{{ $centrocosto->id }}">
                                                                {{ $centrocosto->codigo }} | {{ $centrocosto->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-xs-6 label-input">
                                                    <label for="customerruc">Valor Venta:</label>
                                                    <input class="form-control" name="txt_customerruc" id="txt_customerruc"
                                                           readonly>
                                                </div>

                                                <div class="col-md-2 col-xs-6">
                                                    <label for="igv">IGV:</label>
                                                    <select name="igv" id="igv" class="select2">
                                                        @foreach($impuestos as $impuesto)
                                                            <option value="{{$impuesto->id}}" data-tipocalculo="{{$impuesto->tipocalculo}}" data-valor="{{$impuesto->valor}}" @if($impuesto->codigo == '03') selected @endif>{{$impuesto->nombrecorto}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 col-sm-6 col-xs-6">
                                                    <label for="txt_impuesto"></label>
                                                    <input type="text" id="txt_impuesto" name="txt_impuesto" class="form-control" readonly="true">
                                                </div>
                                                <div class="col-md-2 col-sm-2 col-xs-2">
                                                    <label for="txt_total">Total:</label>
                                                    <input type="text" name="txt_total" id="txt_total"
                                                           class="form-control" readonly="true">
                                                </div>
                                                <div class="col-md-2 col-xs-12">
                                                    <label for="descuento">Descuento</label>
                                                    <input type="number" class="form-control" id="descuento" name="descuento" readonly>
                                                </div>
                                                <div class="col-md-2 col-xs-12">
                                                    <label for="descuento">Descuento Venta</label>
                                                    <input type="number" class="form-control" id="descuento" name="descuento" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        @include('customerquote.tabs.detalle_documento')
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                         aria-labelledby="cotiza_especifica">
                                        @include('customerquote.tabs.especificaciones')
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content3"
                                         aria-labelledby="caracteristica_producto">
                                        @include('customerquote.tabs.caracteristicas')
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content4"
                                         aria-labelledby="otros_datos">
                                        @include('customerquote.tabs.otros_datos')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </form>
        @include ('customerquote.modals.add_item')
        @include ('customerquote.modals.edit_item')
    </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/customerquote.js') }}"></script>
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
