@extends('templates.home')

@section('content')

    <div class="x_panel identificador ocultar">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.subsidiaries', $sucursal->id) }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <input type="hidden" id="id" name="id" value="{{ $sucursal->id }}">
                <div class="container-fluid" style="background-color: white">
                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-default">
                             <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="email">Codigo:</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="text" id="code" name="code" value="{{ old('codigo', $sucursal->codigo) }}" class="form-control" required>
                                    </div>
                                </div>
                                 <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="password">Descripción:</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="text" id="description" name="description" value="{{ old('description', $sucursal->descripcion) }}" class="form-control">
                                    </div>
                                </div>
                                 <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="name">Dirección:</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="text" class="form-control" name="address" id="address" value="{{ old('direccion', $sucursal->direccion) }}">
                                    </div>
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <label for="">Datos del Contacto</label>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="name"> Nombre:</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="text" class="form-control" name="name" id="name" value="{{ old('contacto', $sucursal->contacto) }}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="phone"> Cargo:</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="text" id="charge" name="charge" value="{{ old('phone', $sucursal->cargo) }}" class="form-control">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12 label-input">
                                        <label for="phone"> Teléfono:</label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input type="number" id="phones" name="phones" value="{{ old('phone', $sucursal->telefono) }}" class="form-control">
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
    <script src="{{ asset('anikama/ani/subsidiaries.js') }}"></script>
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

