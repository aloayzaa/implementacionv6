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
                <input type="hidden" id="id" name="id" value="{{0}}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-7 col-sm-7 col-xs-10">
                            <div class="panel panel-info">
                                <div class="panel-heading">Registro Trifa</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                                <label class="col-sm-12 col-xs-12" for="txt_sucursal">Sucursal: </label>
                                            </div>
                                            <div class="col-md-5 col-xs-12 label-input">
                                                <select class="form-control select2" name="txt_sucursal"
                                                        id="txt_sucursal">
                                                    <option value="" selected>-- Seleccione una opción --</option>
                                                    @foreach($sucursales as $sucursal)
                                                        <option value="{{ $sucursal->id }}">
                                                            {{ $sucursal->codigo }}
                                                            | {{ $sucursal->descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                                <label class="col-sm-12 col-xs-12" for="cbo_producto">Producto:</label>
                                            </div>
                                            <div class="col-md-5 col-xs-12">
                                                <select class="form-control select2" id="cbo_producto"
                                                        name="cbo_producto">
                                                    <option value="">-Seleccione-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                                <label class="col-sm-12 col-xs-12">U.Medida:</label>
                                            </div>
                                            <div class="col-md-5 col-xs-12 label-input">
                                                <select class="select2" id="un_medida" name="un_medida">
                                                    <option value="">--Seleccione-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2 col-xs-12 label-input"  style="width: 100px">
                                                <label class="col-sm-12 col-xs-12" for="">Moneda:</label>
                                            </div>
                                            <div class="col-md-5 col-xs-12 label-input">
                                                <select class="form-control select2" name="currency" id="currency">
                                                    <option value="">--Seleccione-</option>
                                                    @foreach($monedas as $moneda)
                                                        <option
                                                            value="{{$moneda->id}}"> {{$moneda->descripcion}} </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                            <label class="col-sm-12 col-xs-12" for="precio1">Precio 1: </label>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <input class="form-control" type="text" name="txt_precio1" id="txt_precio1">
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                            <label class="col-sm-12 col-xs-12" for="precio3">Precio 3: </label>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <input class="form-control" type="text" name="txt_precio3" id="txt_precio3">
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                            <label class="col-sm-12 col-xs-12" for="precio3">Precio 5: </label>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <input class="form-control" type="text" name="txt_precio3" id="txt_precio3">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                            <label class="col-sm-12 col-xs-12" for="precio2">Precio 2: </label>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <input class="form-control" type="text" name="txt_precio2" id="txt_precio1">
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                            <label class="col-sm-12 col-xs-12" for="precio4">Precio 4: </label>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <input class="form-control" type="text" name="txt_precio6" id="txt_precio6">
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input" style="width: 100px">
                                            <label class="col-sm-12 col-xs-12" for="precio6">Precio 6: </label>
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <input class="form-control" type="text" name="txt_precio6" id="txt_precio6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-8">
                            <div class="panel panel-info">
                                <div class="panel-heading">Condiciones Filtro</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="filtro"></label>
                                            <textarea class="form-control" id="filtro" name="filtro" rows="5" readonly></textarea>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-primary form-control text-center" id="btn_actualizar" name="btn_actualizar">
                                        <span class="fa flaticon-logout"></span> Actualizar Precio a Toda la lista
                                    </button>
                                </div>
                            </div>
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
        </div>
    </div>



@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/pricelists.js') }}"></script>


@endsection
