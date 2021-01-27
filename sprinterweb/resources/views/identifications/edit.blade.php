@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.identifications', $documentoide->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" name="id" id="id" value="{{$documentoide->id}}">
{{--                <input type="hidden" id="ruta_estado" name="ruta_estado" value="{{ route('estado.identifications') }}">--}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-md-2 col-xs-12 label-input">
                                <label for="txt_codigo">Código:</label>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="txt_codigo_identi" name="txt_codigo_identi"
                                       class="form-control" value="{{$documentoide->codigo}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-md-2 col-xs-12 label-input">
                                <label for="txt_descripcion">Descripción:</label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="txt_descripcion_ident" id="txt_descripcion_ident"
                                       class="form-control" value="{{$documentoide->descripcion}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-md-2 col-xs-12 label-input">
                                <label for="txt_codigo_sunat">Cód.Sunat:</label>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" name="txt_codigo_sunat" id="txt_codigo_sunat"
                                       class="form-control" value="{{$documentoide->codsunat}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_simbolo">Simbolo:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" id="txt_simbolo" name="txt_simbolo"
                                       class="form-control" value="{{$documentoide->simbolo}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_longitud">Longitud:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="number" name="txt_longitud" id="txt_longitud"
                                       class="form-control" value="{{$documentoide->longitud}}">
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
    <script src="{{ asset('anikama/ani/identifications.js') }}"></script>
    <script>
        $(function () {
            if(performance.navigation.type == 0){
                if(document.referrer.includes('identifications/create')){
                    success('success', 'El registro se realizó correctamente', 'Guardado!');
                }
                if(document.referrer.includes('identifications/edit')){
                    success('success', 'El registro se actualizó correctamente', 'Actualizado!');
                }
            }
        });
    </script>

@endsection
