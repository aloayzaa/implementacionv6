@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
@endsection
@section('content')

    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="ruta" name="ruta" value="{{ route('update.provisionstopay') }}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">
                        <form id="frm_generales" autocomplete="off">
                            <div class="identificador ocultar">
                                <input type="hidden" id="id" name="id" value="{{ $docxpagar->id }}">

                                <div class="panel panel-info">
                                    <div class="panel-heading">Registro</div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="form-group col-md-2" style="width: 150px">
                                                <label for="txt_periodo">Periodo: </label>
                                                <input type="text" class="form-control"
                                                       value="{{ $period->descripcion }}" readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label for="txt_numero">Unid. Negocio: </label>
                                                <select class="form-control select2" name="txt_unegocio"
                                                        id="txt_unegocio">
                                                    @foreach($unidades as $unidad)
                                                        <option value="{{$unidad->id}}"
                                                                @if( $unidad->id == $docxpagar->id) selected @endif>
                                                            {{$unidad->codigo}} | {{$unidad->descripcion}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2" style="width: 100px">
                                                <label for="txt_numero">Número: </label>
                                                <input type="text" class="form-control text-center" name="txt_numero"
                                                       id="txt_numero" placeholder="0000"
                                                       value="{{ $docxpagar->numero }}" readonly>
                                            </div>
                                            <div class="form-group col-md-2" style="width: 165px">
                                                <label for="txt_fecha">Fecha: </label>
                                                <input type="date" class="form-control" name="txt_fecha" id="txt_fecha"
                                                       min="{{ $period->inicio }}" max="{{ $period->final }}"
                                                       value="{{ $docxpagar->fechaproceso }}"> {{--fechadoc para que no interfiera con la fecha de abajo--}}
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_ordencompra">Orden Compra: </label>
                                                <div class="input-group input-group-sm" style="z-index: 0">
                                                    <input type="text" class="form-control" name="txt_ordencompra"
                                                           ondblclick="add_orden({{ 0 }})" id="txt_ordencompra"
                                                           value="{{ $codigo_orden }}" readonly>
                                                    @if($docxpagar->ordencompra_id)
                                                        <span class="input-group-btn" id="span-orden">
                                                     <button class="btn btn-dark" type="button">Ir</button>
                                                </span>
                                                    @endif
                                                </div>
                                                <input type="hidden" class="form-control" name="txt_ordencompra_id"
                                                       id="txt_ordencompra_id" value="{{ $ordencompra_id }}" readonly>
                                            </div>
                                            <div class="form-group col-md-1" style="width: 190px">
                                                <label for="txt_sucursal">Sucursal: </label>
                                                <select class="form-control select2" name="txt_sucursal"
                                                        id="txt_sucursal">
                                                    @foreach($sucursales as $sucursal)
                                                        <option value="{{ $sucursal->id }}"
                                                                @if($sucursal->id == $docxpagar->sucursal_id) selected @endif>
                                                            {{ $sucursal->codigo }}
                                                            | {{ $sucursal->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_tipocompra">Tipo Compra: </label>
                                                <select class="form-control select2" name="txt_tipocompra"
                                                        id="txt_tipocompra">
                                                    @foreach($tipocompras as $tipocompra)
                                                        <option value="{{ $tipocompra->id }}"
                                                                @if($tipocompra->id == $docxpagar->tipoCompra['id']) selected @endif>
                                                            {{ $tipocompra->codigo }}
                                                            | {{ $tipocompra->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Documento</div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="txt_tercero">Razón Social: </label>
                                                    <select class="form-control select2" name="txt_tercero"
                                                            id="txt_tercero">
                                                        <option value="{{$docxpagar->tercero_id}}"
                                                                selected>{{$docxpagar->tercero->codigo}}
                                                            | {{$docxpagar->tercero->descripcion}}</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_tipodoc">Tipo Doc: </label>
                                                    <select class="form-control select2" name="txt_tipodoc"
                                                            id="txt_tipodoc">
                                                        @foreach($documentoscompra as $documentocompra)
                                                            <option value="{{ $documentocompra->id }}"
                                                                    @if($documentocompra->id == $docxpagar->documento_id) selected @endif>
                                                                {{ $documentocompra->codigo }}
                                                                | {{ $documentocompra->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label for="txt_seriedoc">Serie: </label>
                                                    <input type="text" class="form-control" name="txt_seriedoc"
                                                           id="txt_seriedoc" placeholder="0000"
                                                           value="{{ $docxpagar->seriedoc }}">
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label for="txt_numerodoc">Número: </label>
                                                    <input type="text" class="form-control" name="txt_numerodoc"
                                                           id="txt_numerodoc" placeholder="0000"
                                                           value="{{ $docxpagar->numerodoc }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_fechadoc">Fecha: </label>
                                                    <input type="date" class="form-control tipocambio"
                                                           name="txt_fechadoc"
                                                           id="txt_fechadoc" value="{{ $docxpagar->fechadoc }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_tcambio">T. Cambio: </label>
                                                    <input type="text" class="form-control typechange"
                                                           name="txt_tcambio"
                                                           id="txt_tcambio" value="{{ $docxpagar->tcambio }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="txt_condicionpago">Condición Pago: </label>
                                                <select class="form-control select2" name="txt_condicionpago"
                                                        id="txt_condicionpago">
                                                    @foreach($condicionpagos as $condicionpago)
                                                        <option value="{{$condicionpago->id}}"
                                                                @if($condicionpago->id == $docxpagar->condicionPago['id']) selected @endif>
                                                            {{ $condicionpago->codigo }}
                                                            | {{ $condicionpago->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_vencimiento">Vencimiento: </label>
                                                <input type="date" class="form-control" name="txt_vencimiento"
                                                       id="txt_vencimiento" value="{{ $docxpagar->vencimiento }}"
                                                       readonly>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_moneda">Moneda: </label>
                                                <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                                    @foreach($monedas as $moneda)
                                                        <option value="{{$moneda->id}}"
                                                                @if( $moneda->id == $docxpagar->moneda_id) selected @endif>
                                                            {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_tc">T. C: </label>
                                                <input type="text" class="form-control" name="txt_tc" id="txt_tc"
                                                       value="{{ $docxpagar->tc }}" readonly>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label for="txt_glosa">Glosa: </label>
                                                <input type="text" class="form-control" name="txt_glosa" id="txt_glosa"
                                                       value="{{ $docxpagar->glosa }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Tributos</div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="txt_clasificacion">Clasificación de Bien o
                                                    Servicio: </label>
                                                <select class="form-control select2" name="txt_clasificacion"
                                                        id="txt_clasificacion">
                                                    @foreach($servicios as $servicio)
                                                        <option value="{{ $servicio->id }}"
                                                                @if($servicio->id == $docxpagar->tipobienservicio_id) selected @endif>
                                                            {{ $servicio->descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_adquisicion">Tipo Aquisición: </label>
                                                <select class="form-control select2" name="txt_adquisicion"
                                                        id="txt_adquisicion">
                                                    <option value="1" @if($docxpagar->tipoadq==1) selected @endif>1.
                                                        Destino
                                                        Gravado
                                                    </option>
                                                    <option value="2" @if($docxpagar->tipoadq==2) selected @endif>2.
                                                        Destino
                                                        Mixto
                                                    </option>
                                                    <option value="3" @if($docxpagar->tipoadq==3) selected @endif>3.
                                                        Destino No
                                                        Gravado
                                                    </option>
                                                    <option value="4" @if($docxpagar->tipoadq==4) selected @endif>4. No
                                                        Gravadas
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_igv_id">IGV: </label>
                                                <select class="form-control select2" name="txt_igv_id" id="txt_igv_id">
                                                    <option value="" selected>-- Seleccione una opción --</option>
                                                    @foreach($impuestos as $impuesto)
                                                        <option value="{{ $impuesto->id }}"
                                                                @if($docxpagar->impuesto_id == $impuesto->id) selected @endif>
                                                            {{ $impuesto->nombrecorto }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_percepcion_id">Percepción: </label>
                                                <select class="form-control select2" name="txt_percepcion_id"
                                                        id="txt_percepcion_id">
                                                    <option value="" selected>-- Seleccione una opción --</option>
                                                    @foreach($impuestos2 as $impuesto2)
                                                        <option value="{{ $impuesto2->id }}"
                                                                @if($docxpagar->impuesto2_id == $impuesto2->id) selected @endif>
                                                            {{ $impuesto2->nombrecorto }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_renta_id">Renta: </label>
                                                <select class="form-control select2" name="txt_renta_id"
                                                        id="txt_renta_id">
                                                    <option value="" selected>-- Seleccione una opción --</option>
                                                    @foreach($impuestos3 as $impuesto3)
                                                        <option value="{{ $impuesto3->id }}"
                                                                @if($docxpagar->impuesto3_id == $impuesto3->id) selected @endif>
                                                            {{ $impuesto3->nombrecorto }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading">Importes</div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="form-group col-md-1">
                                                <label for="txt_base">Base Afecta: </label>
                                                <input type="text" class="form-control numero twodecimal"
                                                       name="txt_base"
                                                       id="txt_base" value="{{ $docxpagar->base }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_inafecto">Inafecto: </label>
                                                <input type="text" class="form-control numero twodecimal"
                                                       name="txt_inafecto"
                                                       id="txt_inafecto" value="{{ $docxpagar->inafecto }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_igv">IGV: </label>
                                                <input type="text" class="form-control desbloqueable numero twodecimal"
                                                       name="txt_igv" id="txt_igv" value="{{ $docxpagar->impuesto }}"
                                                       readonly>
                                            </div>
                                            <div class="form-group col-md-1" style="margin-top:-20px">
                                                <label for="check_impuesto">Incluye: </label>
                                                @if($docxpagar->conpercepcion == 1)
                                                    <input type="checkbox" id="check_impuesto" checked
                                                           name="check_impuesto"/>
                                                @else
                                                    <input type="checkbox" id="check_impuesto" name="check_impuesto"/>
                                                @endif
                                                <label for="txt_percepcion">Percepción: </label>
                                                <input type="text" class="form-control desbloqueable numero twodecimal"
                                                       name="txt_percepcion" id="txt_percepcion"
                                                       value="{{ $docxpagar->impuesto2 }}" readonly>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_renta">Renta: </label>
                                                <input type="text" class="form-control desbloqueable numero twodecimal"
                                                       name="txt_renta" id="txt_renta"
                                                       value="{{ $docxpagar->impuesto3 }}"
                                                       readonly>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label for="txt_total">Total: </label>
                                                <input type="text" class="form-control" name="txt_total" id="txt_total"
                                                       value="{{ $docxpagar->total }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab"
                                           aria-expanded="true">Detalle del Documento</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_content2" role="tab" id="aplica-tab" data-toggle="tab"
                                           aria-expanded="false">Datos Adicionales</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_content3" role="tab" id="aplica-tab" data-toggle="tab"
                                           aria-expanded="false">Historial</a>
                                    </li>
                                </ul>
                            </div>

                            <div id="myTabContent" class="tab-content identificador ocultar">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                     aria-labelledby="home-tab">
                                    @include('provisionstopay.tabs.detalleDocumento')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                     aria-labelledby="aplica-tab">
                                    @include('provisionstopay.tabs.datosAdicionales')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_content3"
                                     aria-labelledby="aplica-tab">
                                    @include('provisionstopay.tabs.historial')
                                </div>
                            </div>
                        </form>
                        @include ('provisionstopay.modals.edit_cuenta')
                        @include ('provisionstopay.modals.referencia')
                        @include ('provisionstopay.modals.ordencompra')
                    </div>
                </div>
            </div>
            <input type="hidden" id="asiento" value="{{ $asiento_id }}">
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/provisionstopay.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function () {
            if (performance.navigation.type == 0) {
                if (document.referrer.includes('create')) {
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
        backButtonRefresh();

        function asiento(asiento_id) {
            return asiento_id != 0 ? window.location = '{{ route("show.dailyseat", $asiento_id) }}' : alert('No existe asiento')
        }

        function orden(orden_id) {
            return orden_id != '' ? window.location = '{{ route("edit.purchaseorder", ["id" => $ordencompra_id]) }}' : alert('No existe referencia')
        }

        function cuenta(id) {
            if (id == null) {
                alert('No existe provision')
            } else {
                var url = '{{ route('edit.accountingplans', ["id" => ":id"]) }}';
                window.open(url.replace(':id', id), "_blank");
            }
        }
    </script>

@endsection
