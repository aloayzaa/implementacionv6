@extends('templates.home')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <div class="col-xs-12">

                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.exittowarehouse') }}">
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
                                    <input type="hidden" id="id" name="id" value="{{ $salida->id }}">
                                    <div class="form-row">

                                        <div class="form-group col-md-3">
                                            <label for="txt_periodo">Periodo: </label>
                                            <input type="text" class="form-control" value="{{$period->descripcion}}" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero">Unid. Negocio: </label>
                                            <select class="form-control select2" name="txt_unegocio" id="txt_unegocio">
                                                @foreach($unidades as $unidad)
                                                    <option value="{{$salida->unegocio_id}}">
                                                        {{$unidad->codigo}} | {{$unidad->descripcion}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_numero">Número: </label>
                                            <input type="text" class="form-control .typechange" name="txt_numero" id="txt_numero" value="{{$salida->numero}}" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_fecha">Fecha: </label>
                                            <input type="date" class="form-control" name="txt_fecha" id="txt_fecha" value="{{$salida->fecha}}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_tcambio">T. Cambio: </label>
                                            <input type="text" class="form-control typechange" name="txt_tcambio" id="txt_tcambio" value="{{$salida->tcambio}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="txt_almacen">Almacén: </label>
                                            <select class="form-control select2" name="txt_almacen" id="txt_almacen">
                                                @foreach($almacenes as $almacen)
                                                    <option value="{{$almacen->id}}"
                                                            @if( $almacen->id == $salida->almacen_id) selected @endif>
                                                        {{$almacen->descripcion}}
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
                                                    <option value="{{$mov->id}}"
                                                            @if( $mov->id == $salida->movimientotipo['id']) selected @endif>
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
                                                    <option value="{{$tercero->id}}"
                                                            @if( $tercero->id == $salida->tercero['id']) selected @endif>
                                                        {{ $tercero->codigo }} | {{ $tercero->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_doc_venta">D. Venta: </label>
                                            <input type="text" class="form-control" name="txt_doc_venta" id="txt_doc_venta" ondblclick="window.location='{{ route("show.billing",array("1")) }}'" value="@if($salida->docxpagar_id != null) {{$factura->seriedoc.'-'.$factura->numerodoc}} @endif">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_fecha">Moneda: </label>
                                            <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                                @foreach($monedas as $moneda)
                                                    <option value="{{$moneda->id}}"
                                                            @if( $moneda->id == $salida->moneda_id) selected @endif>
                                                        {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="txt_glosa">Observaciones: </label>
                                            <input type="text" class="form-control" name="txt_glosa" id="txt_glosa" value="{{$salida->glosa}}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label for="txt_totaldoc">Total Doc: </label>
                                            <input type="text" class="form-control" name="txt_totaldoc" id="txt_totaldoc" value="{{$salida->total}}" >
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
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        backButtonRefresh();
        $(document).ready(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('crear')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
    </script>
@endsection
