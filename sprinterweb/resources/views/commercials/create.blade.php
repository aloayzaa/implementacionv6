@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.commercials') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
{{--                <input type="hidden" id="id" name="id" value="{{ 0 }}">--}}
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
                                           class="form-control">
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
                                        <option value="A" id="venta">Ambos</option>
                                        <option value="C" id="venta">Cobrar</option>
                                        <option value="P" id="compra">Pagar</option>
                                        <option value="" id="compra">Ninguno</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12"></div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="icheck-primary col-md-6">
                                            <input type="checkbox" id="primary" name="control">
                                            <label for="primary">Controlar Numeración</label>
                                        </div>
                                        {{--       </div>

                                               <div class="form-group">--}}
                                        <div class="icheck-info col-md-6">
                                            <input type="checkbox" id="info" name="bloquear">
                                            <label for="info">Bloquear Numeración en Ventas</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="icheck-peterriver col-md-6">
                                            <input type="checkbox" id="peterriver" name="compra">
                                            <label for="peterriver">Mostrar el Registro de Compras</label>
                                        </div>
                                        {{--</div>

                                        <div class="form-group">--}}
                                        <div class="icheck-belizehole col-md-6">
                                            <input type="checkbox" id="belizehole" name="venta">
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
@endsection
