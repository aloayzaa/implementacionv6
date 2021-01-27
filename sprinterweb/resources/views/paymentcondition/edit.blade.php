@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.paymentcondition', $condicionpago->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $condicionpago->id }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_codigo">Código:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="text" id="txt_codigo_cp" name="txt_codigo_cp"
                                       class="form-control" value="{{ $condicionpago->codigo }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                <label for="txt_descripcion">Descripción:</label>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="text" name="txt_descripcion_cp" id="txt_descripcion_cp"
                                       class="form-control" value="{{ $condicionpago->descripcion }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_dias_credito">Días Crédito:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="text" name="txt_dias_credito" id="txt_dias_credito"
                                       class="form-control" value="{{ $condicionpago->dias }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_simbolo">Simbolo:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="text" name="txt_simbolo" id="txt_simbolo"
                                       class="form-control" value="{{ $condicionpago->simbolo }}">
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
    <script src="{{ asset('anikama/ani/paymentcondition.js') }}"></script>
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

