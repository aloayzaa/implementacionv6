@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"  method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.commercials', $commercial->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $commercial->id }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="code">Código:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text"
                                           id="code_doc"
                                           name="code_doc"
                                           class="form-control"
                                           value="{{ $commercial->codigo }} "
                                           >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Descripción:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           class="form-control"
                                           value="{{ $commercial->descripcion }}"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codSunat">Código sunat:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text"
                                           id="codSunat"
                                           name="codSunat"
                                           class="form-control"
                                           value="{{ $commercial->codsunat }}"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="factor">Factor:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="number"
                                           id="factor"
                                           name="factor"
                                           class="form-control"
                                           value="{{ $commercial->factor }}"
                                           max="1"
                                           min="-1"
                                           required>
                                </div>
                            </div>
                            <div class="row">
                               <div class="col-md-2 col-xs-12 label-input">
                                   <label for="origin">Origen:</label>
                               </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control"
                                            name="origin"
                                            id="origin">
                                        <option value="A" id="venta" @if($commercial->origen == 'A') selected @endif>Ambos</option>
                                        <option value="C" id="venta" @if($commercial->origen == 'C') selected @endif>Cobrar</option>
                                        <option value="P" id="compra" @if($commercial->origen == 'P') selected @endif>Pagar</option>
                                        <option value="" id="compra" @if($commercial->origen == '') selected @endif>Ninguno</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12"></div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="icheck-primary col-md-6">
                                            <input type="checkbox" id="primary" name="control"
                                                   @if($commercial->controlnum == 1) checked @endif>
                                            <label for="primary">Controlar Numeración</label>
                                        </div>
                                        {{--       </div>

                                               <div class="form-group">--}}
                                        <div class="icheck-info col-md-6">
                                            <input type="checkbox" id="info" name="bloquear"
                                                   @if($commercial->bloquear == 1) checked @endif>
                                            <label for="info">Bloquear Numeración en Ventas</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="icheck-peterriver col-md-6">
                                            <input type="checkbox" id="peterriver" name="compra"
                                                   @if($commercial->verenrcompra == 1) checked @endif>
                                            <label for="peterriver">Mostrar el Registro de Compras</label>
                                        </div>
                                        {{--</div>

                                        <div class="form-group">--}}
                                        <div class="icheck-belizehole col-md-6">
                                            <input type="checkbox" id="belizehole" name="venta"
                                                   @if($commercial->verenrventa == 1) checked @endif>
                                            <label for="belizehole">Mostrar en el Registro de Ventas</label>
                                        </div>
                                    </div>
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
    <script src="{{ asset('anikama/ani/commercials.js') }}"></script>

    <script>
        if(performance.navigation.type == 0){
            if(document.referrer.includes('commercials/create')){
                success('success', 'El registro se realizó correctamente', 'Guardado!');
            }
        }
    </script>
@endsection
