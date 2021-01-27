@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">

            <input type="hidden" id="var" name="var" value="{{ $var }}">
            <input type="hidden" id="ruta" name="ruta" value="{{ route('store.movementtype') }}">
            <input type="hidden" name="proceso" id="proceso" @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
            <input type="hidden" name="route" id="route" @if(isset($route)) value="{{$route}}" @else value="" @endif/>
            <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="col-md-12 col-xs-12">
                        <form id="frm_generales" action="">

                            <div class="row">
                                <div class="form-row">
                                    <input type="hidden" id="id" name="id" value="{{0}}">

                                    <div class="form-group col-md-2">
                                        <label for="txt_codigo">Codigo: </label>
                                        <input type="text" class="form-control" name="txt_codigo" id="txt_codigo" placeholder="0000" value="">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="txt_descripcion">Descripción: </label>
                                        <input type="text" class="form-control" name="txt_descripcion" id="txt_descripcion" value="">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="txt_codsunat">Código Sunat: </label>
                                        <input type="text" class="form-control" name="txt_codsunat" id="txt_codsunat" placeholder="0000" value="">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="txt_grupo">Grupo: </label>
                                        <select class="form-control select2" name="txt_grupo" id="txt_grupo">
                                            @foreach($grupos as $grupo)
                                                <option value="{{ $grupo['value'] }}">
                                                    {{ $grupo['desc'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="txt_movtype">Tipo Movimiento: </label>
                                        <select class="form-control select2" name="txt_movtype" id="txt_movtype">
                                            @foreach($movimientos as $movimiento)
                                                <option value="{{ $movimiento['value'] }}">
                                                    {{ $movimiento['desc'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="txt_typecalc">Tipo Cálculo: </label>
                                        <select class="form-control select2" name="txt_typecalc" id="txt_typecalc">
                                            @foreach($calculos as $calculo)
                                                <option value="{{ $calculo['value'] }}">
                                                    {{ $calculo['desc'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="txt_tipocambio">Tipo Cambio: </label>
                                        <select class="form-control select2" name="txt_tipocambio" id="txt_tipocambio">
                                            @foreach($cambios as $cambio)
                                                <option value="{{ $cambio['value'] }}">
                                                    {{ $cambio['desc'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label for="txt_tventa">Tomar la configuración contable del tipo de venta: </label>
                                        <select class="form-control select2" name="txt_tventa" id="txt_tventa">
                                            <option value="" selected>-- Seleccione una opción --</option>
                                            @foreach($t_ventas as $t_venta)
                                                <option value="{{$t_venta->id}}">
                                                    {{ $t_venta->codigo }} | {{ $t_venta->descripcion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <div class="icheck-belizehole col-md-6">
                                            <input type="checkbox" id="check" name="check">
                                            <label for="check">Requiere valorización al momento de registrarse</label>
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
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/ani/movementType.js') }}"></script>
@endsection
