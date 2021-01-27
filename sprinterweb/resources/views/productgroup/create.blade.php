@extends('templates.home')
@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales"  method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.productgroups') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ 0 }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_codigo">Código:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="text" id="txt_codigo_gp" name="txt_codigo_gp" class="form-control" value="{{ $codigo }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_descripcion">Descripción:</label>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <input type="text" name="txt_descripcion_gp" id="txt_descripcion_gp" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_codigo_anterior">Codigo Anterior:</label>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <input type="text" name="txt_codigo_anterior" id="txt_codigo_anterior" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_cantidad">Cantidad:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="number" name="txt_cantidad" id="txt_cantidad" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_yapa">Yapa:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="number" name="txt_yapa" id="txt_yapa" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="cbo_marca">Marca:</label>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <select name="cbo_marca" id="cbo_marca" class="form-control select2">
                                    @foreach($marca as $m)
                                        <option value="{{$m->id}}">{{$m->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1 col-sm-2 col-xs-12 label-input">
                                <label for="txt_descuento">% Descuento:</label>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12">
                                <input type="text" name="txt_descuento" id="txt_descuento" class="form-control">
                            </div>
                        </div>
                        <br>
                        {{--
                        <div class="row">
                            <div class="col-md-2 col-sm-3 col-xs-12">
                                <a href="" class="form-control btn-primary" style="text-align: center"><span class="fa fa-refresh"></span> Asociar Productos</a>
                            </div>
                        </div>
                        --}}
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/productgroup.js') }}"></script>

@endsection