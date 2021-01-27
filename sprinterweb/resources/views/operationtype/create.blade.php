@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="ruta" name="ruta" value="{{ route('store.operationtype') }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">

                        <form class="form-horizontal" id="frm_generales" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="codigo">Código:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" id="codigo" name="codigo" class="form-control">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="descripcion">Descripción:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" id="descripcion" name="descripcion" class="form-control" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="tipo">Tipo Operación :</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <select class="form-control select2" name="tipo" id="tipo">
                                            @foreach($tipos as $tipo)
                                            <option value="{{ $tipo['value'] }}">{{ $tipo['desc'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="row">
                                    <div class="col-md-2 col-xs-12">

                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <div class="form-group">
                                            <div class="icheck-info col-md-6">
                                                <input type="checkbox" class="solouno" id="estransferencia" name="estransferencia">
                                                <label for="estransferencia">Es de Transferencia</label>
                                            </div>

                                            <div class="icheck-info col-md-6">
                                                <input type="checkbox" class="solouno" id="esajuste" name="esajuste">
                                                <label for="esajuste">Es de Ajuste por Tipo de Cambio</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="icheck-belizehole col-md-6">
                                                <input type="checkbox" id="esanticipo" name="esanticipo">
                                                <label for="esanticipo">Es para crear documentos (anticipos, entregas, préstamos)</label>
                                            </div>

                                            <div class="icheck-belizehole col-md-6">
                                                <input type="checkbox" id="verflujo" name="verflujo" checked>
                                                <label for="verflujo">Mostrar en el Flujo Efectivo</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-xs-12">

                                    </div>
                                    <div class="col-md-10 col-xs-12">
                                        <div class="form-group">

                                            <div class="icheck-belizehole col-md-4">
                                                <input type="checkbox" id="pidedocumento" name="pidedocumento">
                                                <label for="pidedocumento">Requiere tener asociado un documento de terceros</label>
                                            </div>
                                            <div class="col-md-2 col-xs-12">
                                                <select class="form-control select2" name="origen" id="origen" disabled>
                                                    <option value="C">Cobrar</option>
                                                    <option value="P">Pagar</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/operationtype.js') }}"></script>
@endsection
