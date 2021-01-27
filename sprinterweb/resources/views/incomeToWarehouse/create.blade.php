@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.7/css/select.dataTables.min.css">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <div class="col-xs-12">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.incometowarehouse') }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <form id="frm_generales" autocomplete="off">

                                <div class="panel panel-info">
                                    <div class="panel-heading">Registro</div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="form-row">
                                                <input type="hidden" id="id" name="id" value="{{ 0 }}">
                                                <div class="form-group col-md-2">
                                                    <label for="txt_periodo">Periodo: </label>
                                                    <input type="text" class="form-control"
                                                           value="{{ $period->descripcion }}" readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_numero">Número: </label>
                                                    <input type="text" class="form-control .typechange"
                                                           name="txt_numero" id="txt_numero" placeholder="0000" value=""
                                                           readonly>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_fecha">Fecha: </label>
                                                    <input type="date" class="form-control" name="txt_fecha"
                                                           id="txt_fecha" min="{{ $period->inicio }}"
                                                           max="{{ $period->final }}" value="{{ $today }}">

                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label for="txt_tcambio">T. Cambio: </label>
                                                    <input type="text" class="form-control typechange"
                                                           name="txt_tcambio" id="txt_tcambio" value="" readonly>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                    <label for="txt_movimiento">Movimiento: </label>
                                                    <select class="form-control select2" name="txt_movimiento"
                                                            id="txt_movimiento">
                                                        <option value="" selected>-- Seleccione una opción --</option>
                                                        @foreach($mov_type as $mov)
                                                            <option value="{{$mov->id}}">
                                                                {{ $mov->codigo }} | {{ $mov->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_referencia">Referencia: </label>
                                                <input type="text" class="form-control" name="txt_referencia"
                                                       id="txt_referencia" ondblclick="add_orden({{ 0 }})" value=""
                                                       readonly>
                                                <input type="hidden" class="form-control" name="txt_referencia_id"
                                                       id="txt_referencia_id" value="" readonly>
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
                                                        <option value="" selected>-- Seleccione una opción --</option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label for="txt_guia">Guía Remisión: </label>
                                                    <input type="text" class="form-control" name="txt_guia"
                                                           id="txt_guia"
                                                           value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-row">

                                                <div class="form-group col-md-2">
                                                    <label for="txt_factura">N° Factura: </label>
                                                    <input type="text" class="form-control" name="txt_factura"
                                                           id="txt_factura"
                                                           value="">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="txt_fechafactura">Fecha Factura: </label>
                                                    <input type="date" class="form-control" name="txt_fechafactura"
                                                           id="txt_fechafactura" value="">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label for="txt_fecha">Moneda: </label>
                                                    <select class="form-control select2" name="txt_moneda"
                                                            id="txt_moneda">
                                                        <option value="" selected>-- Seleccione una opción --</option>
                                                        @foreach($monedas as $moneda)
                                                            <option value="{{$moneda->id}}">
                                                                {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="txt_glosa">Observaciones: </label>
                                                    <input type="text" class="form-control" name="txt_glosa"
                                                           id="txt_glosa"
                                                           value="">
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
                                                        <option value="" selected>-- Seleccione una opción --</option>
                                                        @foreach($almacenes as $almacen)
                                                            <option value="{{$almacen->id}}">
                                                                {{$almacen->descripcion}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_ref_prov">Ref. Prov. Compra: </label>
                                                <input type="text" class="form-control" name="txt_ref_prov"
                                                       id="txt_ref_prov"
                                                       value="" disabled>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="txt_total">Total Doc : </label>
                                                <input type="text" class="form-control" name="txt_total" id="txt_total"
                                                       value="0.00" readonly>
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

                            <div id="myTabContent" class="tab-content">
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
                            @include ('incomeToWarehouse.modals.referencia')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script src="{{ asset('anikama/ani/ingresosalmacen.js') }}"></script>
    <script>
        backButtonRefresh();

        function producto(id) {
            if (id == null) {
                alert('No existe producto')
            } else {
                var url = '{{ route('edit.products', ["id" => ":id"]) }}';
                window.open(url.replace(':id', id), "_blank");
            }
        }
    </script>
    {{--    <script src="{{ asset('js/datatables.js') }}"></script>--}}
@endsection
