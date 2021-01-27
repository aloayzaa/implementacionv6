@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT"
                  data-route="{{ route('tariffitems') }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $parancelaria->id }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="txt_codigo">Código:</label>
                                </div>
                                <div class="col-md-2 col-sm-4 col-xs-12">
                                    <input type="text" id="txt_codigo" name="txt_codigo"
                                           class="form-control" value="{{ $parancelaria->codigo }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="txt_descripcion">Descripción:</label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                    <input type="text" name="txt_descripcion" id="txt_descripcion"
                                           class="form-control" value="{{ $parancelaria->descripcion }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="txt_arancel">Arancel:</label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input type="text" name="txt_arancel" id="txt_arancel"
                                           class="form-control" value="{{ $parancelaria->valor }}"">
                                </div>
                            </div>
                            <div class="row">
                                <p class="title-view">Del Impuesto Selectivo al Consumo:</p>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="txt_arancel_consumo">Arancel:</label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <input type="text" name="txt_arancel_consumo" id="txt_arancel_consumo"
                                           class="form-control" value="{{ $parancelaria->isc }}"">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="cbo_tipo">Tipo:</label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <select name="cbo_tipo"  id="cbo_tipo" class="form-control">
                                        <option value="N" @if($parancelaria->isc == "N") selected @endif>Nominal</option>
                                        <option value="P" @if($parancelaria->isc == "P") selected @endif>Porcentual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-sm-1 col-xs-12 label-input">
                                    <label for="cbo_tipo_isc">Tipo ISC:</label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12">
                                    <select name="cbo_tipo_isc" id="cbo_tipo_isc" class="form-control select2">
                                        @foreach($typeisc as $t)
                                                <option value="{{$t->id}}" @if($t->id == $parancelaria->tipoisc_id) selected @endif>{{$t->descripcion}}</option>
                                        @endforeach
                                    </select>
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
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/tariffitems.js') }}"></script>
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

