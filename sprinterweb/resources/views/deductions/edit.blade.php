@extends('templates.home')

@section('content')

    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.deductions', $deduction->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $deduction->id }}">
                <input type="hidden" id="estado" name="estado" value="{{ $deduction->estado }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código:</label>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="code" name="code" class="form-control" value="{{ $deduction->codigo }}" required>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Descripción:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="description" name="description" class="form-control"value="{{ $deduction->descripcion }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codSunat">Código Sunat:</label>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="sunatcode" name="sunatcode" class="form-control" value="{{ $deduction->codsunat }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="symbol">Simbolo:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="symbol" name="symbol" class="form-control" value="{{ $deduction->simbolo }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="value">Valor:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="value" name="value" class="form-control" value="@if(intval($deduction->valor) < 1) @else{{ number_format($deduction->valor, 2, '.', ',') }} @endif" required>
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
    <script src="{{ asset('anikama/ani/deductions.js') }}"></script>
    <script>
        $(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('deductions/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('deductions/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>
@endsection
