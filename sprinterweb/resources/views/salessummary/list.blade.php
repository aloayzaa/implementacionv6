@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="frm_reporte" id="frm_reporte" method="GET">
                <input type="hidden" value="{{$var}}">
                <div class="x_content">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-md-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="finishdate">Desde:</label>
                                            <input class="form-control"
                                                   type="date"
                                                   id="finishdate"
                                                   name="finishdate"
                                                   min="{{$period->inicio}}"
                                                   max="{{$period->final}}"
                                                   value="{{$period->inicio}}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="finishdate">Hasta:</label>
                                            <input class="form-control"
                                                   type="date"
                                                   id="finishdate"
                                                   name="finishdate"
                                                   min="{{$period->inicio}}"
                                                   max="{{$period->final}}"
                                                   value="{{$period->final}}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <div class="form-group">
                                            <label for="type">Por:</label>
                                            <select class="form-control select2"
                                                    id="type"
                                                    name="type">
                                                <option value="C">Cliente</option>
                                                <option value="A">Producto</option>
                                                <option value="B">Cliente/Producto</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12">
                                        <label for=""><input type="checkbox" id="monthly" name="monthly">
                                            Vista Mensual</label>&nbsp;&nbsp;
                                        <label for=""><input type="checkbox" id="costs" name="costs">
                                            Centro Costo</label>
                                    </div>
                                    <div class="col-md-2 col-xs-12" style="margin-top: 10px;">
                                        <button type="button" onclick="table()"
                                        class="form-control btn-primary"><span class="fa fa-play"></span> Mostrar</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <hr class="reportes">
                                    <div class="contenido_scrollauto">
                                        <input type="hidden" value="{{$var}}" id="var" name="var">
                                        <table id="listSalesSummary" class="table table-striped table-bordered table-bordered2 tbl_ctacte"
                                               class="tablesorter" width="100%">
                                            <thead>
                                            <tr>
                                                {{--
                                                @if($chk_mensual==1)
                                                    <th rowspan="2" class="alinear_centro">Periodo</th>
                                                @endif
                                                @if($tipo=='C')
                                                    <th colspan="2" class="alinear_centro">Cliente</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                @elseif ($tipo  == 'A')
                                                    <th colspan="3" class="alinear_centro">Producto</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                    <th rowspan="2">Cantidad</th>
                                                @elseif ($tipo  == 'B')
                                                    <th colspan="4" class="alinear_centro">Cliente/Producto</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                    <th rowspan="2">Cantidad</th>
                                                @elseif ($tipo  == 'D')
                                                    <th colspan="2" class="alinear_centro">Fecha</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                @elseif ($tipo  == 'M')
                                                    <th colspan="2" class="alinear_centro">Marca</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                @elseif ($tipo  == 'F')
                                                    <th colspan="2" class="alinear_centro">Familia</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                @else
                                                    <th colspan="2" class="alinear_centro">Punto Venta</th>
                                                    @if($chk_ccosto==1)
                                                        <th rowspan="2">C. Costo</th>
                                                    @endif
                                                @endif
                                                <th colspan="4" class="alinear_centro">Venta</th>
                                                <th rowspan="2">Costo(2)</th>
                                                <th rowspan="2">Utilidad(3)</th>
                                                <th colspan="1" class="alinear_centro">% Rent.</th>
                                                <th colspan="1" class="alinear_centro">% Marg. Com</th>
                                                --}}
                                                <th colspan="2" class="alinear_centro">Cliente</th>
                                                <th colspan="4" class="alinear_centro">Venta</th>
                                                <th rowspan="2">Costo(2)</th>
                                                <th rowspan="2">Utilidad(3)</th>
                                                <th colspan="1" class="alinear_centro">% Rent.</th>
                                                <th colspan="1" class="alinear_centro">% Marg. Com</th>
                                            </tr>
                                            <tr>
                                                {{--
                                                @if ($tipo  == 'A')
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Familia</th>
                                                @elseif ($tipo  == 'B')
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                    <th>Producto</th>
                                                    <th>Familia</th>
                                                @else
                                                    <th>Código</th>
                                                    <th>Descripción</th>
                                                @endif
                                                <th>Valor(1)</th>
                                                <th>I.G.V</th>
                                                <th>Servicio</th>
                                                <th>Total</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                --}}
                                                <th>Código</th>
                                                <th>Descripción</th>
                                                <th>Valor(1)</th>
                                                <th>I.G.V</th>
                                                <th>Servicio</th>
                                                <th>Total</th>
                                                <th>Código</th>
                                                <th>Descripción</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                {{--
                                                <th bgcolor="#01DFA5">Totales:</th>
                                                @if($chk_mensual==1)
                                                    @if ($tipo=='A')
                                                        @if($chk_ccosto==1)
                                                            <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @else
                                                            <th colspan="3" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @endif
                                                    @elseif ($tipo=='B')
                                                        @if($chk_ccosto==1)
                                                            <th colspan="5" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @else
                                                            <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @endif
                                                    @else
                                                        @if($chk_ccosto==1)
                                                            <th colspan="3" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                        @else
                                                            <th colspan="2" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if ($tipo=='A')
                                                        @if($chk_ccosto==1)
                                                            <th colspan="3" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @else
                                                            <th colspan="2" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @endif
                                                    @elseif ($tipo=='B')
                                                        @if($chk_ccosto==1)
                                                            <th colspan="4" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @else
                                                            <th colspan="3" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                            <th bgcolor="#01DFA5"></th>
                                                        @endif
                                                    @else
                                                        @if($chk_ccosto==1)
                                                            <th colspan="2" style="text-align:left;" bgcolor="#01DFA5"></th>
                                                        @else
                                                            <th bgcolor="#01DFA5"></th>
                                                        @endif
                                                    @endif
                                                @endif
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                 --}}
                                                <th bgcolor="#01DFA5">Totales:</th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
                                                <th bgcolor="#01DFA5"></th>
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
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('anikama/js2/crud.js') }}"></script>
    <script src="{{ asset('anikama/js2/generales.js') }}"></script>
    <script src="{{ asset('DataTables-1.10.10/media/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('anikama/js2/list.js') }}"></script>
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('anikama/ani/salessummary.js') }}"></script>
    <script>
        $(function () {
            tableListSalesSummary.init('{{ route('list.salessummary') }}');
        });
    </script>
@endsection
