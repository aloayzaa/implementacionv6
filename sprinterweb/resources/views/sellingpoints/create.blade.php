@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{0}}">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.sellingpoints') }}">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="txt_codigo">Código:</label>
                                <input type="text" class="form-control" id="txt_codigo" name="txt_codigo">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="txt_descripcion">Descripción:</label>
                                <input type="text" class="form-control" id="txt_descripcion" name="txt_descripcion">
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="cbo_almacen">Almacén:</label>
                                <select name="cbo_almacen" id="cbo_almacen" class="select2">
                                    <option value="" selected>-Seleccionar-</option>
                                    @foreach($almacenes as $almacen)
                                        <option value="{{$almacen->id}}">{{$almacen->codigo}} | {{$almacen->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="cbo_tipoventa">Tipo Venta:</label>
                                <select name="cbo_tipoventa" id="cbo_tipoventa" class="select2">
                                    <option value="" selected>-Seleccionar-</option>
                                    @foreach($tipoventas as $tipoventa)
                                        <option value="{{$tipoventa->id}}">{{$tipoventa->codigo}} | {{$tipoventa->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="cbo_centrocosto">Centro Costo:</label>
                                <select name="cbo_centrocosto" id="cbo_centrocosto" class="select2">
                                    <option value="" selected>-Seleccionar-</option>
                                    @foreach($centrocostos as $centrocosto)
                                        <option value="{{$centrocosto->id}}">{{$centrocosto->codigo}} | {{$centrocosto->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="cbo_proyecto">Proyecto:</label>
                                <select name="cbo_proyecto" id="cbo_proyecto" class="select2">
                                    <option value="" selected>-Seleccionar-</option>
                                    @foreach($proyectos as $proyecto)
                                        <option value="{{$proyecto->id}}">{{$proyecto->codigo}} | {{$proyecto->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <label for="cbo_ctactebanco">Fondo o caja:</label>
                                <select name="cbo_ctactebanco" id="cbo_ctactebanco" class="select2">
                                    <option value="" selected>-Seleccionar-</option>
                                    @foreach($ctactebancos as $ctactebanco)
                                        <option value="{{$ctactebanco->id}}">{{$ctactebanco->codigo}} | {{$ctactebanco->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="col-md-2 col-sm-2 col-xs-12" style="margin-top: 7px; margin-left: -10px;">
                                    <label for="opt_precioventa">Precio Venta:</label>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <label class="radio-inline"><input type="radio" name="opt_precioventa" value="1" checked>1</label>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <label class="radio-inline"><input type="radio" name="opt_precioventa" value="2">2</label>
                                </div>
                                <div class="col-md-1 col-sm-1 col-xs-12">
                                    <label class="radio-inline"><input type="radio" name="opt_precioventa" value="3">3</label>
                                </div>
                                <div class="col-md-7 col-sm-7 col-xs-12" style="margin-top: 7px;">
                                    <p> del Tarifario utilizando por defecto</p>
                                </div>
                            </div>
                            {{--
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <label for="txt_nromesas">Nro. Mesas</label>
                                <input type="text" class="form-control" name="txt_nromesas" id="txt_nromesas">
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <button type="button" class="btn btn-xs btn-primary">Configurar Carta</button>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12">
                                <button type="button" class="btn btn-xs btn-primary">Asociar Productos</button>
                            </div>
                            --}}
                        </div>
                        <div class="row">
                            <div class="ln_solid"></div>
                        </div>
                        <div class="row">
                            <p>Series por documentos asociados</p>
                        </div>
                        <div class="row">
                            <table id="listAssociatedDocuments"
                                   class="table table-striped table-bordered" width="100%">
                                <thead>
                                <tr role="row">
                                    <th>Item</th>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Serie</th>
                                    <th>Líneas</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            @include('sellingpoints.modal.documento_asociado')
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/sellingpoints.js') }}"></script>
@endsection
