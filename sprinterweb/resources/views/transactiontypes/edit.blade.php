@extends('templates.home')

@section('content')

    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.transactiontypes', $tipotransaccion->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $tipotransaccion->id }}">
                <input type="hidden" id="estado" name="estado" value="{{ $tipotransaccion->estado }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="txt_codigo_tipo_trans">Código:</label>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <input type="text" id="txt_codigo_tipo_trans" name="txt_codigo_tipo_trans" class="form-control" value="{{ $tipotransaccion->codigo }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="txt_descripcion_tipo_trans">Descripción:</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" name="txt_descripcion_tipo_trans" id="txt_descripcion_tipo_trans" class="form-control" value="{{ $tipotransaccion->descripcion }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                    <label for="txt_cod_sunat">Cod.Sunat:</label>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <input type="text" id="txt_cod_sunat" name="txt_cod_sunat" class="form-control" value="{{ $tipotransaccion->codsunat }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                    <label for="txt_simbolo">Símbolo:</label>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <input type="text" id="txt_simbolo" name="txt_simbolo" class="form-control" value="{{ $tipotransaccion->simbolo }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/transactiontypes.js') }}"></script>
    <script>
        $(document).ready(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
    </script>
@endsection

