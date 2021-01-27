@extends('templates.home')
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">

            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="ruta" name="ruta" value="{{ route('update.exchangerate', ['id' => $tipocambio->id]) }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">

                            <form class="form-horizontal" id="frm_generales" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="codigo">Fecha:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" class="form-control" name="fecha" value="{{ $tipocambio->fecha }}" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="descripcion">Compra:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" class="form-control" name="t_compra" value="{{ $tipocambio->t_compra }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="descripcion">Venta:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" class="form-control" name="t_venta" value="{{ $tipocambio->t_venta }}" required>
                                    </div>
                                </div>

                                <br>
                                <br>

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
    <script src="{{ asset('anikama/ani/exchangerate.js') }}"></script>

@endsection
