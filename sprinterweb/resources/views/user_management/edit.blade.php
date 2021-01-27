@extends('templates.app')

@section('content')

    <div class="x_panel">
        <div class="x_content">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 identificador ocultar">
                            <form class="form-horizontal" id="frm_generales" name="frm_generales" method="PUT">
                                <input type="hidden" id="ruta" name="ruta" value="{{ route('update.user_management', $usuario->id) }}">
                                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" id="var" name="var" value="{{ $var }}">
                                <input type="hidden" id="proceso" name="proceso" value="{{ $proceso }}">
                                <input type="hidden" id="id" name="id" value="{{ $usuario->id }}">
                                <input type="hidden" id="estado" name="estado" value="{{ $usuario->estado }}">
                                <div class="col-md-2">
                                    <label for="">Código:</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="codigo" name="codigo" class="form-control" value="{{$usuario->codigo}}">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Déscripcion:</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" id="descripcion" name="descripcion" value="{{$usuario->descripcion}}" class="form-control">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Email::</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" id="email" name="email" class="form-control" value="{{ $usuario->email }}">
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for=""></label>
                                </div>
                                <div class="col-md-10">
                                    <input type="checkbox" id="estipo" name="estipo" @if($usuario->estipo == 1) checked @endif>Es un usuario tipo
                                </div>
                                <div class="col-md-12">
                                    <br>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Dscto. Autorizado:</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="pdescuento" name="pdescuento" class="form-control" value="{{$usuario->pdescuento}}">
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
                            <div class="col-md-12 col-sm-12">
                                @include('user_management.modals.privilegios')
                                @include('user_management.modals.cargar_usuarios')
                            </div>
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
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script>
        $(function () {
            loadUser.init('{{ route('loaduser.user_management') }}');
            masterproducts.init('{{ route('maestrosproductos.user_management') }}');
            mastercustomer.init('{{ route('maestrosterceros.user_management') }}');
            mastercosts.init('{{ route('maestroscostos.user_management') }}');
            masterothers.init('{{ route('maestrosotros.user_management') }}');
            shoppingconfigurations.init('{{ route('comprasconfiguracion.user_management') }}');
            shoppingprocess.init('{{ route('comprasprocesos.user_management') }}');
            shoppingreports.init('{{ route('comprasreportes.user_management') }}');
            shoppingtransaccions.init('{{ route('comprastransacciones.user_management') }}');
            salesconfigurations.init('{{ route('ventasconfiguracion.user_management') }}');
            salesprocess.init('{{ route('ventasprocesos.user_management') }}');
            salesreports.init('{{ route('ventasreportes.user_management') }}');
            salestransaccions.init('{{ route('ventastransacciones.user_management') }}');
            warehouseconfigurations.init('{{ route('logisticaconfiguracion.user_management') }}');
            warehouseprocess.init('{{ route('logisticaprocesos.user_management') }}');
            warehousereports.init('{{ route('logisticareportes.user_management') }}');
            warehousetransaccions.init('{{ route('logisticatransacciones.user_management') }}');
            treasuryconfigurations.init('{{ route('tesoreriaconfiguracion.user_management') }}');
            treasuryprocess.init('{{ route('tesoreriaprocesos.user_management') }}');
            treasuryreports.init('{{ route('tesoreriareportes.user_management') }}');
            treasurytransaccions.init('{{ route('tesoreriatransacciones.user_management') }}');
            accountingconfigurations.init('{{ route('contabilidadconfiguracion.user_management') }}');
            accountingprocess.init('{{ route('contabilidadprocesos.user_management') }}');
            accountingreports.init('{{ route('contabilidadreportes.user_management') }}');
            accountingtransaccions.init('{{ route('contabilidadtransacciones.user_management') }}');
            specialaccounts.init('{{ route('especial.user_management') }}');
            specialaccounts2.init('{{ route('especial2.user_management') }}');
            tributaryconfigurations.init('{{ route('tributosconfiguracion.user_management') }}');
            tributaryprocess.init('{{ route('tributosprocesos.user_management') }}');
            tributaryreports.init('{{ route('tributosreportes.user_management') }}');
            tributarytransaccions.init('{{ route('tributostransacciones.user_management') }}');
            activeconfigurations.init('{{ route('activosconfiguracion.user_management') }}');
            activeprocess.init('{{ route('activosprocesos.user_management') }}');
            activereports.init('{{ route('activosreportes.user_management') }}');
            activetransaccions.init('{{ route('activostransacciones.user_management') }}');
            utilitarianoptions.init('{{ route('utilitarioopciones.user_management') }}');
        });
    </script>
@endsection
