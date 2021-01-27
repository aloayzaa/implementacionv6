@extends('templates.app')
@section('content')
    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.purchasetypes', $tipocompra->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{$tipocompra->id}}">
                <input type="hidden" id="estado" name="estado" value="{{ $tipocompra->estado }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Código</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="code_tipocompras" name="code_tipocompras" value="{{$tipocompra->codigo}}" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Descripción</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input class="form-control" type="text" id="description_tipocompras" name="description_tipocompras" required value="{{$tipocompra->descripcion}}">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Tipo Transaccion Asociada</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" id="type_tipocompras" name="type_tipocompras" required>
                                        <option value="">Seleccionar..</option>
                                        @foreach($transactions as $transaction)
                                            <option value="{{$transaction->id}}" @if($tipocompra->tipotransaccion_id == $transaction->id) selected @endif>
                                                {{$transaction->codigo}} | {{$transaction->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">Subdiario Asociado</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" id="asociado_tipocompras" name="asociado_tipocompras" required>
                                        <option value="">Seleccionar..</option>
                                        @foreach($subdiarios as $subdiario)
                                            <option value="{{$subdiario->id}}" @if($tipocompra->subdiario_id == $subdiario->id) selected @endif>
                                                {{$subdiario->codigo}} | {{$subdiario->descripcion}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">&nbsp;</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-6 col-sm-2 col-xs-12 form-group">
                                        {{--<label class="top-check-down" for="period">--}}
                                            <input type="checkbox" id="chkservice" name="chkservice" @if($tipocompra->esservicio == 1) checked @endif>
                                            Es por servicios
                                        {{--</label>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">&nbsp;</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="col-md-6 col-sm-2 col-xs-12 form-group">
                                        {{--<label class="top-check-down" for="period">--}}
                                            <input type="checkbox" id="chkimportation" name="chkimportation" @if($tipocompra->esimporta == 1) checked @endif>
                                            Es Importación
                                        {{--</label>--}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="tradeName">&nbsp;</label>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <div class="col-md-6 col-sm-2 col-xs-12 form-group">
                                        {{--<label class="top-check-down" for="period">--}}
                                            <input type="checkbox" id="chktax" name="chktax" @if($tipocompra->esretencion == 1) checked @endif>
                                            Es para el registro de los documentos de retencion de IGV
                                        {{--</label>--}}
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
    <script src="{{ asset('anikama/ani/purchasetypes.js') }}"></script>
    <script>
        $(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('purchasetypes/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('purchasetypes/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>
@endsection
