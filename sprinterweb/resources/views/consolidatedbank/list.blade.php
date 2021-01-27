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
                                <label for="bank">Banco:</label>
                                <select class="form-control select2"
                                        id="bank"
                                        name="bank">
                                    <option value="0" selected>Todos los Bancos</option>
                                    @foreach($bancos as $banco)
                                        <option value="{{$banco->id}}">{{$banco->codigo}}
                                            | {{$banco->descripcion}}</option>
                                    @endforeach
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
                                <label for="currency">Moneda:</label>
                                <select class="form-control select2"
                                        id="currency"
                                        name="currency">
                                    <option selected value="O">Moneda Origen</option>
                                    <option value="N">Soles</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-4">
                                <button type="button" class="form-control btn-primary top-check-down"
                                        value="{{route('list.consolidatedbank')}}" name="reporte" id="reporte">
                                    <span class="fa fa-play"></span>
                                    Mostrar
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <hr class="reportes">
                            <div class="contenido_scrollauto">
                                <table id="listConsolidatedBank" class="table table-striped table-bordered table-bordered2 tbl_ctacte" class="tablesorter">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Banco</th>
                                        <th colspan="3">Cuenta Corriente</th>
                                        <th rowspan="2">S. Inicial</th>
                                        <th rowspan="2">Ingreso</th>
                                        <th rowspan="2">Egreso</th>
                                        <th rowspan="2">S. Final</th>
                                    </tr>
                                    <tr>
                                        <th>Número</th>
                                        <th>Cuenta</th>
                                        <th>Moneda</th>

                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th colspan="1" style="text-align:left;" bgcolor="#01DFA5">Totales:</th>
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
    <script src="{{ asset('anikama/ani/consolidatedbank.js') }}"></script>
    <script>
        $(function () {
            tableListConsolidatedBank.init('{{ route('list.consolidatedbank') }}');
        });
    </script>
    @include('templates.export_files')
@endsection
