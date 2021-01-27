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
                        <div class="col-md-12 col-xs-12">
                            <form name="frm_reporte" id="frm_reporte" method="GET">
                                <input type="hidden" value="{{ route('rptcompras.shoppingsummary') }}" id="ruta">
                                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="initialdate">Desde:</label>
                                            <input class="form-control" type="date" id="initialdate" name="initialdate" value="{{$fecha}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="finishdate">Hasta:</label>
                                            <input class="form-control" type="date" id="finishdate" name="finishdate" max="{{$period->final}}" value="{{$fecha}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="type">Por:</label>
                                            <select class="form-control" id="type" name="type">
                                                <option value="C">Proveedor</option>
                                                <option value="A">Producto/Cuenta</option>
                                                <option value="S">Sucursal</option>
                                                <option value="T">Tipo Compra</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="type">Moneda</label>
                                            <select class="form-control" id="money" name="money">
                                                <option value="N">Nuevo Soles</option>
                                                <option value="E">Dólares</option>
                                                <option value="C">Cantidades</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <br>
                                        <div class="form-group">
                                            <input type="checkbox" id="comportamiento">Comportamiento Mensual
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <br>
                                        <div class="form-group">
                                            <button type="button" id="mostrar" name="mostrar" class="form-control btn-primary">
                                                <span class="fa fa-play"></span> Mostrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <br><br><br>
                        <div class="row">
                            <hr class="reportes">
                            <div class="contenido_scrollauto">
                                <table id="listShoppingSummary"
                                       class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                       class="tablesorter" width="100%">
                                    <thead>
                                    <tr>
                                        <th colspan="2" class="alinear_centro">Producto/Cuenta</th>
                                        <th rowspan="2">Sub Total</th>
                                        <th rowspan="2">I.G.V</th>
                                        <th rowspan="2">Total</th>
                                    </tr>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
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
    <script src="{{ asset('anikama/ani/shoppingsummarys.js') }}"></script>
    <script>
        $(function () {
            /*tableListShoppingSummary.init('{{ route('rptcompras.shoppingsummary') }}');*/
        });
    </script>
@endsection
