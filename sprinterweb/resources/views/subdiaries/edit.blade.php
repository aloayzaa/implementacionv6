@extends('templates.home')

@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="POST" >
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.subdiaries', $subdiario->id ) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $subdiario->id }}">
                <input type="hidden" id="estado" name="estado" value="{{ $subdiario->estado }}">
                <div class="container-fluid" style="background-color: white">
                    <div class="col-md-12 col-xs-12">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="name"> Código </label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="code_sd" name="code_sd" required value="{{$subdiario->codigo}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="name"> Descripción </label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="description_sd" name="description_sd" required value="{{$subdiario->descripcion}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="name"> Tipo Venta </label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" id="type" name="type">
                                        <option @if($subdiario->tcambio == 'C') selected @endif id="C">Compra</option>
                                        <option @if($subdiario->tcambio == 'V') selected @endif id="V">Venta</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="name"> Cód. Sunat (libro) </label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="sunat" name="sunat" required
                                           value="{{$subdiario->codsunat}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12">
                                    <label for="name"> &nbsp; </label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <label for=""><input type="checkbox" id="noautomatic" name="noautomatic"
                                                         @if($subdiario->noautomatic == 1) checked @endif>
                                        No genera Cuenta Automática en asiento diario</label>&nbsp;&nbsp;
                                    <label for=""><input type="checkbox" id="bicurrency" name="bicurrency"
                                                         @if($subdiario->bimoneda == 1) checked @endif>
                                        Pedir Importes en moneda oficial y extranjera</label>
                                    <label for=""><input type="checkbox" id="adjustment" name="adjustment"
                                                         @if($subdiario->deajuste == 1) checked @endif>
                                        Es para ajustes por diferencia de tipo de cambio</label>
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
    <script src="{{ asset('anikama/ani/subdiaries.js') }}"></script>
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
