@extends('templates.app')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $centroCostos->id }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Código</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" value="{{$centroCostos->codigo}}" type="text" id="code_cost"
                                           name="code_cost"
                                           readonly required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Descripción</label>
                                </div>
                                <div class="col-md-6 col-xs-12" id="nombre_comercial">
                                    <input class="form-control" type="text" id="description_costs" name="description_costs"
                                           required value="{{$centroCostos->descripcion}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Tipo</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" id="type" name="type" required>
                                        <option @if($centroCostos->tipo == 'P') selected @endif value="P">
                                            Produccción
                                        </option>
                                        <option @if($centroCostos->tipo == 'A') selected @endif value="A">Apoyo</option>
                                        <option @if($centroCostos->tipo == 'AD') selected @endif value="AD">
                                            Administrativo
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Código Alterno</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="acode" name="acode" required
                                           value="{{$centroCostos->codalterno}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Piso</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2" name="floor" id="floor">
                                        <option @if($centroCostos->piso == 'A') selected @endif value="A">Arena</option>
                                        <option @if($centroCostos->piso == 'C') selected @endif value="C">Cemento
                                        </option>
                                        <option @if($centroCostos->piso == 'T') selected @endif value="T">Tierra
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Techo</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <select class="form-control select2" name="top" id="top">
                                        <option value="A" @if($centroCostos->techo == 'A') selected @endif>Arpillera
                                        </option>
                                        <option value="E" @if($centroCostos->techo == 'E') selected @endif>Estera
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Largo</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="height" name="height" required
                                           value="{{$centroCostos->largo}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Ancho</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="width" name="width" required
                                           value="{{$centroCostos->ancho}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Área</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input class="form-control" type="text" id="area" name="area" readonly
                                           value="{{$centroCostos->capacidad}}">
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
    <script src="{{ asset('anikama/ani/cost.js') }}"></script>
    <script>
        $(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('costs/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('costs/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>
@endsection
