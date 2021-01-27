@extends('templates.app')
@section('scripts_css')
    <link rel="stylesheet" href="{{ asset('DataTables-1.10.10/media/css/jquery.dataTables.css') }}">
@endsection
@section('content')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form name="frm_reporte" id="frm_generales" method="GET">
                <input type="hidden" value="{{$var}}" id="var" name="var">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <label for="bank" ">Banco:</label>
                                <select class="form-control select2"
                                        id="bank"
                                        name="bank">
                                    <option selected value="0">Todos los Bancos</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{$banco->id}}">{{$banco->codigo}}
                                            | {{$banco->descripcion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <label for="currentaccount">Cuenta Corriente:</label>
                                <select class="form-control select2"
                                        id="currentaccount"
                                        name="currentaccount">
                                    <option selected value="0">Todas las Cuentas</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <label for="initialdate">Desde:</label>
                                <input class="form-control"
                                       type="date"
                                       id="initialdate"
                                       name="initialdate"
                                       min="{{$period->inicio}}"
                                       max="{{$period->final}}"
                                       value="{{$period->inicio}}"
                                       required>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
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
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <label for="detailed" class="top-check-down"><input type="checkbox" name="detailed" id="detailed">
                                    Detallado</label>

                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 form-group">
                                <button type="button" onclick="table()" class="form-control btn-primary top-check-down"><span class="fa fa-play"></span> Mostrar</button>
                            </div>
                        </div>
                        <div class="row">
                            <hr class="reportes">
                            <div class="contenido_scrollauto">
                                <table id="listCashBankBook" class="table table-striped table-bordered table-bordered2 tbl_ctacte" class="tablesorter">
                                    <thead>
                                    <tr>
                                        {{--
                                        <th rowspan="2">Sucursal</th>
                                        <th rowspan="2">Cuenta Corriente</th>
                                        <th rowspan="2">Fecha</th>
                                        <th rowspan="2">Voucher</th>
                                        @if ($chk_detalle==1)
                                            <th colspan="1">Tipo</th>
                                            <th rowspan="2">Cuenta</th>
                                            <th rowspan="2">C. Costo</th>
                                            <th colspan="2">Comprobante Pago</th>
                                        @endif
                                        <th rowspan="2">Glosa</th>
                                        <th colspan="1">Número</th>
                                        <th rowspan="2">Cheque</th>
                                        <th rowspan="2">Ingresos</th>
                                        <th rowspan="2">Egresos</th>
                                        <th rowspan="2">Saldo</th>
                                        <th rowspan="2">T.C.</th>
                                        @if ($chk_detalle==1)
                                            <th rowspan="2">Razón Social</th>
                                        @else
                                            <th rowspan="2">Cheque Girado</th>
                                        @endif
                                        --}}
                                        <th rowspan="2">Sucursal</th>
                                        <th rowspan="2">Cuenta Corriente</th>
                                        <th rowspan="2">Fecha</th>
                                        <th rowspan="2">Voucher</th>
                                        <th rowspan="2">Glosa</th>
                                        <th colspan="1">Número</th>
                                        <th rowspan="2">Cheque</th>
                                        <th rowspan="2">Ingresos</th>
                                        <th rowspan="2">Egresos</th>
                                        <th rowspan="2">T.C.</th>
                                        <th rowspan="2">Cheque Girado</th>
                                    </tr>
                                    <tr>
                                        {{--
                                        @if ($chk_detalle==1)
                                            <th>Operacíón</th>
                                            <th>Documento</th>
                                            <th>Nro. Interno</th>
                                        @endif
                                        <th>Transacción</th>
                                        --}}
                                        <th>Transacción</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        {{--
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        @if ($chk_detalle==1)
                                            <th colspan="11" style="text-align:left;" bgcolor="#01DFA5"></th>
                                        @else
                                            <th colspan="6" style="text-align:left;" bgcolor="#01DFA5"></th>
                                        @endif
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th colspan="2" bgcolor="#01DFA5"></th>
                                        --}}
                                        <th bgcolor="#01DFA5">Totales:</th>
                                        <th colspan="5" style="text-align:left;" bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th bgcolor="#01DFA5"></th>
                                        <th colspan="2" bgcolor="#01DFA5"></th>
                                    </tr>
                                    </tfoot>
                                </table>
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
    <script src="{{ asset('anikama/ani/cashbankbook.js') }}"></script>
    <script>
        $(function () {
            tableListCashBankBook.init('{{ route('list.cashbankbook') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
