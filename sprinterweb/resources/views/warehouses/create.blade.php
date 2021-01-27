@extends('templates.home')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.warehouses') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="email">Código:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text" id="code_almacen" name="code_almacen" class="form-control" required>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="password">Descripción:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" id="description_almacen" name="description_almacen" class="form-control">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Sucursal:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" lang="es" name="subsidiary_almacen" id="subsidiary_almacen">
                                        <option value="">Seleccionar-</option>
                                        @foreach($sucursales  as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->codigo }} | {{ $sucursal->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Dirección:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" class="form-control" name="address" id="address">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="ubigeo">Ubigeo:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <select class="form-control select2" lang="es" name="ubigeo" id="ubigeo">
                                        <option value="">Seleccionar-</option>
                                        @foreach($ubigeo  as $ubigeo)
                                            <option value="{{ $ubigeo->id }}">{{$ubigeo->codigo}} | {{ $ubigeo->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="name">Nombre Corto:</label>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" class="form-control" name="shortname_almacen" id="shortname_almacen">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="stablishment">Cod. Establecimiento:</label>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <input type="text" id="stablishment_almacen" name="stablishment_almacen" class="form-control">
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <label for="" class="label-input"><input type="checkbox" name="esconsignacion" id="esconsignacion">Es Almacén de Consignación o Warrant</label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-2 col-xs-12 label-input">
                                    <label for="codwarrant">Cod. Almacén en empresa WARRANT:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text" id="codwarrant" name="codwarrant" class="form-control">
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
    <script src="{{ asset('anikama/ani/warehouses.js') }}"></script>
@endsection

