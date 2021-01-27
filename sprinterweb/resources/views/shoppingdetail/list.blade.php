@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <form name="frm_reporte" id="frm_reporte" method="GET">
                                    <input type="hidden" value="{{ route('rptcompras.shoppingdetail') }}" id="ruta">
                                    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="initialdate">Desde:</label>
                                            <input class="form-control" type="date" id="initialdate" name="initialdate"
                                                   value="{{$fecha}}">
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <label for="finishdate">Hasta:</label>
                                            <input class="form-control" type="date" id="finishdate" name="finishdate"
                                                   max="{{$period->final}}" value="{{$fecha}}">
                                        </div>
                                        <div class="col-md-2 col-xs-12 label-input">
                                            <br>
                                            <input type="checkbox" id="lctipo">
                                            <label for="">Por Modelo</label>
                                        </div>
                                        <div class="col-md-2 col-xs-12">
                                            <button type="button" id="mostrar" name="mostrar"
                                                    class="form-control btn-primary">
                                                <span class="fa fa-play"></span> Mostrar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <br><br><br>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <table id="listShoppingDetail" class="table table-striped table-bordered table-bordered2" width="100%">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Sucursal</th>
                                        <th rowspan="2">Num. Reg.</th>
                                        <th colspan="3">Documento</th>
                                        <th colspan="2">Cuenta/Producto</th>
                                        <th colspan="2">Fam. Producto</th>
                                        <th colspan="3">M. Nacional</th>
                                        <th colspan="3">M. Extranjera</th>
                                        <th rowspan="2">Tipo Cambio</th>
                                        <th colspan="2">O. Compra/Servicio</th>
                                        <th colspan="2">Proveedor</th>
                                        <th colspan="2">Centro Costo</th>
                                        <th colspan="2">Actividad</th>
                                        <th colspan="2">Proyecto</th>
                                        <th colspan="2">Asiento Dest.</th>
                                        <th rowspan="2">Usuario</th>
                                    </tr>
                                    <tr>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Mon</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>V. Compra</th>
                                        <th>I.G.V.</th>
                                        <th>Importe</th>
                                        <th>V. Compra</th>
                                        <th>I.G.V.</th>
                                        <th>Importe</th>
                                        <th>Número</th>
                                        <th>Fecha</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Cargo</th>
                                        <th>Abono</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="9" bgcolor="#01DFA5">Totales:</th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th colspan="12" bgcolor="#01DFA5"></th>
                                    </tr>
                                    </tfoot>
                                </table>
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
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/shoppingdetail.js') }}"></script>
    <script>
        $(function () {
            /*tableListShoppingDetail.init('{{ route('rptcompras.shoppingdetail') }}');*/
        });
    </script>
    @include('templates.export_files')
@endsection
