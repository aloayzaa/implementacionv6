@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="ruta" name="ruta" value="{{ route('update.operationtype', ['id' => $operationtype->id]) }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">

                            <form class="form-horizontal" id="frm_generales" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="codigo">Código:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" class="form-control" id="codigo" name="codigo" value="{{ $operationtype->codigo }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="descripcion">Descripción:</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $operationtype->descripcion }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-12 label-input text-center">
                                        <label for="tipo">Tipo Operación :</label>
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <select class="form-control select2" name="tipo" id="tipo">
                                            @foreach($tipos as $tipo)
                                                <option value="{{ $tipo['value'] }}"
                                                @if( $tipo['value'] === $operationtype->tipo) selected @endif>{{ $tipo['desc'] }}</option>
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
                                                <input type="checkbox" id="estransferencia" name="estransferencia" @if($operationtype->estransferencia == 1) checked @endif>
                                                <label for="estransferencia">Es de Transferencia</label>
                                            </div>

                                            <div class="icheck-info col-md-6">
                                                <input type="checkbox" id="esajuste" name="esajuste" @if($operationtype->esajuste == 1) checked @endif>
                                                <label for="esajuste">Es de Ajuste por Tipo de Cambio</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="icheck-belizehole col-md-6">
                                                <input type="checkbox" id="esanticipo" name="esanticipo" @if($operationtype->esanticipo == 1) checked @endif>
                                                <label for="esanticipo">Es para crear documentos (anticipos, entregas, préstamos)</label>
                                            </div>

                                            <div class="icheck-belizehole col-md-6">
                                                <input type="checkbox" id="verflujo" name="verflujo" @if($operationtype->verflujo == 1) checked @endif>
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
                                                <input type="checkbox" id="pidedocumento" name="pidedocumento" @if($operationtype->pidedocumento == 1) checked @endif>
                                                <label for="pidedocumento">Requiere tener asociado un documento de terceros</label>
                                            </div>
                                            <div class="col-md-2 col-xs-12">
                                                <select class="form-control select2" name="origen" id="origen" @if($operationtype->pidedocumento == 0) disabled @endif >
                                                    @foreach($origenes as $origen)
                                                        <option value="{{ $origen['value'] }}"
                                                       @if( $origen['value'] === $operationtype->origen) selected @endif>{{ $origen['desc'] }}</option>
                                                    @endforeach
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
