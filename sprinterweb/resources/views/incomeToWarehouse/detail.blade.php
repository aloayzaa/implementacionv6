@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <div class="col-xs-12">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.incometowarehouse') }}">
                <input type="hidden" name="proceso" id="proceso"
                       @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                <input type="hidden" name="route" id="route"
                       @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">


                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">

                            <form id="frm_generales" autocomplete="off">
                                <div class="identificador ocultar">

                                    <div class="panel panel-info">
                                        <div class="panel-heading">Registro</div>
                                        <div class="panel-body">

                                            <div class="row">
                                                <div class="form-row">
                                                    <input type="hidden" id="id" name="id" value="{{ $ingreso->id }}">
                                                    <div class="form-group col-md-2">
                                                        <label for="txt_periodo">Periodo: </label>
                                                        <input type="text" class="form-control"
                                                               value="{{ $ingreso->periodo->descripcion  }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="txt_numero">Número: </label>
                                                        <input type="text" class="form-control .typechange"
                                                               name="txt_numero" id="txt_numero" placeholder="0000"
                                                               value="{{ $ingreso->numero }}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="txt_fecha">Fecha: </label>
                                                        <input type="date" class="form-control" name="txt_fecha"
                                                               id="txt_fecha" min="{{ $period->inicio }}"
                                                               max="{{ $period->final }}" value="{{ $ingreso->fecha }}">
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                        <label for="txt_tcambio">T. Cambio: </label>
                                                        <input type="text" class="form-control typechange"
                                                               name="txt_tcambio" id="txt_tcambio"
                                                               value="{{ $ingreso->tcambio }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label for="txt_movimiento">Movimiento: </label>
                                                        <select class="form-control select2" name="txt_movimiento"
                                                                id="txt_movimiento">
                                                            @foreach($mov_type as $mov)
                                                                <option value="{{$mov->id}}"
                                                                        @if( $mov->id == $ingreso->movimientotipo['id']) selected @endif>
                                                                    {{ $mov->codigo }} | {{ $mov->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_referencia">Referencia: </label>
                                                    <div class="input-group input-group-sm" style="z-index: 0">
                                                        <input type="text" class="form-control" name="txt_referencia"
                                                               id="txt_referencia" value="{{ $codigo_referencia }}"
                                                               ondblclick="add_orden({{ $ingreso->id  }})" readonly>
                                                        @if($referencia_id)
                                                            <span class="input-group-btn" id="span-referencia">
                                                     <button class="btn btn-dark" type="button">Ir</button>
                                                </span>
                                                        @endif
                                                    </div>
                                                    <input type="hidden" class="form-control" name="txt_referencia_id"
                                                           id="txt_referencia_id" value="{{ $referencia_id }}" readonly>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">Documento</div>
                                        <div class="panel-body">
                                            <div class="row">

                                                <div class="form-row">

                                                    <div class="form-group col-md-6">
                                                        <label for="txt_tercero">Proveedor: </label>
                                                        <select class="form-control select2" name="txt_tercero"
                                                                id="txt_tercero">
                                                            <option value="{{$ingreso->tercero_id}}"
                                                                    selected>{{$ingreso->tercero->codigo}}
                                                                | {{$ingreso->tercero->descripcion}}</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="txt_guia">Guía Remisión: </label>
                                                        <input type="text" class="form-control" name="txt_guia"
                                                               id="txt_guia"
                                                               value="{{ $ingreso->refgremision }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-row">

                                                    <div class="form-group col-md-2">
                                                        <label for="txt_factura">N° Factura: </label>
                                                        <input type="text" class="form-control" name="txt_factura"
                                                               id="txt_factura" value="{{ $ingreso->refprovision }}">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="txt_fechafactura">Fecha Factura: </label>
                                                        <input type="date" class="form-control" name="txt_fechafactura"
                                                               id="txt_fechafactura" value="{{ $ingreso->fechadoc }}">
                                                    </div>

                                                    <div class="form-group col-md-2">
                                                        <label for="txt_fecha">Moneda: </label>
                                                        <select class="form-control select2" name="txt_moneda"
                                                                id="txt_moneda">
                                                            @foreach($monedas as $moneda)
                                                                <option value="{{$moneda->id}}"
                                                                        @if( $moneda->id == $ingreso->moneda_id) selected @endif>
                                                                    {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="txt_glosa">Observaciones: </label>
                                                        <input type="text" class="form-control" name="txt_glosa"
                                                               id="txt_glosa"
                                                               value="{{ $ingreso->glosa }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="panel panel-info">
                                        <div class="panel-heading">Otros Datos</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label for="txt_almacen">Almacén: </label>
                                                        <select class="form-control select2" name="txt_almacen"
                                                                id="txt_almacen">
                                                            @foreach($almacenes as $almacen)
                                                                <option value="{{$almacen->id}}"
                                                                        @if( $almacen->id == $ingreso->almacen_id) selected @endif>
                                                                    {{$almacen->descripcion}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_ref_prov">Ref. Prov. Compra: </label>
                                                    <input type="hidden" name="documento_id" id="documento_id"
                                                           value="{{ $documento_id }}" disabled>
                                                    <input type="text" class="form-control" name="txt_ref_prov"
                                                           id="txt_ref_prov" ondblclick="provision({{ $documento_id }})"
                                                           value="{{ $codigo_provision }}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_total">Total Doc : </label>
                                                    <input type="text" class="form-control" name="txt_total"
                                                           id="txt_total"
                                                           value="{{ $ingreso->total }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <div class="row">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_detalles" id="home-tab" role="tab" data-toggle="tab"
                                           aria-expanded="true">Detalle del Documento</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_referencia" role="tab" id="aplica-tab" data-toggle="tab"
                                           aria-expanded="false">Referencias</a>
                                    </li>
                                </ul>
                            </div>

                            <div id="myTabContent" class="tab-content identificador ocultar">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_detalles"
                                     aria-labelledby="home-tab">
                                    @include ('incomeToWarehouse.tabs.carrito')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_referencia"
                                     aria-labelledby="aplica-tab">
                                    @include ('incomeToWarehouse.tabs.referencias')
                                </div>
                            </div>

                            @include ('incomeToWarehouse.modals.add_item')
                            @include ('incomeToWarehouse.modals.edit_item')

                            <input type="hidden" id="asiento" value="{{ $asiento_id }}">

                            @include ('incomeToWarehouse.modals.referencia')
                            @include ('incomeToWarehouse.modals.provision')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/ingresosalmacen.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function () {
            if (performance.navigation.type == 0) {
                if (document.referrer.includes('crear')) {
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
        backButtonRefresh();

        function producto(id) {
            if (id == null) {
                alert('No existe producto')
            } else {
                var url = '{{ route('edit.products', ["id" => ":id"]) }}';
                window.open(url.replace(':id', id), "_blank");
            }
        }

        function asiento(asiento_id) {
            return asiento_id != 0 ? window.location = '{{ route("show.dailyseat", $asiento_id) }}' : alert('No existe asiento')
        }

        function referencia(referencia_id) {
            return referencia_id != '' ? window.location = '{{ route("edit.purchaseorder", ["id" => $referencia_id]) }}' : alert('No existe referencia')
        }
    </script>

@endsection
