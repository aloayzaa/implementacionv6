@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_content">
                <div class="col-xs-12">
                    <input type="hidden" id="ruta" name="ruta" value="{{ route('store.dailyseat') }}">
                    <input type="hidden" name="proceso" id="proceso"
                           @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                    <input type="hidden" name="route" id="route"
                           @if(isset($route)) value="{{$route}}" @else value="" @endif/>

                    <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

                    <form id="frm_generales" action="">
                        <div class="row">
                            <div class="form-row">
                                <input type="hidden" id="id" name="id" value="{{ 0 }}">
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
                                    <input type="date" class="form-control" name="txt_fecha" id="txt_fecha" value="">
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="txt_tcambio">T. Cambio: </label>
                                    <input type="text" class="form-control typechange" name="txt_tcambio" id="txt_tcambio" value="" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="subdiary">Subdiario: </label>
                                <select class="form-control select2" name="subdiary" id="subdiary">
                                    <option disabled selected>Seleccionar</option>
                                    @foreach($subdiarios as $subdiario)
                                        <option value="{{$subdiario->id}}">
                                            {{$subdiario->codigo}} | {{$subdiario->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">

                            <div class="form-row">

                                <div class="form-group col-md-3">
                                    <label for="txt_sucursal">Sucursal: </label>
                                    <select class="form-control select2" name="txt_sucursal" id="txt_sucursal">
                                        @foreach($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">
                                                {{ $sucursal->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="txt_fecha">Moneda: </label>
                                    <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                        @foreach($monedas as $moneda)
                                            <option value="{{ $moneda->id }}">
                                                {{ $moneda->codigo }} | {{ $moneda->descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="txt_tc">T. C: </label>
                                    <input type="text" class="form-control" name="txt_tc" id="txt_tc" value="" disabled> {{--Para euros--}}
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="txt_glosa">Glosa: </label>
                                    <input type="text" class="form-control" name="txt_glosa" id="txt_glosa" value="" >
                                </div>


                            </div>

                        </div>

                    </form>

                    @include ('dailyseat.carrito')

                </div>

                    <button class="btn-info" onclick="store()">Registrar</button>
                    <button class="btn-success" onclick="agregar()">Agregar</button>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/dailyseat.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
@endsection
