@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="POST">
                                <input type="hidden" id="ruta" name="ruta" value="{{ route('store.user_management') }}">
                                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" id="var" name="var" value="{{ $var }}">
                                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                                <div class="col-md-2">
                                    <label for="">Código:</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="codigo" name="codigo" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Déscripcion:</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" id="descripcion" name="descripcion" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Email::</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" id="email" name="email" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for=""></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="checkbox" id="estipo" name="estipo">Es un usuario tipo
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Dscto. Autorizado:</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="pdescuento" name="pdescuento" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                    <label for="">Puntos de Emisión por Usuario</label>
                                    <table class="table table-bordered">
                                        <thead class="text-center">
                                        <tr>
                                            <td>Sel</td>
                                            <td>Código</td>
                                            <td>Descripción</td>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            {!! $botones !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud2.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('anikama/ani/user_management.js') }}"></script>
@endsection
