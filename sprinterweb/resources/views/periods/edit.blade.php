@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="ruta" name="ruta" value="{{ route('update.periods', ['id' => $periodo->id]) }}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-12 col-xs-12">

                        <form class="form-horizontal" id="frm_generales" name="frm_generales">

                            <div class="form-group">
                                <label class="control-label col-md-3" for="codigo">
                                    Código
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="codigo" name="codigo" value="{{ $periodo->codigo }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" for="descripcion">
                                    Descripción
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="descripcion" name="descripcion" value="{{ $periodo->descripcion }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" for="inicio">
                                    Fecha Inicio
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="date"  name="inicio" id="f_inicio" value="{{ $periodo->inicio }}" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3" for="final">
                                    Fecha Fin
                                </label>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="date" name="final" id="f_final" value="{{ $periodo->final }}" readonly>
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
    <script src="{{ asset('anikama/ani/period.js') }}"></script>
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
