@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <div class="col-xs-12">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.dailyseat') }}">
                <input type="hidden" name="proceso" id="proceso"
                       @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                <input type="hidden" name="route" id="route"
                       @if(isset($route)) value="{{$route}}" @else value="" @endif/>
                <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <form id="frm_generales" action="">
                                <div class="row">
                                    <div class="form-row">
                                        <input type="hidden" id="id" name="id" value="{{ $asiento->id }}">
                                        <div class="form-group col-md-3">
                                            <label for="txt_periodo">Periodo: </label>
                                            <input type="text" class="form-control" value="{{$period->descripcion}}" readonly>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_numero">Unid. Negocio: </label>
                                            <select class="form-control select2" name="txt_unegocio" id="txt_unegocio">
                                                @foreach($unidades as $unidad)
                                                    <option value="{{$unidad->id}}"
                                                     @if( $unidad->id == $asiento->id) selected @endif>
                                                        {{$unidad->codigo}} | {{$unidad->descripcion}}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_numero">Número: </label>
                                            <input type="text" class="form-control .typechange" name="txt_numero" id="txt_numero" placeholder="0000" value="{{ $asiento->numero }}" readonly>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_fecha">Fecha: </label>
                                            <input type="date" class="form-control" name="txt_fecha" id="txt_fecha" value="{{ $asiento->fechaproceso }}">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="txt_tcambio">T. Cambio: </label>
                                            <input type="text" class="form-control typechange" name="txt_tcambio" id="txt_tcambio" value="{{ $asiento->tcambio }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="subdiary">Subdiario: </label>
                                        <select class="form-control select2" name="subdiary" id="subdiary">
                                            <option disabled selected>Seleccionar</option>
                                            @foreach($subdiarios as $subdiario)
                                                <option value="{{$subdiario->id}}"
                                                @if( $subdiario->id == $asiento->subdiario_id) selected @endif>
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
                                                    <option value="{{ $sucursal->id }}"
                                                     @if( $sucursal->id == $asiento->sucursal_id) selected @endif>
                                                        {{ $sucursal->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="txt_fecha">Moneda: </label>
                                            <select class="form-control select2" name="txt_moneda" id="txt_moneda">
                                                @foreach($monedas as $moneda)
                                                    <option value="{{ $moneda->id }}"
                                                      @if( $moneda->id == $asiento->moneda_id) selected @endif>
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
                                            <input type="text" class="form-control" name="txt_glosa" id="txt_glosa" value="{{ $asiento->glosa }}" >
                                        </div>


                                    </div>

                                </div>

                            </form>
                            <br>
                            @include ('dailyseat.carrito')
                        </div>
                    </div>
                </div>

                @include ('incomeToWarehouse.modals.provision')
                <input type="hidden" id="asientito" value="{{ $asiento->referencia_id }}">

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
    <script type="text/javascript">
         backButtonRefresh();
        $('#btn_asientito').click(function () {
            history.back();
        });
        function cuenta(id) {
            if(id == null){
                alert('No existe provision')
            }else{
                var url = '{{ route('edit.accountingplans', ["id" => ":id"]) }}';
                window.open(url.replace(':id', id), "_blank");
            }
        }
    </script>
@endsection
