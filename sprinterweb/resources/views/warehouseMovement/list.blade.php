@extends('templates.app')
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <form class="form-horizontal" id="frmProcesarValorizacion" name="frmProcesarValorizacion">
                                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="modulo" id="modulo" value="modcxc">
                                <div class="row">
                                    <div class="text-center">
                                        <h2><span class="fa fa-gear"></span> Proceso de Actualización de Kardex de Almacén Valorizado</h2><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12"></div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Desde:</label>
                                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                                   data-inputmask="'mask': '99/99/9999'" placeholder="00/00/0000">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="fecha_fin">Hasta:</label>
                                            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                                   data-inputmask="'mask': '99/99/9999'" placeholder="00/00/0000">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12"></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary" onclick="procesar_valorizacion()">
                                            <span class="fa fa-refresh"></span> Procesar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('anikama/ani/warehousemovement.js') }}"></script>
@endsection
