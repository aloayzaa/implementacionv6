@extends('templates.home')

@section('content')
    <div class="x_panel">
        <div class="x_content">
            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.subsidiaries') }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="var" name="var" value="{{ $var }}">
                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                <div class="container-fluid" style="background-color: white">
                    <div class="col-md-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Código: </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="code" name="code" required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Descripción: </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="description" name="description" required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Dirección: </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="address" name="address" required>
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
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Nombre: </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="name" name="name" required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Cargo: </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="text" id="charge" name="charge" required>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <label for="name"> Teléfonos: </label>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <input class="form-control" type="number" id="phones" name="phones" required>
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
@endsection
