@extends('templates.app')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <div class="col-xs-12">

                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.accountingplans') }}">
                <input type="hidden" name="proceso" id="proceso"
                       @if(isset($proceso)) value="{{$proceso}}" @else value="" @endif/>
                <input type="hidden" class="form-control" id="_token" name="_token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <form id="frm_generales" autocomplete="off">

                                <input type="hidden" id="id" name="id" value="{{ 0 }}">

                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="code_pcg">Código</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="code_pcg" name="code_pcg">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="descripcion">Descripción</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12" id="nombre_comercial">
                                        <input class="form-control" type="text" id="descripcion" name="descripcion">
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
                                                <input type="checkbox" id="es_divisionaria" name="es_divisionaria">
                                                <label for="es_divisionaria">Divisionaria</label>
                                            </div>

                                            <div class="icheck-info col-md-6">
                                                <input type="checkbox" id="es_analisis" name="es_analisis">
                                                <label for="es_analisis">Análisis</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="icheck-belizehole col-md-6">
                                                <input type="checkbox" id="pide_ccosto" name="pide_ccosto">
                                                <label for="pide_ccosto">Exige Centro Costo</label>
                                            </div>

                                            <div class="icheck-belizehole col-md-6">
                                                <input type="checkbox" id="pide_op" name="pide_op">
                                                <label for="pide_op">Exige Orden Trabajo/Producción</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <br>

                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="name">Clase</label>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <select class="form-control select2" name="tipo_cuenta" id="tipo_cuenta">
                                             @foreach($clases as $clase)
                                                <option value="{{ $clase['value'] }}"  @if($clase['value'] == '') selected @endif>
                                                    {{ $clase['desc'] }}</option>
                                             @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="dbalance">Naturaleza de Cta</label>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <select class="form-control select2" name="dbalance" id="dbalance">
                                            <option value="" selected>-- Seleccione una opción --</option>
                                            @foreach($naturalezas as $naturaleza)
                                                <option value="{{ $naturaleza['value'] }}">
                                                    {{ $naturaleza['desc'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="moneda">Moneda</label>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <select class="form-control select2" name="moneda" id="moneda">
                                            <option value="" selected>-- Seleccione una opción  -- </option>
                                            @foreach($currencies as $currency)
                                                <option value="{{$currency->id}}">
                                                    {{$currency->codigo}} | {{$currency->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="tipo_auxiliar">Tipo Auxiliar</label>
                                    </div>
                                    <div class="col-md-3 col-xs-12" id="nombre_comercial">
                                        <select class="form-control select2" id="tipo_auxiliar" name="tipo_auxiliar">
                                            @foreach($auxiliares as $auxiliar)
                                                <option value="{{ $auxiliar['value'] }}" >{{ $auxiliar['desc']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="tipo_ajuste">Ajuste Diferencia T.C</label>
                                    </div>
                                    <div class="col-md-3 col-xs-12" id="nombre_comercial">
                                        <select class="form-control select2" id="tipo_ajuste" name="tipo_ajuste">
                                            @foreach($ajustes as $ajuste)
                                                <option value="{{ $ajuste['value'] }}">
                                                    {{ $ajuste['desc'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="tipo_cambio">Tipo Cambio</label>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <select class="form-control select2" id="tipo_cambio" name="tipo_cambio">
                                            <option value="" selected>-- Seleccione una opción --</option>
                                            @foreach($tiposdecambio as $tipodecambio)
                                                <option value="{{ $tipodecambio['value'] }}">
                                                    {{ $tipodecambio['desc'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="ln_solid"></div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="ctacargo_id">Cta Cargo</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <select class="form-control select2" name="ctacargo_id" id="ctacargo_id">
                                            <option value="">-- Seleccione una opción --</option>
                                            @foreach($pcgs as $pcg)
                                                <option value="{{$pcg->id}}">
                                                    {{$pcg->codigo}} | {{$pcg->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-1 col-xs-12 label-input">
                                        <label for="ctaabono_id">Cta Abono</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12" id="nombre_comercial">
                                        <select class="form-control select2" name="ctaabono_id" id="ctaabono_id">
                                            <option value="">-- Seleccione una opción --</option>
                                            @foreach($pcgs as $pcg)
                                                <option value="{{$pcg->id}}">
                                                    {{$pcg->codigo}} | {{$pcg->descripcion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>
                    </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/accountingplan.js') }}"></script>
@endsection
