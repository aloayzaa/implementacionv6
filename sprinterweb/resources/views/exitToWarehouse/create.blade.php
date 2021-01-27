@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <div class="col-xs-12">

                    <input type="hidden" id="ruta" name="ruta" value="{{ route('store.exittowarehouse') }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                    <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <form id="frm_generales" action="">
                                <h5 class="">Registro</h5>
                                <div class="row">
                                    <div class="form-row">
                                        <input type="hidden" id="id" name="id" value="{{0}}">
                                        <div class="form-group col-md-3">
                                            <label for="txt_periodo">Periodo: </label>
                                            <input type="text" class="form-control" value="{{$period->descripcion}}" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero">Unid. Negocio: </label>
                                            <select class="form-control select2" name="txt_unegocio" id="txt_unegocio">
                                                @foreach($unidades as $unidad)
                                                    <option value="{{$unidad->id}}">
                                                        {{$unidad->codigo}} | {{$unidad->descripcion}}
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
                                            <input type="date" class="form-control tipocambio" name="txt_fecha" id="txt_fecha" value="{{'2019-12-10'}}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_tcambio">T. Cambio: </label>
                                            <input type="text" class="form-control typechange" name="txt_tcambio" id="txt_tcambio" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="txt_almacen">Almacén: </label>
                                            <select class="form-control select2" name="txt_almacen" id="txt_almacen">
                                                @foreach($almacenes as $almacen)
                                                    <option value="{{ $almacen->id }}">
                                                        {{ $almacen->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <h5 class="">Documento</h5>
                                <div class="row">

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="txt_movimiento">Movimiento: </label>
                                            <select class="form-control select2" name="txt_movimiento" id="txt_movimiento">
                                                @foreach($mov_type as $mov)
                                                    <option value="{{$mov->id}}">
                                                        {{ $mov->codigo }} | {{ $mov->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="txt_tercero">Responsable: </label>
                                            <select class="form-control select2" name="txt_tercero" id="txt_tercero">
                                                @foreach($terceros as $tercero)
                                                    <option value="{{$tercero->id}}">
                                                        {{ $tercero->codigo }} | {{ $tercero->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_doc_venta">D. Venta: </label>
                                            <input type="text" class="form-control" name="txt_doc_venta" id="txt_doc_venta" value="">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_fecha">Moneda: </label>
                                            <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                                @foreach($monedas as $moneda)
                                                    <option value="{{$moneda->id}}">
                                                        {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="txt_glosa">Observaciones: </label>
                                            <input type="text" class="form-control" name="txt_glosa" id="txt_glosa" value="">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="txt_totaldoc">Total Doc: </label>
                                            <input type="text" class="form-control" name="txt_totaldoc" id="txt_totaldoc" value="" >
                                        </div>
                                    </div>

                                </div>

                            </form>

                            @include ('exitToWarehouse.carrito')
                            @include ('exitToWarehouse.modals.edit_item')
                            @include ('exitToWarehouse.modals.add_item')
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
    <script src="{{ asset('anikama/ani/salidasalmacen.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script>
        backButtonRefresh();
    </script>
@endsection
