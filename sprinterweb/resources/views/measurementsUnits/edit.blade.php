@extends('templates.home')
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.measurements', $umedida->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $umedida->id }}">
                <input type="hidden" id="estado" name="estado" value="{{ $umedida->estado }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_codigo">Código:</label>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="txt_codigo_unidad" name="txt_codigo_unidad" class="form-control" value="{{$umedida->codigo}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_descripcion">Descripción:</label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="txt_descripcion_unidad" id="txt_descripcion_unidad" class="form-control" value="{{$umedida->descripcion}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_codigo_sunat">Cód.Sunat:</label>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" name="txt_codigo_sunat" id="txt_codigo_sunat" class="form-control" value="{{$umedida->codsunat}}">
                            </div>
                        </div>
                        @include('measurementsUnits.conversion')
                    </div>
                </div>
            </form>
            @include('measurementsUnits.modal.add_umedida')
            @include('measurementsUnits.modal.edit_umedida')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/measurement.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            DetalleUnidadMedida.init('{{ route('list_unidadmedida.measurements') }}');
        });
        $(document).ready(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
            }
        });
    </script>
@endsection
