@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <div class="col-xs-12">
                    <input type="hidden" id="ruta" name="ruta" value="{{ route('store.ordertowarehouse') }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                    <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">

                            <form id="frm_generales" autocomplete="off">
                                <div class="row">
                                    <p class="title-view">Registro:</p>
                                </div>
                                <div class="row">
                                    <div class="form-row">
                                        <input type="hidden" id="id" name="id" value="{{0}}">
                                        <div class="form-group col-md-3">
                                            <label for="txt_periodo">Periodo: </label>
                                            <input type="text" class="form-control" value="{{ $period->descripcion }}" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero">Unid. Negocio: </label>
                                            <select class="form-control select2" name="txt_unegocio" id="txt_unegocio">
                                                @foreach($unidades as $unidad)
                                                    <option value="{{ $unidad->id }}">
                                                        {{ $unidad->codigo }} | {{ $unidad->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_numero">Número: </label>
                                            <input type="text" class="form-control .typechange" name="txt_numero" id="txt_numero" placeholder="0000" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_fecha">Fecha: </label>
                                            <input type="date" class="form-control tipocambio" name="txt_fecha" id="txt_fecha" min="{{ $period->inicio }}" max="{{ $period->final }}" value="{{ $today }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_tcambio">T. Cambio: </label>
                                            <input type="text" class="form-control typechange" name="txt_tcambio" id="txt_tcambio" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="txt_movimiento">Movimiento: </label>
                                        <select class="form-control select2" name="txt_movimiento" id="txt_movimiento">
                                            <option value="" selected>-- Seleccione una opción  -- </option>
                                            @foreach($mov_type as $mov)
                                                <option value="{{ $mov->id }}">
                                                    {{ $mov->codigo }} | {{ $mov->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="txt_ordentrabajo">Orden de trabajo/Cotización: </label>
                                        <select class="form-control select2" name="txt_ordentrabajo" id="txt_ordentrabajo">
                                            <option value="" disabled selected>-- Seleccione una Orden --</option>
                                            @foreach($workorders as $workorder)
                                                <option value="{{ $workorder->id }}">
                                                    {{ $workorder->nromanual }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <p class="title-view">Datos del Pedido:</p>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="txt_tercero">Solicitante: </label>
                                        <select class="form-control select2" name="txt_tercero" id="txt_tercero">
                                            <option value="" selected>-- Seleccione una opción  -- </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="txt_glosa">Observaciones: </label>
                                        <input type="text" class="form-control" name="txt_glosa" id="txt_glosa" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="txt_sucursal">Sucursal: </label>
                                        <select class="form-control select2" name="txt_sucursal" id="txt_sucursal">
                                            <option value="" selected>-- Seleccione una opción  -- </option>
                                            @foreach($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}">
                                                    {{ $sucursal->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="txt_tercero">Almacen: </label>
                                        <select class="form-control select2" name="txt_almacen" id="txt_almacen">
                                            <option value="" selected>-- Seleccione una opción  -- </option>
                                            @foreach($almacenes as $almacen)
                                                <option value="{{ $almacen->id }}">
                                                    {{ $almacen->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="txt_fecha">Moneda: </label>
                                        <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                            <option value="" selected>-- Seleccione una opción  -- </option>
                                            @foreach($monedas as $moneda)
                                                <option value="{{ $moneda->id }}">
                                                    {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <div class="row">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_detalles" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Detalle del Documento</a>
                                    </li>
                                    <li role="presentation" class="">
                                        <a href="#tab_aplicaciones" role="tab" id="aplica-tab" data-toggle="tab" aria-expanded="false">Aplicaciones</a>
                                    </li>
                                </ul>
                            </div>

                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_detalles" aria-labelledby="home-tab">
                                    @include ('orderToWarehouse.carrito')
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tab_aplicaciones" aria-labelledby="aplica-tab">

                                </div>
                            </div>
                            @include ('orderToWarehouse.modals.add_item')
                            @include ('orderToWarehouse.modals.edit_item')

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
    <script src="{{ asset('anikama/ani/ordertowarehouse.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script>
        backButtonRefresh();
        function producto(id) {
            if(id == null){
                alert('No existe producto')
            }else{
                var url = '{{ route('edit.products', ["id" => ":id"]) }}';
                window.open(url.replace(':id', id), "_blank");
            }
        }
    </script>
@endsection
