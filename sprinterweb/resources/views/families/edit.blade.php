@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"
                  method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.families', $familia->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" name="id" id="id" value="{{$familia->id}}">
{{--                <input type="hidden" id="ruta_estado" name="ruta_estado" value="{{ route('estado.families') }}">--}}
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-md-2 col-xs-12 label-input">
                                <label for="txt_codigo">Código:</label>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="txt_codigo" name="txt_codigo"
                                       class="form-control" value="{{$familia->codigo}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-md-2 col-xs-12 label-input">
                                <label for="txt_descripcion">Descripción:</label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="txt_descripcion" id="txt_descripcion"
                                       class="form-control" value="{{$familia->descripcion}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-md-2 col-xs-12 label-input">
                                <label for="txt_codigo_sunat">Cód.Sunat:</label>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" name="txt_codigo_sunat" id="txt_codigo_sunat"
                                       class="form-control" value="{{$familia->codsunat}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_pro_sunat_defecto">Producto Sunat por Defecto:</label>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <select class="form-control select2" name="cbo_pro_sunat" id="cbo_pro_sunat">
                                    <option value="{{$familia->productosunat_id}}">{{$familia->productosunat["codigo"]}},{{$familia->productosunat["descripcion"]}}</option>
                                </select>
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
    <script src="{{ asset('anikama/ani/families.js') }}"></script>
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
